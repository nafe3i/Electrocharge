<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectorStatus extends Model
{
    protected $fillable = [
        'connector_id',
        'status',
        'updated_by',
        'last_updated_at',
    ];

    public $timestamps = false; // car on utilise last_updated_at

    public function connector()
    {
        return $this->belongsTo(Connector::class);
    }
}