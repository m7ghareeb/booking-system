<?php

namespace App\Services;

use Carbon\Carbon;

class Slot
{
    public $employees = [];

    public function __construct(public Carbon $time)
    {
        //
    }
}
