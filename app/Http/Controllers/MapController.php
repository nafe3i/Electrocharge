<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\View\View;

class MapController extends Controller
{
    /**
     * GET /map
     * Affiche la page avec la carte Leaflet
     * Les stations arrivent via AJAX depuis Api/StationController
     * On passe seulement les données des filtres à Blade
     */
    public function index(): View
    {
        $cities = Station::where('is_active', true)
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $totalStations = Station::where('is_active', true)->count();

        return view('map.index', compact('cities', 'totalStations'));
    }
}