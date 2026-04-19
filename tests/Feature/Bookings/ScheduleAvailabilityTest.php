<?php

use App\Models\Employee;
use App\Models\Schedule;
use App\Models\ScheduleExclusion;
use App\Models\Service;
use App\Services\ScheduleAvailability;
use Carbon\Carbon;
use Spatie\Period\Period;

describe('Schedule Availability', function () {

    it('returns daily availability based on default schedule', function () {
        Carbon::setTestNow(Carbon::parse('1st April 2026'));

        $employee = Employee::factory()
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->addYear()->endOfDay(),
            ]))
            ->create();

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $availability = (new ScheduleAvailability($employee, $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            );

        expect($availability->current())
            ->toBeInstanceOf(Period::class)
            ->startsAt(now()->setTimeFromTimeString('09:00'))
            ->toBeTrue()
            ->endsAt(now()->setTimeFromTimeString('16:30'))
            ->toBeTrue();
    });

    it('returns availability per day based on weekday schedule', function () {
        Carbon::setTestNow(Carbon::parse('monday 1st April 2026')); // monday 1st April 2026

        $employee = Employee::factory()
            ->has(Schedule::factory()->state([
                'starts_date'       => Carbon::now()->startOfDay(),
                'ends_date'         => Carbon::now()->addYear()->endOfDay(),
                'monday_starts_at'  => '12:00', // monday 1st April 2026
                'monday_ends_at'    => '18:00', // monday 1st April 2026
                'tuesday_starts_at' => '09:00', // tuesday 2nd April 2026
                'tuesday_ends_at'   => '17:00', // tuesday 2nd April 2026
            ]))
            ->create();

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $availability = (new ScheduleAvailability($employee, $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->addDay()->endOfDay()
            );

        $mondayAvailability = $availability->current(); // monday 1st April 2026

        expect($mondayAvailability)
            ->toBeInstanceOf(Period::class)
            ->startsAt(now()->setTimeFromTimeString('12:00'))
            ->toBeTrue()
            ->endsAt(now()->setTimeFromTimeString('17:30'))
            ->toBeTrue();

        $availability->next(); // to switch to tuesday 2nd April 2026

        $tuesdayAvailability = $availability->current(); // tuesday 2nd April 2026

        expect($tuesdayAvailability)
            ->toBeInstanceOf(Period::class)
            ->startsAt(now()->addDay()->setTimeFromTimeString('09:00'))
            ->toBeTrue()
            ->endsAt(now()->addDay()->setTimeFromTimeString('16:30'))
            ->toBeTrue();
    });

    it('returns availability excluding full-day and partial exclusions', function () {
        Carbon::setTestNow(Carbon::parse('1st April 2026'));

        $employee = Employee::factory()
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->addYear()->endOfDay(),
            ]))
            // Exclude the whole day of 2nd April 2026
            ->has(ScheduleExclusion::factory()->state([
                'starts_at' => Carbon::now()->addDay()->startOfDay(),
                'ends_at'   => Carbon::now()->addDay()->endOfDay(),
            ]))
            // Exclude 1 hour from 12:00 to 13:00
            ->has(ScheduleExclusion::factory()->state([
                'starts_at' => Carbon::now()->setTimeFromTimeString('12:00'),
                'ends_at'   => Carbon::now()->setTimeFromTimeString('13:00'),
            ]))
            ->create();

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $availability = (new ScheduleAvailability($employee, $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->addDay()->endOfDay()
            );

        expect($availability->current())
            ->toBeInstanceOf(Period::class)
            ->startsAt(now()->setTimeFromTimeString('09:00'))
            ->toBeTrue()
            ->endsAt(now()->setTimeFromTimeString('11:59'))
            ->toBeTrue();

        $availability->next();

        expect($availability->current())
            ->toBeInstanceOf(Period::class)
            ->startsAt(now()->setTimeFromTimeString('13:00'))
            ->toBeTrue()
            ->endsAt(now()->setTimeFromTimeString('16:30'))
            ->toBeTrue();

        $availability->next();

        expect($availability->valid())
            ->toBeFalse();
    });

    it('returns upcoming availability only, skipping past time', function () {
        Carbon::setTestNow(Carbon::parse('1st April 2026 09:15'));

        $employee = Employee::factory()
            ->has(Schedule::factory()->state([
                'starts_date' => Carbon::now()->startOfDay(),
                'ends_date'   => Carbon::now()->addYear()->endOfDay(),
            ]))
            ->create();

        $service = Service::factory()->create([
            'duration' => 30,
        ]);

        $availability = (new ScheduleAvailability($employee, $service))
            ->forPeriod(
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            );

        expect($availability->current())
            ->toBeInstanceOf(Period::class)
            ->startsAt(now()->setTimeFromTimeString('10:00'))
            ->toBeTrue();
    });
});
