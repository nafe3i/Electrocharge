<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'query',
        'latitude',
        'longitude',
        'filters',
    ];

    protected $casts = [
        'filters' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}