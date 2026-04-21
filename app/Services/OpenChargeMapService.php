<?php

namespace App\Services;

use App\Models\Connector;
use App\Models\ConnectorStatus;
use App\Models\Station;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenChargeMapService
{
    // Injecter OperatorService
    public function __construct(
        protected OperatorService $operatorService
    ) {
    }

    public function fetchStations(): void
    {
        $response = Http::get('https://api.openchargemap.io/v3/poi', [
            'output' => 'json',
            'countrycode' => 'MA',
            'maxresults' => 50,
            'key' => env('OPENCHARGE_API_KEY'),
        ]);

        if (!$response->successful()) {
            throw new \Exception('API OpenChargeMap failed: ' . $response->status());
        }

        $stations = $response->json();

        foreach ($stations as $item) {
            try {
                $this->processStation($item);
            } catch (\Exception $e) {
                Log::error('Station import error ID=' . $item['ID'] . ': ' . $e->getMessage());
                continue;
            }
        }

        Log::info('Stations synced: ' . count($stations));
    }

    protected function processStation(array $item): void
    {
        $operatorName = data_get($item, 'OperatorInfo.Title');

        // Créer ou récupérer le compte opérateur
        // null si pas de nom d'opérateur dans OCM
        $operatorUser = $operatorName
            ? $this->operatorService->firstOrCreateFromName($operatorName)
            : null;

        $station = Station::updateOrCreate(
            ['ocm_id' => $item['ID']],
            [
                'name' => data_get($item, 'AddressInfo.Title', 'Unknown'),
                'address' => data_get($item, 'AddressInfo.AddressLine1'),
                'city' => data_get($item, 'AddressInfo.Town', 'Unknown'),
                'latitude' => data_get($item, 'AddressInfo.Latitude'),
                'longitude' => data_get($item, 'AddressInfo.Longitude'),
                'operator_name' => $operatorName,
                'operator_id' => $operatorUser?->id, // ← lien compte opérateur
                'photo_url' => data_get($item, 'MediaItems.0.ItemURL'),
            ]
        );

        foreach (data_get($item, 'Connections', []) as $conn) {
            $type = $this->mapConnectorType(
                data_get($conn, 'ConnectionType.Title', '')
            );

            if (!$type)
                continue;

            $connector = Connector::updateOrCreate(
                ['station_id' => $station->id, 'type' => $type],
                [
                    'power_kw' => data_get($conn, 'PowerKW', 0),
                    'quantity' => data_get($conn, 'Quantity', 1),
                ]
            );

            ConnectorStatus::updateOrCreate(
                ['connector_id' => $connector->id],
                [
                    'status' => $this->mapStatus(
                        data_get($conn, 'StatusType.Title', '')
                    ),
                    'updated_by' => null,
                    'last_updated_at' => now(),
                ]
            );
        }
    }

    private function mapConnectorType(string $type): ?string
    {
        if (str_contains($type, 'CCS'))
            return 'CCS';
        if (str_contains($type, 'Type 2'))
            return 'Type2';
        if (str_contains($type, 'CHAdeMO'))
            return 'CHAdeMO';
        if (str_contains($type, 'Tesla'))
            return 'Tesla';
        return null;
    }

    private function mapStatus(string $status): string
    {
        return $status === 'Operational' ? 'libre' : 'hors_service';
    }
}