<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Station;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = auth()->user()
            ->alerts()
            ->with('station')
            ->latest()
            ->get();

        return view('user.alerts', compact('alerts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'            => 'required|in:disponibilite,prix',
            'station_id'      => 'nullable|exists:stations,id',
            'threshold_price' => 'nullable|numeric|min:0',
            'radius_km'       => 'nullable|numeric|min:0',
        ]);

        auth()->user()->alerts()->create($validated);

        return back()->with('success', 'Alerte créée avec succès.');
    }

    public function toggle(Alert $alert)
    {
        abort_if($alert->user_id !== auth()->id(), 403);

        $alert->update(['is_active' => !$alert->is_active]);

        return back()->with('success', 'Alerte mise à jour.');
    }

    public function destroy(Alert $alert)
    {
        abort_if($alert->user_id !== auth()->id(), 403);

        $alert->delete();

        return back()->with('success', 'Alerte supprimée.');
    }
}
