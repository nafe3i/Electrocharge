@extends('layouts.app')

@section('content')

    <div class="flex gap-4 h-[calc(100vh-120px)]">

        {{-- Panneau gauche : filtres --}}
        <div class="w-72 flex-shrink-0 flex flex-col gap-4 overflow-y-auto">

            {{-- Titre --}}
            <div>
                <h1 class="text-lg font-medium text-gray-800">Carte des stations</h1>
                <p class="text-sm text-gray-500" id="stations-count">
                    Chargement...
                </p>
            </div>

            {{-- Filtres --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4 space-y-4">
                <h2 class="text-sm font-medium text-gray-700">Filtres</h2>

                {{-- Ville --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Ville</label>
                    <select id="filter-city" class="w-full border border-gray-200 rounded-lg px-3 py-2
                                           text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Toutes les villes ({{ $cities->count() }})</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Type connecteur --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Type de connecteur</label>
                    <select id="filter-type" class="w-full border border-gray-200 rounded-lg px-3 py-2
                                           text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Tous</option>
                        <option value="CCS">CCS</option>
                        <option value="Type2">Type 2</option>
                        <option value="CHAdeMO">CHAdeMO</option>
                        <option value="Tesla">Tesla</option>
                    </select>
                </div>

                {{-- Statut --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Disponibilité</label>
                    <select id="filter-status" class="w-full border border-gray-200 rounded-lg px-3 py-2
                                           text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Tous</option>
                        <option value="libre">🟢 Libre</option>
                        <option value="occupee">🟠 Occupée</option>
                        <option value="hors_service">🔴 Hors service</option>
                    </select>
                </div>

                {{-- Boutons --}}
                <div class="flex gap-2 pt-1">
                    <button onclick="applyFilters()" class="flex-1 bg-green-600 text-white py-2 rounded-lg text-sm
                                           hover:bg-green-700 transition font-medium">
                        Appliquer
                    </button>
                    <button onclick="resetFilters()" class="border border-gray-200 text-gray-600 px-3 py-2
                                           rounded-lg text-sm hover:bg-gray-50 transition">
                        Reset
                    </button>
                </div>
            </div>

            {{-- Légende --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <h2 class="text-sm font-medium text-gray-700 mb-3">Légende</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-gray-600">Libre</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                        <span class="text-gray-600">Occupée</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <span class="text-gray-600">Hors service</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                        <span class="text-gray-600">Statut inconnu</span>
                    </div>
                </div>
            </div>

            {{-- Bouton géolocalisation --}}
            <button onclick="locateUser()" class="w-full border border-green-200 text-green-700 bg-green-50
                                   py-2.5 rounded-xl text-sm font-medium hover:bg-green-100 transition">
                📍 Autour de moi
            </button>
        </div>

        {{-- Carte Leaflet --}}
        <div class="flex-1 relative">
            <div id="map" class="w-full h-full rounded-xl border border-gray-200"></div>

            {{-- Loader overlay --}}
            <div id="map-loader" class="absolute inset-0 bg-white bg-opacity-80 flex items-center
                                justify-center rounded-xl z-10">
                <div class="text-center">
                    <div class="w-8 h-8 border-2 border-green-600 border-t-transparent
                                        rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-sm text-gray-500">Chargement des stations...</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .station-popup {
            min-width: 200px;
            font-family: sans-serif;
        }

        .station-popup h3 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 4px;
        }

        .station-popup .sub {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .station-popup .info-row {
            font-size: 12px;
            margin: 3px 0;
        }

        .station-popup .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .status-libre {
            background: #dcfce7;
            color: #166534;
        }

        .status-occupee {
            background: #fff7ed;
            color: #9a3412;
        }

        .status-hors_service {
            background: #fee2e2;
            color: #991b1b;
        }

        .station-popup .detail-link {
            display: block;
            margin-top: 10px;
            text-align: center;
            background: #16a34a;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            text-decoration: none;
        }

        .station-popup .detail-link:hover {
            background: #15803d;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([31.7917, -7.0926], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const statusColors = {
            libre: '#22c55e',
            occupee: '#f97316',
            hors_service: '#ef4444',
            inconnu: '#9ca3af',
        };

        const statusLabels = {
            libre: 'Libre',
            occupee: 'Occupée',
            hors_service: 'Hors service',
            inconnu: 'Inconnu',
        };

        let markersLayer = L.layerGroup().addTo(map);

        function loadStations(params = {}) {
            document.getElementById('map-loader').style.display = 'flex';

            const url = new URL('/api/stations', window.location.origin);
            Object.entries(params).forEach(([key, val]) => {
                if (val) url.searchParams.append(key, val);
            });

            fetch(url, { headers: { accept: "application/json" } })
                .then(res => res.json())
                .then(stations => {
                    console.log(stations)
                    markersLayer.clearLayers();

                    document.getElementById('stations-count').textContent =
                        stations.total + ' station(s) trouvée(s)';

                    stations.data.forEach(station => {
                        const color = statusColors[station.status] ?? statusColors.inconnu;
                        const label = statusLabels[station.status] ?? 'Inconnu';
                        const types = Array.isArray(station.types) ? station.types.join(', ') : '—';
                        const price = station.min_price ? station.min_price + ' MAD/kWh' : 'Non renseigné';
                        const power = station.max_power_kw ? station.max_power_kw + ' kW' : '—';
                        const rating = station.average_rating > 0
                            ? '⭐ ' + station.average_rating + '/5'
                            : 'Pas encore noté';

                        const marker = L.circleMarker(
                            [station.latitude, station.longitude],
                            {
                                radius: 10,
                                fillColor: color,
                                color: '#ffffff',
                                weight: 2.5,
                                opacity: 1,
                                fillOpacity: 0.9,
                            }
                        );

                        marker.bindPopup(`
                                    <div class="station-popup">
                                        <h3>${station.name}</h3>
                                        <div class="sub">${station.city ?? ''} · ${station.operator_name ?? 'Opérateur inconnu'}</div>
                                        <div class="info-row">
                                            <span class="status-badge status-${station.status}">${label}</span>
                                        </div>
                                        <div class="info-row">🔌 ${types}</div>
                                        <div class="info-row">⚡ Puissance max : ${power}</div>
                                        <div class="info-row">💰 Prix : ${price}</div>
                                        <div class="info-row">${rating}</div>
                                        <a href="/stations/${station.id}" class="detail-link">
                                            Voir le détail →
                                        </a>
                                    </div>
                                `, { maxWidth: 260 });

                        markersLayer.addLayer(marker);
                    });

                    document.getElementById('map-loader').style.display = 'none';
                })
                .catch(err => {
                    console.error('Erreur:', err);
                    document.getElementById('map-loader').style.display = 'none';
                    document.getElementById('stations-count').textContent =
                        'Erreur de chargement';
                });
        }

        function applyFilters() {
            console.log('applyFilters clicked');
            loadStations({
                city: document.getElementById('filter-city').value,
                type: document.getElementById('filter-type').value,
                status: document.getElementById('filter-status').value,
            });
        }

        function resetFilters() {
            document.getElementById('filter-city').value = '';
            document.getElementById('filter-type').value = '';
            document.getElementById('filter-status').value = '';
            loadStations();
        }

        function locateUser() {
            if (!navigator.geolocation) {
                alert('Géolocalisation non supportée par votre navigateur.');
                return;
            }
            navigator.geolocation.getCurrentPosition(pos => {
                const { latitude, longitude } = pos.coords;
                map.setView([latitude, longitude], 13);
                L.circleMarker([latitude, longitude], {
                    radius: 8, fillColor: '#3b82f6',
                    color: '#fff', weight: 2, fillOpacity: 1
                }).addTo(map).bindPopup('Vous êtes ici').openPopup();
                loadStations({ lat: latitude, lng: longitude });
            }, () => { alert('Impossible de vous localiser.') });
        }

        loadStations();
    </script>
@endpush