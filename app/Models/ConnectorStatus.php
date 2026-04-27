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
    protected $casts = [
        'last_updated_at' => 'datetime',
    ];

    public $timestamps = false;

    public function connector()
    {
        return $this->belongsTo(Connector::class);
    }
    // Mis à jour par quel utilisateur
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}