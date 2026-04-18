@extends('layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('map') }}" class="hover:text-green-600">Carte</a>
            <span>/</span>
            <span class="text-gray-700">{{ $station->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Colonne principale --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Header station --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    @if($station->photo_url)
                        <img src="{{ $station->photo_url }}" alt="{{ $station->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-green-50 to-green-100
                                                                flex items-center justify-center">
                            <span class="text-6xl">⚡</span>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h1 class="text-xl font-semibold text-gray-900">
                                    {{ $station->name }}
                                </h1>
                                <p class="text-gray-500 text-sm mt-1">
                                    {{ $station->address ?? '' }}
                                    @if($station->city)
                                        · {{ $station->city }}
                                    @endif
                                </p>
                                @if($station->operator_name)
                                    <p class="text-sm text-gray-400 mt-1">
                                        Opérateur : {{ $station->operator_name }}
                                    </p>
                                @endif
                            </div>

                            {{-- Note moyenne --}}
                            @if($station->reviews->count() > 0)
                                <div class="text-center flex-shrink-0">
                                    <div class="text-2xl font-bold text-yellow-500">
                                        {{ $station->averageRating() }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        /5 · {{ $station->reviews->count() }} avis
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($station->opening_hours)
                            <div class="mt-3 text-sm text-gray-600 bg-gray-50
                                                                    rounded-lg px-3 py-2">
                                🕐 {{ $station->opening_hours }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Connecteurs --}}
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h2 class="text-base font-medium text-gray-800 mb-4">
                        Connecteurs disponibles
                    </h2>

                    @forelse($station->connectors as $connector)
                        @php
                            $status = $connector->status?->status ?? 'inconnu';
                            $statusLabel = [
                                'libre' => 'Libre',
                                'occupee' => 'Occupée',
                                'hors_service' => 'Hors service',
                                'inconnu' => 'Inconnu'
                            ];
                            $statusColor = [
                                'libre' => 'bg-green-50 text-green-700 border-green-200',
                                'occupee' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'hors_service' => 'bg-red-50 text-red-700 border-red-200',
                                'inconnu' => 'bg-gray-50 text-gray-700 border-gray-200'
                            ];
                        @endphp
                        <div class="flex items-center justify-between py-3
                                                                border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center
                                                                        justify-center text-lg font-bold text-gray-600">
                                    {{ substr($connector->type, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800 text-sm">
                                        {{ $connector->type }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $connector->power_kw }} kW
                                        · {{ $connector->quantity }} prise(s)
                                        @if($connector->price_per_kwh)
                                            · {{ $connector->price_per_kwh }} MAD/kWh
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-xs px-2.5 py-1 rounded-full border font-medium
                                                                         {{ $statusColor[$status] ?? $statusColor['inconnu'] }}">
                                    {{ $statusLabel[$status] ?? 'Inconnu' }}
                                </span>
                                @if($connector->status)
                                    <span class="text-xs text-gray-400">
                                        {{ $connector->updated_at->diffForHumans() ?? 'Inconnu'}}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">
                            Aucun connecteur renseigné pour cette station.
                        </p>
                    @endforelse
                </div>

                {{-- Avis --}}
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-medium text-gray-800">
                            Avis ({{ $station->reviews->count() }})
                        </h2>
                        @auth
                            <button onclick="document.getElementById('review-form').classList.toggle('hidden')"
                                class="text-sm text-green-600 hover:text-green-700 font-medium">
                                + Laisser un avis
                            </button>
                        @endauth
                    </div>

                    {{-- Formulaire avis --}}
                    @auth
                        <div id="review-form" class="hidden mb-6 bg-gray-50 rounded-xl p-4">
                            <form method="POST" action="{{ route("reviews.store",$station) }}">
                                @csrf
                                <input type="hidden" name="station_id" value="{{ $station->id }}">

                                <div class="mb-3">
                                    <label class="block text-xs text-gray-500 mb-1">Note</label>
                                    <select name="rating" class="border border-gray-200 rounded-lg px-3 py-2
                                                                               text-sm focus:outline-none focus:ring-2
                                                                               focus:ring-green-500">
                                        <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                        <option value="4">⭐⭐⭐⭐ Bien</option>
                                        <option value="3">⭐⭐⭐ Moyen</option>
                                        <option value="2">⭐⭐ Mauvais</option>
                                        <option value="1">⭐ Très mauvais</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-xs text-gray-500 mb-1">
                                        Commentaire (optionnel)
                                    </label>
                                    <textarea name="comment" rows="3" placeholder="Partagez votre expérience..."
                                        class="w-full border border-gray-200 rounded-lg
                                                                                 px-3 py-2 text-sm focus:outline-none
                                                                                 focus:ring-2 focus:ring-green-500 resize-none">
                                                                </textarea>
                                </div>

                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg
                                                                           text-sm hover:bg-green-700 transition">
                                    Publier l'avis
                                </button>
                            </form>
                        </div>
                    @endauth

                    {{-- Liste des avis --}}
                    @forelse($station->reviews as $review)
                        <div class="py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center
                                                                            justify-center text-green-700 text-xs font-medium">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $review->user->name }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-yellow-500 text-sm">
                                        {{ str_repeat('⭐', $review->rating) }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ $review->created_at->diffForHumans() ?? 'inconu'}}
                                    </span>
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-sm text-gray-600 ml-9">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-6">
                            Aucun avis pour cette station.
                            @auth
                                Soyez le premier à laisser un avis !
                            @endauth
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Colonne droite --}}
            <div class="space-y-4">

                {{-- Actions --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 space-y-3">

                    @auth
                        @if($isFavorite)
                            {{-- Retirer des favoris --}}
                            <form method="POST" action="{{ route('favorites.destroy', $station) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2.5 rounded-lg text-sm font-medium
                                                   bg-red-50 text-red-600 border border-red-200
                                                   hover:bg-red-100 transition flex items-center
                                                   justify-center gap-2">
                                    ❤️ Retirer des favoris
                                </button>
                            </form>
                        @else
                            {{-- Ajouter aux favoris --}}
                            <form method="POST" action="{{ route('favorites.store', $station) }}">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-lg text-sm font-medium
                                                   bg-green-50 text-green-700 border border-green-200
                                                   hover:bg-green-100 transition flex items-center
                                                   justify-center gap-2">
                                    🤍 Ajouter aux favoris
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full py-2.5 rounded-lg text-sm font-medium border
                                  border-gray-200 text-gray-600 hover:bg-gray-50 transition
                                  flex items-center justify-center gap-2">
                            🔒 Connectez-vous pour les favoris
                        </a>
                    @endauth
                    {{-- Retour carte --}}
                    <a href="{{ route('map') }}" class="w-full py-2.5 rounded-lg text-sm font-medium border
                                          border-gray-200 text-gray-600 hover:bg-gray-50 transition
                                          flex items-center justify-center gap-2">
                        ← Retour à la carte
                    </a>
                </div>

                {{-- Mini carte --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div id="mini-map" class="h-48"></div>
                </div>

                {{-- Infos rapides --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 space-y-3">
                    <h3 class="text-sm font-medium text-gray-700">Informations</h3>

                    <div class="text-sm space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Connecteurs</span>
                            <span class="font-medium">{{ $station->connectors->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Types</span>
                            <span class="font-medium">
                                {{ $station->connectors->pluck('type')->unique()->join(', ') ?: '—' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Puissance max</span>
                            <span class="font-medium">
                                {{ $station->connectors->max('power_kw') ?? '—' }} kW
                            </span>
                        </div>
                        @if($station->connectors->whereNotNull('price_per_kwh')->isNotEmpty())
                            <div class="flex justify-between">
                                <span class="text-gray-500">Prix min</span>
                                <span class="font-medium text-green-700">
                                    {{ $station->connectors->whereNotNull('price_per_kwh')->min('price_per_kwh') }}
                                    MAD/kWh
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const miniMap = L.map('mini-map', { zoomControl: false, dragging: false })
            .setView([{{ $station->latitude }}, {{ $station->longitude }}], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(miniMap);

        L.circleMarker([{{ $station->latitude }}, {{ $station->longitude }}], {
            radius: 10, fillColor: '#16a34a',
            color: '#fff', weight: 2, fillOpacity: 1
        }).addTo(miniMap).bindPopup('{{ $station->name }}').openPopup();
    </script>
@endpush