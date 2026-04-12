<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OpenChargeMapService;

class SyncStations extends Command
{
    protected $signature = 'stations:sync';
    protected $description = 'Synchronize stations from OpenChargeMap API';

    public function handle()
    {
        $this->info(' Start syncing stations...');

        try {
            app(OpenChargeMapService::class)->fetchStations();

            $this->info(' Stations synced successfully!');
        } catch (\Exception $e) {
            $this->error(' Error: ' . $e->getMessage());
        }
    }
}