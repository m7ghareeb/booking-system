<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SlotRangeGenerator
{
    public function __construct(protected Carbon $startAt, protected Carbon $endAt) {}

    public function generate(int $intervalInMinutes)
    {
        $collection = collect();
        // Generate days between start and end date
        $days = CarbonPeriod::create($this->startAt, '1 day', $this->endAt);
        // For each day generate slots between start and end date
        foreach ($days as $day) {
            // Generate slots for the day
            $date = new Date($day);
            // Generate times between start and end date with the given interval
            $times = CarbonPeriod::create($day->startOfDay(), $intervalInMinutes . ' minutes', $day->copy()->endOfDay());
            // For each time generate a slot and add it to the date
            foreach ($times as $time) {
                // Generate a slot for the time
                $date->addSlot(new Slot($time));
            }

            $collection->push($date);
        }

        return $collection;
    }
}
