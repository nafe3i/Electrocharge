<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'connector_id',
        'price_per_kwh',
        'recorded_at',
    ];

    protected $casts = [
        'price_per_kwh' => 'float',
        'recorded_at' => 'datetime',
    ];

    public function connector(): BelongsTo
    {
        return $this->belongsTo(Connector::class);
    }
}