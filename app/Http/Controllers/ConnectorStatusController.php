<?php

namespace App\Http\Controllers;

use App\Models\Connector;
use App\Models\ConnectorStatus;
use App\Notifications\StationStatusChanged;
use Illuminate\Http\Request;

class ConnectorStatusController extends Controller
{
    public function update(Request $request, Connector $connector)
    {
        abort_if(!auth()->user()->can('update-connector-status'), 403);
        abort_if($connector->station->operator_id !== auth()->id(), 403);

        $request->validate([
            'status' => 'required|in:libre,occupee,hors_service'
        ]);

        ConnectorStatus::updateOrCreate(
            ['connector_id' => $connector->id],
            [
                'status' => $request->status,
                'updated_by' => auth()->id(),
                'last_updated_at' => now(),
            ]
        );

        // Notifier si statut devient libre
        if ($request->status === 'libre') {
            $station = $connector->station;
            $station->alerts()
                ->where('is_active', true)
                ->where('type', 'disponibilite')
                ->with('user')
                ->get()
                ->each(
                    fn($alert) =>
                    $alert->user->notify(new StationStatusChanged($station))
                );
        }

        return back()->with('success', 'Statut mis à jour.');
    }
}
