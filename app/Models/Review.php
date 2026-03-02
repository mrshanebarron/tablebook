<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
        'photos' => 'array',
        'is_approved' => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->youtube_url) return null;
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $this->youtube_url, $matches);
        return isset($matches[1]) ? "https://www.youtube.com/embed/{$matches[1]}" : null;
    }
}
