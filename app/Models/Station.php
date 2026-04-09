<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'ocm_id',
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
        'operator_name',
        'opening_hours',
        'photo_url',
        'is_active',
    ];
     public function connectors()
    {
        return $this->hasMany(Connector::class);
    }
}