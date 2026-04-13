<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    protected $fillable = [
        'user_id',
        'station_id',
        'type',
        'threshold_price',
        'radius_km',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'threshold_price' => 'float',
        'radius_km' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}