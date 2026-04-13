<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connector extends Model
{
    protected $fillable = [
        'station_id',
        'type',
        'power_kw',
        'price_per_kwh',
        'quantity',
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function status()
    {
        return $this->hasOne(ConnectorStatus::class);
    }
    // Historique des prix
    public function priceHistory()
    {
        return $this->hasMany(PriceHistory::class);
    }
}