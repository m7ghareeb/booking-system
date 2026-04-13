<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ScheduleFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Guarded(['id'])]
class Schedule extends Model
{
    /** @use HasFactory<ScheduleFactory> */
    use HasFactory;

    protected $casts = [
        'starts_date' => 'date',
        'ends_date'   => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getWorkingHoursForDate(Carbon $date)
    {
        $hours = array_filter([
            $this->{Str::lower($date->format('l')) . '_starts_at'},
            $this->{Str::lower($date->format('l')) . '_ends_at'},
        ]);

        return empty($hours) ? null : $hours;
    }
}
