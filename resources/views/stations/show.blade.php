@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
            <a href="{{ route('map') }}" class="hover:text-green-600 transition">Carte</a>
            <span>/</span>
            <span class="text-slate-700">{{ $station->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Colonne principale --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Header station --}}
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    @if($station->photo_url)
                        <img src="{{ $station->photo_url }}" alt="{{ $station->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-40 bg-gradient-to-br from-green-50 to-emerald-100
                                        flex items-center justify-center">
                            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h1 class="text-xl font-semibold text-slate-900">
                                    {{ $station->name }}
                                </h1>
                                <p class="text-slate-500 text-sm mt-1">
                                    {{ $station->address ?? '' }}
                                    @if($station->city) &middot; {{ $station->city }} @endif
                                </p>
                                @if($station->operator_name)
                                    <p class="text-xs text-slate-400 mt-1">
                                        Opérateur : {{ $station->operator_name }}
                                    </p>
                                @endif
                            </div>

                            @if($station->reviews->count() > 0)
                                <div class="text-center flex-shrink-0 bg-amber-50 border border-amber-200
                                                rounded-xl px-3 py-2">
                                    <div class="text-xl font-bold text-amber-600">
                                        {{ $station->averageRating() }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        /5 &middot; {{ $station->reviews->count() }} avis
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($station->opening_hours)
                            <div class="mt-3 flex items-center gap-2 text-sm text-slate-600
                                            bg-slate-50 rounded-lg px-3 py-2">
                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $station->opening_hours }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Connecteurs --}}
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <h2 class="text-sm font-semibold text-slate-800 mb-4">Connecteurs disponibles</h2>

                    {{-- Maps définis une seule fois, hors de la boucle --}}
                    @php
                        $statusLabels = [
                            'libre' => 'Libre',
                            'occupee' => 'Occupée',
                            'hors_service' => 'Hors service',
                            'inconnu' => 'Inconnu',
                        ];
                        $statusColors = [
                            'libre' => 'bg-green-50 text-green-700 border-green-200',
                            'occupee' => 'bg-orange-50 text-orange-700 border-orange-200',
                            'hors_service' => 'bg-red-50 text-red-700 border-red-200',
                            'inconnu' => 'bg-slate-50 text-slate-600 border-slate-200',
                        ];
                    @endphp

                    @forelse($station->connectors as $connector)
                        @php $status = $connector->status?->status ?? 'inconnu'; @endphp
                        <div class="flex items-center justify-between py-3
                                        border-b border-slate-100 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center
                                                justify-center text-xs font-bold text-slate-600">
                                    {{ substr($connector->type, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-medium text-slate-800 text-sm">
                                        {{ $connector->type }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $connector->power_kw }} kW
                                        &middot; {{ $connector->quantity }} prise(s)
                                        @if($connector->price_per_kwh)
                                            &middot; {{ $connector->price_per_kwh }} MAD/kWh
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs px-2.5 py-1 rounded-full border font-medium
                                                 {{ $statusColors[$status] ?? $statusColors['inconnu'] }}">
                                    {{ $statusLabels[$status] ?? 'Inconnu' }}
                                </span>
                                @if($connector->status)
                                    <span class="text-xs text-slate-400">
                                        {{ $connector->status->last_updated_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">
                            Aucun connecteur renseigné pour cette station.
                        </p>
                    @endforelse
                </div>

                {{-- Avis --}}
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-slate-800">
                            Avis ({{ $station->reviews->count() }})
                        </h2>
                        @auth
                            <button onclick="document.getElementById('review-form').classList.toggle('hidden')"
                                class="text-sm text-green-600 hover:text-green-700 font-medium transition">
                                Laisser un avis
                            </button>
                        @endauth
                    </div>

                    @auth
                        <div id="review-form" class="hidden mb-5 bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <form method="POST" action="{{ route('reviews.store', $station) }}">
                                @csrf
                                <input type="hidden" name="station_id" value="{{ $station->id }}">

                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">
                                        Note
                                    </label>
                                    <select name="rating" class="border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                                                       focus:outline-none focus:ring-2 focus:ring-green-500
                                                       focus:border-transparent">
                                        <option value="5">5 / 5 — Excellent</option>
                                        <option value="4">4 / 5 — Bien</option>
                                        <option value="3">3 / 5 — Moyen</option>
                                        <option value="2">2 / 5 — Mauvais</option>
                                        <option value="1">1 / 5 — Très mauvais</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('rating')" class="mt-1" />
                                </div>

                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">
                                        Commentaire (optionnel)
                                    </label>
                                    <textarea name="comment" rows="3" placeholder="Partagez votre expérience..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm
                                                         focus:outline-none focus:ring-2 focus:ring-green-500
                                                         focus:border-transparent resize-none">{{ old('comment') }}</textarea>
                                    <x-input-error :messages="$errors->get('comment')" class="mt-1" />
                                </div>

                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm
                                                   font-medium hover:bg-green-700 transition">
                                    Publier l'avis
                                </button>
                            </form>
                        </div>
                    @endauth

                    @forelse($station->reviews as $review)
                        <div class="py-3 border-b border-slate-100 last:border-0">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center
                                                    justify-center text-green-700 text-xs font-semibold">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">
                                        {{ $review->user->name }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="bg-amber-50 text-amber-700 border border-amber-200
                                                     text-xs font-medium px-2 py-0.5 rounded-full">
                                        {{ $review->rating }}/5
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        {{ $review->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-sm text-slate-600 ml-9">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-6">
                            Aucun avis pour cette station.
                            @auth Soyez le premier à laisser un avis ! @endauth
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Colonne droite --}}
            <div class="space-y-4">

                {{-- Actions --}}
                <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-2">
                    @auth
                        @if($isFavorite)
                            <form method="POST" action="{{ route('favorites.destroy', $station) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2.5 rounded-lg text-sm font-medium
                                                       bg-red-50 text-red-600 border border-red-200
                                                       hover:bg-red-100 transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Retirer des favoris
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('favorites.store', $station) }}">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-lg text-sm font-medium
                                                       bg-green-50 text-green-700 border border-green-200
                                                       hover:bg-green-100 transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Ajouter aux favoris
                                </button>
                            </form>
                        @endif

                        @if(auth()->user()->can('manage-alerts'))
                            <form method="POST" action="{{ route('alerts.store') }}">
                                @csrf
                                <input type="hidden" name="station_id" value="{{ $station->id }}">
                                <input type="hidden" name="type" value="disponibilite">
                                <button type="submit" class="w-full py-2.5 rounded-lg text-sm font-medium
                                                       bg-amber-50 text-amber-700 border border-amber-200
                                                       hover:bg-amber-100 transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    Recevoir une alerte
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full py-2.5 rounded-lg text-sm font-medium border border-slate-200
                                      text-slate-600 hover:bg-slate-50 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Connectez-vous pour les favoris
                        </a>
                    @endauth

                    <a href="{{ route('map') }}" class="w-full py-2.5 rounded-lg text-sm font-medium border border-slate-200
                              text-slate-600 hover:bg-slate-50 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour à la carte
                    </a>
                </div>

                {{-- Mini carte --}}
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    <div id="mini-map" class="h-48"></div>
                </div>

                {{-- Infos rapides --}}
                <div class="bg-white border border-slate-200 rounded-xl p-4">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">
                        Informations
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Connecteurs</span>
                            <span class="font-medium text-slate-800">
                                {{ $station->connectors->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Types</span>
                            <span class="font-medium text-slate-800 text-right">
                                {{ $station->connectors->pluck('type')->unique()->join(', ') ?: '—' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Puissance max</span>
                            <span class="font-medium text-slate-800">
                                {{ $station->connectors->max('power_kw') ?? '—' }} kW
                            </span>
                        </div>
                        @if($station->connectors->whereNotNull('price_per_kwh')->isNotEmpty())
                            <div class="flex justify-between">
                                <span class="text-slate-500">Prix min</span>
                                <span class="font-medium text-green-700">
                                    {{ $station->connectors->whereNotNull('price_per_kwh')->min('price_per_kwh') }} MAD/kWh
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
        }).addTo(miniMap).bindPopup('{{ addslashes($station->name) }}').openPopup();
    </script>
@endpush