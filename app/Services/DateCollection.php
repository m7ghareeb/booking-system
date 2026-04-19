<?php

namespace App\Services;

use Illuminate\Support\Collection;

class DateCollection extends Collection
{
    public function firstAvailableDate()
    {
        return $this->first(fn (DateWrapper $date) => $date->slots->count() >= 1);
    }
}
