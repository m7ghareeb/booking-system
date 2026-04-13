<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Spatie\Period\Period;
use Spatie\Period\PeriodCollection;
use Spatie\Period\Precision;

class ScheduleAvailabilityService
{
    protected PeriodCollection $periods;

    public function __construct(protected Employee $employee, protected Service $service)
    {
        $this->periods = new PeriodCollection;
    }

    public function forPeriod(Carbon $startsAt, Carbon $endsAt)
    {
        collect(CarbonPeriod::create($startsAt, $endsAt)->days())
            ->each(function ($date) {
                $this->addAvailabilityFromSchedule($date);
            });

        foreach ($this->periods as $period) {
            dump($period->asString());
        }
    }

    protected function addAvailabilityFromSchedule(Carbon $date)
    {
        $schedule = $this->employee->schedules
            ->where('starts_date', '<=', $date)
            ->where('ends_date', '>=', $date)
            ->first();

        if (!$schedule) {
            return;
        }

        if (![$startsAt, $endsAt] = $schedule->getWorkingHoursForDate($date)) {
            return;
        }

        $this->periods = $this->periods->add(
            Period::make(
                $date->copy()->setTimeFromTimeString($startsAt),
                $date->copy()->setTimeFromTimeString($endsAt)->subMinutes($this->service->duration),
                Precision::MINUTE()
            )
        );
    }
}
