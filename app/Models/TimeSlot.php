<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TimeSlot extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    public function getFormattedTimeAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' – ' . substr($this->end_time, 0, 5);
    }
}
