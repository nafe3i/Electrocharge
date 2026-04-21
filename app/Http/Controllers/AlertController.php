<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Station;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlertController extends Controller
{

    /**
     * GET /alerts
     * Liste des alertes de l'utilisateur connecté
     */
    public function index(): View
    {
        // Permission Spatie — rôle user a 'manage-alerts'
        abort_if(
            !auth()->user()->can('manage-alerts'),
            403
        );

        $alerts = auth()->user()
            ->alerts()
            ->with('station')
            ->latest()
            ->get();
        $stations = Station::where('is_active', true)->orderBy('name')->get();

        return view('user.alerts', compact('alerts', 'stations'));
    }

    /**
     * POST /alerts
     * Créer une nouvelle alerte
     */
    public function store(Request $request): RedirectResponse
    {
        abort_if(
            !auth()->user()->can('manage-alerts'),
            403
        );

        $validated = $request->validate([
            'type' => ['required', 'in:disponibilite,prix'],
            'station_id' => ['nullable', 'integer', 'exists:stations,id'],
            'threshold_price' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'radius_km' => ['nullable', 'numeric', 'min:1', 'max:100'],
        ]);

        // Si type = prix, le seuil est obligatoire
        if ($validated['type'] === 'prix' && empty($validated['threshold_price'])) {
            return back()
                ->withErrors(['threshold_price' => 'Le seuil de prix est obligatoire pour une alerte prix.'])
                ->withInput();
        }

        Alert::create([
            'user_id' => auth()->id(),
            'station_id' => $validated['station_id'] ?? null,
            'type' => $validated['type'],
            'threshold_price' => $validated['threshold_price'] ?? null,
            'radius_km' => $validated['radius_km'] ?? null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('alerts.index')
            ->with('success', 'Alerte créée avec succès.');
    }

    /**
     * PUT /alerts/{alert}
     * Activer ou désactiver une alerte (toggle)
     */
    public function toggle(Alert $alert): RedirectResponse
    {
        abort_if(
            !auth()->user()->can('manage-alerts'),
            403
        );

        // Vérifier que l'alerte appartient à l'utilisateur connecté
        abort_if($alert->user_id !== auth()->id(), 403);

        $alert->update(['is_active' => !$alert->is_active]);

        $etat = $alert->is_active ? 'activée' : 'désactivée';

        return back()->with('success', "Alerte {$etat}.");
    }

    /**
     * DELETE /alerts/{alert}
     * Supprimer une alerte
     */
    public function destroy(Alert $alert): RedirectResponse
    {
        abort_if(
            !auth()->user()->can('manage-alerts'),
            403
        );

        // Seul le propriétaire peut supprimer son alerte
        abort_if($alert->user_id !== auth()->id(), 403);

        $alert->delete();

        return back()->with('success', 'Alerte supprimée.');
    }
}