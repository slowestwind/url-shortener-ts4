<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'avatar_url',
        'avatar_path',
        'profile_slug',
        'display_name',
        'website_url',
        'social_links',
        'theme_settings',
        'show_analytics',
        'profile_views',
    ];

    protected $casts = [
        'social_links' => 'json',
        'theme_settings' => 'json',
        'show_analytics' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function links(): HasMany
    {
        return $this->user->shortLinks();
    }
}
