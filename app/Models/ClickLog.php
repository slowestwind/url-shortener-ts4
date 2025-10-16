<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClickLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'short_link_id',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'city',
        'device_type',
        'browser_name',
        'os',
        'latitude',
        'longitude',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function shortLink(): BelongsTo
    {
        return $this->belongsTo(ShortLink::class);
    }
}
