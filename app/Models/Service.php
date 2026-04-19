<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Guarded(['id'])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => '$ ' . number_format($value / 100, 2),
        );
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }
}
