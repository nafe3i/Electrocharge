<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\Connector;
use App\Models\ConnectorStatus;
use App\Models\User;
use App\Models\Review;
use App\Models\Favorite;

class StatsController extends Controller
{
    public function index()
    {
        // Statuts des connecteurs
        $statuts = [
            'libre'        => ConnectorStatus::where('status', 'libre')->count(),
            'occupee'      => ConnectorStatus::where('status', 'occupee')->count(),
            'hors_service' => ConnectorStatus::where('status', 'hors_service')->count(),
        ];

        // Stations par ville
        $parVille = Station::where('is_active', true)
            ->selectRaw('city, count(*) as total')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Types de connecteurs
        $parType = Connector::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        // Stats globales
        $stats = [
            'total_stations'  => Station::count(),
            'stations_active' => Station::where('is_active', true)->count(),
            'total_users'     => User::role('user')->count(),
            'total_reviews'   => Review::count(),
            'total_favorites' => Favorite::count(),
            'total_operators' => User::role('operator')->count(),
        ];

        return view('admin.stats', compact('statuts', 'parVille', 'parType', 'stats'));
    }
}
