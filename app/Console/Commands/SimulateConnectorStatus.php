<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\ConnectorStatus;
use App\Models\Station;
use App\Notifications\StationStatusChanged;
use Illuminate\Console\Command;

class SimulateConnectorStatus extends Command
{
    protected $signature   = 'connector:simulate';
    protected $description = 'Simuler les changements de statut des connecteurs';

    public function handle(): int
    {
        $this->info('Démarrage de la simulation des statuts...');

        try {
            $statuses = array_merge(
                array_fill(0, 6, 'libre'),
                array_fill(0, 3, 'occupee'),
                array_fill(0, 1, 'hors_service')
            );

            // Récupérer les anciens statuts avant de changer
            $anciensStatuts = ConnectorStatus::all()
                ->keyBy('connector_id')
                ->map(fn($s) => $s->status);

            // Mettre à jour les statuts
            $count = ConnectorStatus::all()->each(function ($status) use ($statuses) {
                $status->update([
                    'status'          => $statuses[array_rand($statuses)],
                    'last_updated_at' => now(),
                ]);
            })->count();

            // Vérifier les alertes déclenchées
            $this->checkAlerts($anciensStatuts);

            $this->info("✅ {$count} statuts mis à jour.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Erreur : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Vérifier si des alertes doivent être déclenchées
     * On notifie uniquement quand une borne passe
     * de occupee/hors_service → libre
     */
    private function checkAlerts(array|\Illuminate\Support\Collection $anciensStatuts): void
    {
        // Récupérer toutes les alertes de disponibilité actives
        $alertes = Alert::where('type', 'disponibilite')
            ->where('is_active', true)
            ->with(['user', 'station.connectors.status'])
            ->get();

        foreach ($alertes as $alerte) {
            if (!$alerte->station) continue;

            // Vérifier si la station a au moins un connecteur libre maintenant
            $aUnLibre = $alerte->station->connectors->some(function ($connector) {
                return $connector->status?->status === 'libre';
            });

            if ($aUnLibre) {
                // Envoyer la notification à l'utilisateur
                try {
                    $alerte->user->notify(
                        new StationStatusChanged($alerte->station)
                    );
                } catch (\Exception $e) {
                    \Log::warning('Notification failed: ' . $e->getMessage());
                }
            }
        }
    }
}