@extends('layouts.admin')

@section('page-title', 'Vue d\'ensemble')

@section('content')

@php
    $totalStations  = \App\Models\Station::count();
    $activeStations = \App\Models\Station::where('is_active', true)->count();
    $totalUsers     = \App\Models\User::count();
    $totalReviews   = \App\Models\Review::count();
    $totalFavorites = \App\Models\Favorite::count();
    $totalConnectors= \App\Models\Connector::count();

    $libreCount      = \App\Models\ConnectorStatus::where('status', 'libre')->count();
    $occupeeCount    = \App\Models\ConnectorStatus::where('status', 'occupee')->count();
    $horsServiceCount= \App\Models\ConnectorStatus::where('status', 'hors_service')->count();

    $recentStations = \App\Models\Station::latest()->take(5)->get();
    $recentLogs     = \App\Models\AdminLog::with('admin')->latest()->take(8)->get();
@endphp

{{-- Chiffres clés --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="text-sm text-gray-500 mb-1">Stations totales</div>
        <div class="text-3xl font-semibold text-gray-900">{{ $totalStations }}</div>
        <div class="text-xs text-green-600 mt-1">{{ $activeStations }} actives</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="text-sm text-gray-500 mb-1">Utilisateurs</div>
        <div class="text-3xl font-semibold text-gray-900">{{ $totalUsers }}</div>
        <div class="text-xs text-gray-400 mt-1">Inscrits</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="text-sm text-gray-500 mb-1">Connecteurs</div>
        <div class="text-3xl font-semibold text-gray-900">{{ $totalConnectors }}</div>
        <div class="text-xs text-gray-400 mt-1">Sur toutes les stations</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="text-sm text-gray-500 mb-1">Avis publiés</div>
        <div class="text-3xl font-semibold text-gray-900">{{ $totalReviews }}</div>
        <div class="text-xs text-gray-400 mt-1">{{ $totalFavorites }} favoris</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Statuts des bornes --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h2 class="text-base font-medium text-gray-800 mb-4">
            Statuts des bornes en ce moment
        </h2>
        @php $total = $libreCount + $occupeeCount + $horsServiceCount ?: 1; @endphp

        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Libre</span>
                    <span class="font-medium text-green-700">{{ $libreCount }}</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full transition-all"
                         style="width: {{ round($libreCount / $total * 100) }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Occupée</span>
                    <span class="font-medium text-orange-700">{{ $occupeeCount }}</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-500 rounded-full transition-all"
                         style="width: {{ round($occupeeCount / $total * 100) }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Hors service</span>
                    <span class="font-medium text-red-700">{{ $horsServiceCount }}</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500 rounded-full transition-all"
                         style="width: {{ round($horsServiceCount / $total * 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stations récentes --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-medium text-gray-800">Dernières stations</h2>
            <a href="{{ route('admin.stations.index') }}"
               class="text-sm text-green-600 hover:underline">Voir tout</a>
        </div>
        <div class="space-y-2">
            @foreach($recentStations as $station)
                <div class="flex items-center justify-between py-2
                            border-b border-gray-50 last:border-0">
                    <div>
                        <div class="text-sm font-medium text-gray-700">
                            {{ $station->name }}
                        </div>
                        <div class="text-xs text-gray-400">{{ $station->city }}</div>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full
                                 {{ $station->is_active
                                    ? 'bg-green-50 text-green-700'
                                    : 'bg-red-50 text-red-700' }}">
                        {{ $station->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Journal des actions --}}
<div class="bg-white border border-gray-200 rounded-xl p-5">
    <h2 class="text-base font-medium text-gray-800 mb-4">Journal des actions</h2>
    @forelse($recentLogs as $log)
        <div class="flex items-center gap-4 py-2.5 border-b border-gray-50 last:border-0">
            <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center
                        text-xs font-medium text-gray-600 flex-shrink-0">
                {{ strtoupper(substr($log->admin->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <span class="text-sm text-gray-700">
                    <span class="font-medium">{{ $log->admin->name ?? 'Admin' }}</span>
                    —
                    <span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded">
                        {{ $log->action }}
                    </span>
                </span>
            </div>
            <span class="text-xs text-gray-400 flex-shrink-0">
                {{ $log->created_at->diffForHumans() }}
            </span>
        </div>
    @empty
        <p class="text-sm text-gray-400 text-center py-4">Aucune action enregistrée.</p>
    @endforelse
</div>

@endsection