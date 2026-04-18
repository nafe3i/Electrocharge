<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\AdminLog;
use App\Services\StationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class StationController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        protected StationService $stationService
    ) {
    }

    /**
     * GET /stations/{station}
     * Page détail d'une station — accessible à tous
     */
    public function show(Station $station): View
    {
        // $this->authorize('view', $station);

        $station = $this->stationService->getStationDetail($station);

        $isFavorite = auth()->check()
            ? auth()->user()->favorites()
                ->where('station_id', $station->id)
                ->exists()
            : false;

        if (auth()->check()) {
            $this->stationService->saveToHistory(auth()->id(), $station->name);
        }

        return view('stations.show', compact('station', 'isFavorite'));
    }

    /**
     * GET /admin/stations
     * Liste admin avec pagination et filtres
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Station::class);

        $stations = $this->stationService->getStationsForAdmin($request);

        $cities = Station::whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');
        // dd($cities);^

        // if ($request->query("getJsonOnly") == true) {
        //     return response()->json($stations);
        // }
        return view('admin.index', compact('stations', 'cities'));
    }

    /**
     * GET /admin/stations/create
     */
    public function create(): View
    {
        $this->authorize('create', Station::class);
        return view('stations.create');
    }

    /**
     * POST /admin/stations
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Station::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'operator_name' => ['nullable', 'string', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:500'],
            'photo_url' => ['nullable', 'url', 'max:500'],
        ]);

        $station = $this->stationService->createStation($validated, auth()->id());

        return redirect()
            ->route('admin.stations.index')
            ->with('success', "Station « {$station->name} » créée.");
    }

    /**
     * GET /admin/stations/{station}/edit
     */
    public function edit(Station $station): View
    {
        $this->authorize('update', $station);
        return view('stations.edit', compact('station'));
    }

    /**
     * PUT /admin/stations/{station}
     */
    public function update(Request $request, Station $station): RedirectResponse
    {
        $this->authorize('update', $station);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['sometimes', 'required', 'string', 'max:100'],
            'latitude' => ['sometimes', 'required', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'required', 'numeric', 'between:-180,180'],
            'operator_name' => ['nullable', 'string', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:500'],
            'photo_url' => ['nullable', 'url', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $station = $this->stationService->updateStation($station, $validated, auth()->id());

        return redirect()
            ->route('admin.stations.index')
            ->with('success', "Station « {$station->name} » mise à jour.");
    }

    /**
     * DELETE /admin/stations/{station}
     */
    public function destroy(Station $station): RedirectResponse
    {
        $this->authorize('delete', $station);

        $station = $this->stationService->toggleStationStatus($station, auth()->id());
        $action = $station->is_active ? 'activée' : 'désactivée';
        return redirect()
            ->route('admin.stations.index')
            ->with('success', "Station « {$station->name} » {$action}.");
    }
    // public function toggle(Station $station)
    // {
    //     $this->authorize('update', $station);

    //     $station = $this->stationService->toggleStationStatus($station, auth()->id());
    //     return redirect()
    //         ->route('admin.stations.index')
    //         ->with('success', "Station « {$station->name} » " . ($station->is_active ? 'activée' : 'désactivée') . ".");
    // }
}