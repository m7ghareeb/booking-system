<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Period\Boundaries;
use Spatie\Period\Period;
use Spatie\Period\PeriodCollection;
use Spatie\Period\Precision;

class ServiceSlotAvailability
{
    public function __construct(public Collection $employees, public Service $service) {}

    public function forPeriod(Carbon $startsAt, Carbon $endsAt)
    {
        /**
         * @var Collection<DateWrapper> $range
         */
        $range = new SlotRangeGenerator($startsAt, $endsAt)->generate($this->service->duration);

        $this->employees->each(function (Employee $employee) use ($startsAt, $endsAt, &$range) {
            $periods = (new ScheduleAvailability($employee, $this->service))
                ->forPeriod($startsAt, $endsAt);

            $periods = $this->removeAppointments($periods, $employee);

            foreach ($periods as $period) {
                $this->addAvailableEmployeeForPeriod($employee, $period, $range);
            }
        });

        $range = $this->removeEmptySlots($range);

        return $range;
    }

    protected function addAvailableEmployeeForPeriod(Employee $employee, Period $period, Collection $range)
    {
        $range->each(function (DateWrapper $date) use ($employee, $period) {
            $date->slots->each(function (Slot $slot) use ($employee, $period) {
                if ($period->contains($slot->time)) {
                    $slot->addEmployee($employee);
                }
            });
        });

        return $range;
    }

    protected function removeEmptySlots(Collection $range)
    {
        return $range->filter(function (DateWrapper $date) {
            $date->slots = $date->slots->filter(fn (Slot $slot) => $slot->hasEmployees());

            return true;
        });
    }

    protected function removeAppointments(PeriodCollection $periods, Employee $employee)
    {
        $employee->appointments->whereNull('cancelled_at')
            ->each(function (Appointment $appointment) use (&$periods) {
                $periods = $periods->subtract(
                    Period::make(
                        $appointment->starts_at->subMinutes($this->service->duration)->addMinute(),
                        $appointment->ends_at,
                        Precision::MINUTE(),
                        Boundaries::EXCLUDE_ALL()
                    )
                );
            });

        return $periods;
    }
}
