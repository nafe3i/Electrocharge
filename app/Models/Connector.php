<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connector extends Model
{
    protected $fillable=['station_id','type','power_kw','price_per_kwl','quantity'];
}
