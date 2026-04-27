@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Mon espace opérateur</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ auth()->user()->name }}
                &mdash; {{ $stations->count() }} station(s) sous votre gestion
            </p>
        </div>

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

        @forelse($stations as $station)
            <div class="bg-white border border-slate-200 rounded-xl p-5 mb-4">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-semibold text-slate-800">{{ $station->name }}</h2>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $station->city }}</p>
                    </div>
                    <a href="{{ route('stations.show', $station) }}" class="inline-flex items-center gap-1 text-sm text-green-600
                                  hover:text-green-700 font-medium transition">
                        Voir la page publique
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

                {{-- Connecteurs --}}
                <div class="space-y-0">
                    @foreach($station->connectors as $connector)
                        @php $status = $connector->status?->status ?? 'inconnu'; @endphp
                        <div class="flex items-center justify-between py-2.5
                                            border-b border-slate-100 last:border-0">

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center
                                                    justify-center text-xs font-bold text-slate-600">
                                    {{ substr($connector->type, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-700">
                                        {{ $connector->type }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $connector->power_kw }} kW
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-xs px-2.5 py-1 rounded-full border font-medium
                                                     {{ $statusColors[$status] ?? $statusColors['inconnu'] }}">
                                    {{ $statusLabels[$status] ?? 'Inconnu' }}
                                </span>

                                @can('update-connector-status')
                                    <form method="POST" action="{{ route('operator.connectors.status', $connector) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs border border-slate-200 rounded-lg
                                                                   px-2 py-1 bg-white focus:outline-none
                                                                   focus:ring-2 focus:ring-green-500">
                                            <option value="libre" {{ $status === 'libre' ? 'selected' : '' }}>
                                                Libre
                                            </option>
                                            <option value="occupee" {{ $status === 'occupee' ? 'selected' : '' }}>
                                                Occupée
                                            </option>
                                            <option value="hors_service" {{ $status === 'hors_service' ? 'selected' : '' }}>
                                                Hors service
                                            </option>
                                        </select>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white border border-slate-200 rounded-xl">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center
                                justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p class="text-slate-500 text-sm font-medium mb-1">
                    Aucune station assignée
                </p>
                <p class="text-slate-400 text-sm">
                    Contactez un administrateur pour vous assigner des stations.
                </p>
            </div>
        @endforelse
    </div>
@endsection