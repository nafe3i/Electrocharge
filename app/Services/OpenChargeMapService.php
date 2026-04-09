<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Station;
use App\Models\Connector;
use App\Models\ConnectorStatus;

class OpenChargeMapService
{
    public function fetchStations()
    {
        $response = Http::get('https://api.openchargemap.io/v3/poi', [
            'output' => 'json',
            'countrycode' => 'MA',
            'maxresults' => 50,
            'key' => env('OPENCHARGE_API_KEY')
        ]);

        //Vérification API
        if (!$response->successful()) {
            throw new \Exception('API OpenChargeMap failed');
        }

        $stations = $response->json();

        foreach ($stations as $item) {

            //Station
            $station = Station::updateOrCreate(
                ['ocm_id' => $item['ID']],
                [
                    'name' => data_get($item, 'AddressInfo.Title', 'Unknown'),
                    'address' => data_get($item, 'AddressInfo.AddressLine1'),
                    'city' => data_get($item, 'AddressInfo.Town', 'Unknown'),
                    'latitude' => data_get($item, 'AddressInfo.Latitude'),
                    'longitude' => data_get($item, 'AddressInfo.Longitude'),
                    'operator_name' => data_get($item, 'OperatorInfo.Title'),
                ]
            );

            // Connectors
            foreach (data_get($item, 'Connections', []) as $conn) {

                $type = $this->mapConnectorType(
                    data_get($conn, 'ConnectionType.Title', '')
                );

                $connector = Connector::updateOrCreate(
                    [
                        'station_id' => $station->id,
                        'type' => $type
                    ],
                    [
                        'power_kw' => data_get($conn, 'PowerKW', 0),
                        'quantity' => data_get($conn, 'Quantity', 1),
                    ]
                );

                // Status
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

        // Log
        \Log::info('Stations synced: ' . count($stations));
    }

    private function mapConnectorType($type)
    {
        if (str_contains($type, 'CCS')) return 'CCS';
        if (str_contains($type, 'Type 2')) return 'Type2';
        if (str_contains($type, 'CHAdeMO')) return 'CHAdeMO';
        if (str_contains($type, 'Tesla')) return 'Tesla';

        return 'Type2';
    }

    private function mapStatus($status)
    {
        if ($status === 'Operational') return 'libre';
        return 'hors_service';
    }
}