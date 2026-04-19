<?php

namespace App\Services;

use App\Models\Station;
use App\Models\AdminLog;
use App\Models\SearchHistory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Notification;

class StationService
{
    /**
     * Récupérer les stations pour Leaflet (JSON)
     * Appelée par Api/StationController
     * Retourne uniquement les champs utiles à la carte
     */
    public function getStationsForMap(Request $request)
    {
        $query = Station::with(['connectors.status'])
            ->where('is_active', true);

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('type')) {
            $query->whereHas(
                'connectors',
                fn($q) =>
                $q->where('type', $request->type)
            );
        }

        if ($request->filled('power_min')) {
            $query->whereHas(
                'connectors',
                fn($q) =>
                $q->where('power_kw', '>=', $request->power_min)
            );
        }

        if ($request->filled('status')) {
            $query->whereHas(
                'connectors.status',
                fn($q) =>
                $q->where('status', $request->status)
            );
        }

        return $query->get();
    }

    /**
     * Formater une station pour Leaflet
     * Calcule le statut global de la station
     * (le pire statut parmi tous les connecteurs)
     */
    public function formatForMap(Station $station)
    {
        $statuses = $station->connectors
            ->map(function ($connector) {
                return $connector->status?->status ?? 'inconnu';
            });

        $globalStatus = 'libre';
        if ($statuses->contains('hors_service')) {
            $globalStatus = 'hors_service';
        } elseif ($statuses->contains('occupee')) {
            $globalStatus = 'occupee';
        }

        return [
            'id' => $station->id,
            'name' => $station->name,
            'city' => $station->city,
            'latitude' => $station->latitude,
            'longitude' => $station->longitude,
            'operator_name' => $station->operator_name,
            'photo_url' => $station->photo_url,
            'status' => $globalStatus,
            'connectors_count' => $station->connectors->count(),
            'types' => $station->connectors->pluck('type')->unique()->values(),
            'max_power_kw' => $station->connectors->max('power_kw'),
            'min_price' => $station->connectors->whereNotNull('price_per_kwh')->min('price_per_kwh'),
            'detail_url' => route('stations.show', $station->id),
        ];
    }

    /**
     * Récupérer le détail complet d'une station
     * Appelée par StationController@show
     */
    public function getStationDetail(Station $station)
    {
        abort_if(!$station->is_active, 404);

        $station->load([
            'connectors.status',
            'reviews.user',
        ]);

        return $station;
    }

    /**
     * Liste paginée pour le dashboard admin
     */
    public function getStationsForAdmin(Request $request)
    {
        $query = Station::withCount(['connectors', 'reviews', 'favorites']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('operator_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->active === '1');
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $allowedSorts = ['name', 'city', 'created_at', 'connectors_count', 'reviews_count'];
        $sort = in_array($request->sort, $allowedSorts) ? $request->sort : 'created_at';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * Créer une station + logger l'action
     */
    public function createStation(array $data, int $adminId)
    {
        $station = Station::create($data);

        AdminLog::create([
            'admin_id' => $adminId,
            'action' => 'station.create',
            'target_type' => 'station',
            'target_id' => $station->id,
            'details' => ['name' => $station->name, 'city' => $station->city],
        ]);

        return $station;
    }

    /**
     * Mettre à jour une station + logger
     */
    public function updateStation(Station $station, array $data, int $adminId)
    {
        $before = $station->only(['name', 'city', 'address', 'is_active']);
        $station->update($data);

        AdminLog::create([
            'admin_id' => $adminId,
            'action' => 'station.update',
            'target_type' => 'station',
            'target_id' => $station->id,
            'details' => [
                'before' => $before,
                'after' => $station->only(['name', 'city', 'address', 'is_active']),
            ],
        ]);

        return $station;
    }

    /**
     * Désactiver une station (soft delete)
     * On ne supprime jamais — des utilisateurs ont peut-être
     * cette station en favoris ou dans leur historique
     */
    // public function deactivateStation(Station $station, int $adminId)
    // {
    //     $station->update(['is_active' => false]);

    //     AdminLog::create([
    //         'admin_id' => $adminId,
    //         'action' => 'station.deactivate',
    //         'target_type' => 'station',
    //         'target_id' => $station->id,
    //         'details' => ['name' => $station->name],
    //     ]);
    // }

    /**
     * Sauvegarder la recherche dans l'historique
     * Silencieux — ne bloque jamais la page si ça échoue
     */
    public function saveToHistory(int $userId, string $query): void
    {
        try {
            SearchHistory::create([
                'user_id' => $userId,
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            \Log::warning('SearchHistory failed: ' . $e->getMessage());
        }
    }
    public function toggleStationStatus(Station $station, $idAdmin)
    {
        $newState = !$station->is_active;
        $station->update(['is_active' => $newState]);
        AdminLog::create([
            'admin_id' => $idAdmin,
            'action' => $newState ? 'station.activate' : 'station.deactivate',
            'target_type' => 'station',
            'target_id' => $station->id,
            'details' => ['name' => $station->name],
        ]);
        // notifier les users qui ont alerte activer 
        if ($newState) {
            Notification::create([
                'title' => 'Station réactivée',
                'message' => "La station {$station->name} est à nouveau disponible.",
                'type' => 'info',
                'target_table' => 'stations',
                'target_id' => $station->id,
            ]);
        }
        return $station->fresh();
    }
}