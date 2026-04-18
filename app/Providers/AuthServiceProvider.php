<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Station;
use App\Policies\StationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Station::class => StationPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}