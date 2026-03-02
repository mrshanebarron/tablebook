<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'party_size' => 'integer',
        'discount_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->reference)) {
                $booking->reference = 'TB-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'cancelled' => 'danger',
            'completed' => 'info',
            default => 'gray',
        };
    }
}
