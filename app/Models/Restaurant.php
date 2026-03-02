<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    protected $guarded = [];

    protected $casts = [
        'opening_time' => 'string',
        'closing_time' => 'string',
        'is_active' => 'boolean',
    ];

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }
}
