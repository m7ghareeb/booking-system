<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class DateWrapper
{
    public Collection $slots;

    public function __construct(public Carbon $date)
    {
        $this->slots = collect();
    }

    public function addSlot(Slot $slot)
    {
        $this->slots->push($slot);
    }
}
