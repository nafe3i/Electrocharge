@extends('layouts.app')

@section('content')

<div class="flex gap-4 h-[calc(100vh-72px)]">

    {{-- Panneau gauche --}}
    <div class="w-72 flex-shrink-0 flex flex-col gap-3 overflow-y-auto pr-0.5">

        <div>
            <h1 class="text-base font-semibold text-slate-800">Carte des stations</h1>
            <p class="text-sm text-slate-500 mt-0.5" id="stations-count">Chargement...</p>
        </div>

        {{-- Filtres --}}
        <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-3">
            <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Filtres</h2>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Ville</label>
                <select id="filter-city"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Toutes les villes ({{ $cities->count() }})</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Type de connecteur</label>
                <select id="filter-type"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tous les types</option>
                    <option value="CCS">CCS</option>
                    <option value="Type2">Type 2</option>
                    <option value="CHAdeMO">CHAdeMO</option>
                    <option value="Tesla">Tesla</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Disponibilité</label>
                <select id="filter-status"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tous les statuts</option>
                    <option value="libre">Libre</option>
                    <option value="occupee">Occupée</option>
                    <option value="hors_service">Hors service</option>
                </select>
            </div>

            <div class="flex gap-2 pt-1">
                <button onclick="applyFilters()"
                        class="flex-1 bg-green-600 text-white py-2 rounded-lg text-sm
                               font-medium hover:bg-green-700 transition">
                    Appliquer
                </button>
                <button onclick="resetFilters()"
                        class="border border-slate-200 text-slate-600 px-3 py-2
                               rounded-lg text-sm hover:bg-slate-50 transition">
                    Réinitialiser
                </button>
            </div>
        </div>

        {{-- Légende --}}
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Légende</h2>
            <div class="space-y-2">
                @foreach([
                    ['bg-green-500',  'Libre'],
                    ['bg-orange-500', 'Occupée'],
                    ['bg-red-500',    'Hors service'],
                    ['bg-slate-400',  'Statut inconnu'],
                ] as [$color, $label])
                    <div class="flex items-center gap-2.5 text-sm">
                        <div class="w-2.5 h-2.5 rounded-full {{ $color }} flex-shrink-0"></div>
                        <span class="text-slate-600">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Géolocalisation --}}
        <button onclick="locateUser()"
                class="w-full flex items-center justify-center gap-2 border border-green-200
                       text-green-700 bg-green-50 py-2.5 rounded-xl text-sm font-medium
                       hover:bg-green-100 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Autour de moi
        </button>
    </div>

    {{-- Carte --}}
    <div class="flex-1 relative">
        <div id="map" class="w-full h-full rounded-xl border border-slate-200"></div>

        {{-- Loader --}}
        <div id="map-loader"
             class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center
                    justify-center rounded-xl z-10">
            <div class="text-center">
                <div class="w-8 h-8 border-2 border-green-600 border-t-transparent
                            rounded-full animate-spin mx-auto mb-3"></div>
                <p class="text-sm text-slate-500">Chargement des stations...</p>
            </div>
        </div>

        {{-- Erreur géolocalisation --}}
        <div id="geo-error"
             class="hidden absolute top-4 left-1/2 -translate-x-1/2 z-20
                    bg-red-50 border border-red-200 text-red-700 text-sm
                    px-4 py-2.5 rounded-xl shadow-sm whitespace-nowrap">
        </div>
    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 0;
        }
        .leaflet-popup-content {
            margin: 0;
        }
        .station-popup {
            min-width: 210px;
            font-family: ui-sans-serif, system-ui, sans-serif;
            padding: 14px;
        }
        .station-popup h3 {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            margin: 0 0 3px;
        }
        .station-popup .sub {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .station-popup .info-row {
            font-size: 12px;
            color: #475569;
            margin: 4px 0;
        }
        .station-popup .info-label {
            color: #94a3b8;
            font-size: 11px;
            margin-right: 4px;
        }
        .station-popup .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .status-libre        { background: #dcfce7; color: #166534; }
        .status-occupee      { background: #fff7ed; color: #9a3412; }
        .status-hors_service { background: #fee2e2; color: #991b1b; }
        .status-inconnu      { background: #f1f5f9; color: #475569; }
        .station-popup .detail-link {
            display: block;
            margin-top: 10px;
            text-align: center;
            background: #16a34a;
            color: white;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
        }
        .station-popup .detail-link:hover { background: #15803d; }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([31.7917, -7.0926], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const statusColors = {
            libre:        '#22c55e',
            occupee:      '#f97316',
            hors_service: '#ef4444',
            inconnu:      '#94a3b8',
        };

        const statusLabels = {
            libre:        'Libre',
            occupee:      'Occupée',
            hors_service: 'Hors service',
            inconnu:      'Inconnu',
        };

        let markersLayer = L.layerGroup().addTo(map);

        function showGeoError(message) {
            const el = document.getElementById('geo-error');
            el.textContent = message;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 4000);
        }

        function loadStations(params = {}) {
            document.getElementById('map-loader').style.display = 'flex';

            const url = new URL('/api/stations', window.location.origin);
            Object.entries(params).forEach(([key, val]) => {
                if (val) url.searchParams.append(key, val);
            });

            fetch(url, { headers: { accept: 'application/json' } })
                .then(res => res.json())
                .then(response => {
                    markersLayer.clearLayers();

                    document.getElementById('stations-count').textContent =
                        response.total + ' station(s) trouvée(s)';

                    response.data.forEach(station => {
                        const color  = statusColors[station.status] ?? statusColors.inconnu;
                        const label  = statusLabels[station.status] ?? 'Inconnu';
                        const types  = Array.isArray(station.types) ? station.types.join(', ') : '—';
                        const price  = station.min_price ? station.min_price + ' MAD/kWh' : 'Non renseigné';
                        const power  = station.max_power_kw ? station.max_power_kw + ' kW' : '—';
                        const rating = station.average_rating > 0
                            ? station.average_rating + '/5'
                            : 'Pas encore noté';

                        const marker = L.circleMarker(
                            [station.latitude, station.longitude],
                            {
                                radius:      10,
                                fillColor:   color,
                                color:       '#ffffff',
                                weight:      2.5,
                                opacity:     1,
                                fillOpacity: 0.9,
                            }
                        );

                        marker.bindPopup(`
                            <div class="station-popup">
                                <h3>${station.name}</h3>
                                <div class="sub">${station.city ?? ''} &middot; ${station.operator_name ?? 'Opérateur inconnu'}</div>
                                <span class="status-badge status-${station.status}">${label}</span>
                                <div class="info-row"><span class="info-label">Connecteurs</span>${types}</div>
                                <div class="info-row"><span class="info-label">Puissance max</span>${power}</div>
                                <div class="info-row"><span class="info-label">Prix</span>${price}</div>
                                <div class="info-row"><span class="info-label">Note</span>${rating}</div>
                                <a href="/stations/${station.id}" class="detail-link">Voir le détail &rarr;</a>
                            </div>
                        `, { maxWidth: 260 });

                        markersLayer.addLayer(marker);
                    });

                    document.getElementById('map-loader').style.display = 'none';
                })
                .catch(() => {
                    document.getElementById('map-loader').style.display = 'none';
                    document.getElementById('stations-count').textContent = 'Erreur de chargement';
                });
        }

        function applyFilters() {
            loadStations({
                city:   document.getElementById('filter-city').value,
                type:   document.getElementById('filter-type').value,
                status: document.getElementById('filter-status').value,
            });
        }

        function resetFilters() {
            document.getElementById('filter-city').value   = '';
            document.getElementById('filter-type').value   = '';
            document.getElementById('filter-status').value = '';
            loadStations();
        }

        function locateUser() {
            if (!navigator.geolocation) {
                showGeoError('Géolocalisation non supportée par votre navigateur.');
                return;
            }
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const { latitude, longitude } = pos.coords;
                    map.setView([latitude, longitude], 13);
                    L.circleMarker([latitude, longitude], {
                        radius: 8, fillColor: '#3b82f6',
                        color: '#fff', weight: 2, fillOpacity: 1
                    }).addTo(map).bindPopup('Vous êtes ici').openPopup();
                    loadStations({ lat: latitude, lng: longitude });
                },
                () => showGeoError('Impossible de vous localiser.')
            );
        }

        loadStations();
    </script>
@endpush
