<?php

namespace App\Models;

use Database\Factories\ScheduleExclusionFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Guarded(['id'])]
class ScheduleExclusion extends Model
{
    /** @use HasFactory<ScheduleExclusionFactory> */
    use HasFactory;

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];
}
