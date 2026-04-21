@extends('layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <h1 class="text-xl font-medium text-gray-800">
                Dashboard Opérateur
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ auth()->user()->name }} —
                {{ $stations->count() }} station(s) sous votre gestion
            </p>
        </div>

        @forelse($stations as $station)
            <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-medium text-gray-800">{{ $station->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $station->city }}</p>
                    </div>
                    <a href="{{ route('stations.show', $station) }}" class="text-sm text-green-600 hover:underline">
                        Voir public →
                    </a>
                </div>

                {{-- Connecteurs avec statuts --}}
                <div class="space-y-2">
                    @foreach($station->connectors as $connector)
                        @php
                            $status = $connector->status?->status ?? 'inconnu';
                            $colors = [
                                'libre' => 'bg-green-50 text-green-700 border-green-200',
                                'occupee' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'hors_service' => 'bg-red-50 text-red-700 border-red-200',
                                'inconnu' => 'bg-gray-50 text-gray-600 border-gray-200',
                            ];
                        @endphp
                        <div class="flex items-center justify-between py-2
                                                        border-b border-gray-100 last:border-0">
                            <div class="text-sm text-gray-700">
                                {{ $connector->type }} —
                                {{ $connector->power_kw }} kW
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-xs px-2.5 py-1 rounded-full border
                                                                 {{ $colors[$status] ?? $colors['inconnu'] }}">
                                    {{ ucfirst($status) }}
                                </span>

                                {{-- Opérateur peut mettre à jour le statut --}}
                                @can('update-connector-status')
                                    <form method="POST" action="{{ route('operator.connectors.status',$connector) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-200
                                                                                   rounded-lg px-2 py-1 bg-white
                                                                                   focus:outline-none">
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
            <div class="text-center py-12 bg-white border border-gray-200 rounded-xl">
                <p class="text-gray-400 text-sm">
                    Aucune station assignée à votre compte pour le moment.
                </p>
            </div>
        @endforelse
    </div>

@endsection