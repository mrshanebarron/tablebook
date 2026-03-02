<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'value' => 'decimal:2',
        'min_party_size' => 'integer',
        'max_uses' => 'integer',
        'times_used' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isValid(int $partySize = 1): bool
    {
        if (!$this->is_active) return false;
        if ($this->valid_from->isFuture()) return false;
        if ($this->valid_until->isPast()) return false;
        if ($this->max_uses && $this->times_used >= $this->max_uses) return false;
        if ($partySize < $this->min_party_size) return false;
        return true;
    }

    public function calculateDiscount(float $baseAmount): float
    {
        return match ($this->type) {
            'percentage' => round($baseAmount * ($this->value / 100), 2),
            'fixed' => min($this->value, $baseAmount),
            default => 0,
        };
    }
}
