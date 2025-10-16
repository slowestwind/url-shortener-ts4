<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShortLink extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'custom_alias',
        'target_url',
        'title',
        'description',
        'category',
        'utm_params',
        'click_count',
        'scheduled_at',
        'expires_at',
        'is_active',
        'qr_settings',
        'qr_path',
    ];

    protected $casts = [
        'utm_params' => 'json',
        'qr_settings' => 'json',
        'is_active' => 'boolean',
        'scheduled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clickLogs(): HasMany
    {
        return $this->hasMany(ClickLog::class);
    }

    public function getPublicUrl(): string
    {
        return route('links.redirect', ['slug' => $this->slug]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isScheduled(): bool
    {
        return $this->scheduled_at && $this->scheduled_at->isFuture();
    }
}
