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
        'operator_id'
    ];
    //station a plusieurs connecteurs
    public function connectors()
    {
        return $this->hasMany(Connector::class);
    }
    // Une station a plusieurs favoris
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Une station a plusieurs avis
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    // Une station a plusieurs alertes
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    // Calcul de la note moyenne dynamiquement
    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // Connecteurs disponibles (status = libre)
    public function availableConnectors()
    {
        return $this->connectors()->whereHas('status', function ($q) {
            $q->where('status', 'libre');
        });
    }
    //Operators Id for assighining station 
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');

    }
}