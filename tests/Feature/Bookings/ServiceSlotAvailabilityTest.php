<?php

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Service;
use App\Services\DateWrapper;
use App\Services\ServiceSlotAvailability;
use App\Services\Slot;
use Carbon\Carbon;

describe('Service Slot Availability', function () {
    it('returns available time slots for a service', function () {
        Carbon::setTestNow(Carbon::parse('1st April 2026'));

        $employee = Employee::factory()
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->endOfDay(),
            ]))
            ->create();

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $availability = (new ServiceSlotAvailability(collect([$employee]), $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            );

        expect($availability->first()->date->toDateString())->toBe(Carbon::now()->toDateString());
        expect($availability->first()->slots)->toHaveCount(16);
    });

    it('returns list of multiple slots over more than one day', function () {
        Carbon::setTestNow(Carbon::parse('1st April 2026'));

        $employee = Employee::factory()
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->addMonth()->endOfDay(),
            ]))
            ->create();

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $availability = (new ServiceSlotAvailability(collect([$employee]), $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->addDay()->endOfDay()
            );

        expect($availability->map(fn (DateWrapper $date) => $date->date->toDateString()))
            ->toContain(
                Carbon::now()->toDateString(),
                Carbon::now()->addDay()->toDateString(),
            )
            ->toHaveCount(2);

        expect($availability->first()->slots)->toHaveCount(16);
        expect($availability->last()->slots)->toHaveCount(16);
    });

    it('excludes booked appointments for the employee', function () {
        Carbon::setTestNow(Carbon::parse('1st April 2026'));

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $employee = Employee::factory()
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->endOfDay(),
            ]))
            ->has(Appointment::factory()->for($service)->state([
                'starts_at' => Carbon::now()->setTimeFromTimeString('12:00'),
                'ends_at'   => Carbon::now()->setTimeFromTimeString('12:30'),
            ]))
            ->create();

        $availability = (new ServiceSlotAvailability(collect([$employee]), $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            );

        $slots = $availability->map(fn (DateWrapper $date) => $date->slots->map(fn (Slot $slot) => $slot->time->toTimeString()))->flatten();

        expect($slots)
            ->toContain('11:30:00')
            ->not()->toContain('12:00:00')
            ->toContain('12:30:00')
            ->toContain('13:00:00');
    });

    it('ignores cancelled appointments', function () {
        Carbon::setTestNow(Carbon::parse('1st April 2026'));

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $employee = Employee::factory()
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->endOfDay(),
            ]))
            ->has(Appointment::factory()->for($service)->state([
                'starts_at'    => Carbon::now()->setTimeFromTimeString('12:00'),
                'ends_at'      => Carbon::now()->setTimeFromTimeString('12:30'),
                'cancelled_at' => Carbon::now(),
            ]))
            ->create();

        $availability = (new ServiceSlotAvailability(collect([$employee]), $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            );

        $slots = $availability->map(fn (DateWrapper $date) => $date->slots->map(fn (Slot $slot) => $slot->time->toTimeString()))->flatten();

        expect($slots)
            ->toContain('11:30:00')
            ->toContain('12:00:00') // This slot should be available since the appointment is cancelled
            ->toContain('12:30:00')
            ->toContain('13:00:00');
    });

    it('show multiple employees available for the same service', function () {
        Carbon::setTestNow(Carbon::parse('1st April 2026'));

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $employees = Employee::factory()
            ->count(2)
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->endOfDay(),
            ]))
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->endOfDay(),
            ]))
            ->create();

        $availability = (new ServiceSlotAvailability($employees, $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            );

        expect($availability->first()->slots->first()->employees)->toHaveCount(2);
    });
});
