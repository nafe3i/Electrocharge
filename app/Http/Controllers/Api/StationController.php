<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StationService;
// use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function __construct(
        protected StationService $stationService
    ) {
    }

    /**
     * GET /api/stations
     * Retourne les stations en JSON pour Leaflet.js
     * C'est le SEUL endpoint JSON du projet
     */
    public function index(Request $request)
    {
        $stations = $this->stationService
            ->getStationsForMap($request)
            ->map(fn($station) => $this->stationService->formatForMap($station));
        // dd($stations);
        return response()->json([
            'data' => $stations,
            'total' => $stations->count()
        ]);
    }
}