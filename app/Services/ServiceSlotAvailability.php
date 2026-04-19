<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Service;
use Illuminate\Support\Collection;
use Spatie\Period\Period;

class ServiceSlotAvailability
{
    public function __construct(public Collection $employees, public Service $service) {}

    public function forPeriod($startAt, $endAt)
    {
        $range = new SlotRangeGenerator($startAt, $endAt)->generate($this->service->duration);

        $this->employees->each(function (Employee $employee) use ($startAt, $endAt, &$range) {
            $periods = (new ScheduleAvailability($employee, $this->service))
                ->forPeriod($startAt, $endAt);

            foreach ($periods as $period) {
                $this->availabileEmployeeForPeriod($employee, $period, $range);
            }
        });

        return $range;
    }

    protected function availabileEmployeeForPeriod(Employee $employee, Period $period, Collection $range)
    {
        $range->each(function (DateWrapper $date) use ($employee, $period) {
            $date->slots->each(function (Slot $slot) use ($employee, $period) {
                if ($period->contains($slot->time)) {
                    $slot->addEmployee($employee);
                }
            });
        });

        $range = $this->removeUnavailableSlots($range);

        return $range;
    }

    protected function removeUnavailableSlots(Collection $range)
    {
        $range->filter(function (DateWrapper $date) {
            $date->slots = $date->slots->filter(fn (Slot $slot) => $slot->hasEmployees());

            return true;
        });

        return $range;
    }
}
