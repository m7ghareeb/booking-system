<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SlotRangeGenerator
{
    public function __construct(protected Carbon $startAt, protected Carbon $endAt) {}

    /**
     * Generate a collection of DateWrappers, each containing a range of timeslots
     * spaced by the given interval in minutes, covering the period between
     * $this->startAt and $this->endAt.
     *
     * @param  int  $intervalInMinutes  The interval in minutes between each timeslot
     * @return Collection<DateWrapper>
     */
    public function generate(int $intervalInMinutes)
    {
        $collection = collect();

        $days = CarbonPeriod::create($this->startAt, '1 day', $this->endAt);

        foreach ($days as $day) {
            $date = new DateWrapper($day);
            $times = CarbonPeriod::create($day->copy()->startOfDay(), $intervalInMinutes . ' minutes', $day->copy()->endOfDay());

            foreach ($times as $time) {
                $date->addSlot(new Slot($time));
            }

            $collection->push($date);
        }

        return $collection;
    }
}
