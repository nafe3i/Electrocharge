@extends('layouts.admin')

@section('page-title', "Vue d'ensemble")

@section('content')

    {{-- Chiffres clés --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">
                Stations
            </div>
            <div class="text-3xl font-bold text-slate-900 tabular-nums">{{ $totalStations }}</div>
            <div class="text-xs text-green-600 mt-1 font-medium">{{ $activeStations }} actives</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">
                Utilisateurs
            </div>
            <div class="text-3xl font-bold text-slate-900 tabular-nums">{{ $totalUsers }}</div>
            <div class="text-xs text-slate-400 mt-1">Inscrits</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">
                Connecteurs
            </div>
            <div class="text-3xl font-bold text-slate-900 tabular-nums">{{ $totalConnectors }}</div>
            <div class="text-xs text-slate-400 mt-1">Sur toutes les stations</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">
                Avis publiés
            </div>
            <div class="text-3xl font-bold text-slate-900 tabular-nums">{{ $totalReviews }}</div>
            <div class="text-xs text-slate-400 mt-1">{{ $totalFavorites }} favoris</div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

        {{-- Statuts des bornes --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <h2 class="text-sm font-semibold text-slate-800 mb-4">Statuts des bornes</h2>

            @php
                $total = ($libreCount + $occupeeCount + $horsServiceCount) ?: 1;
                $bars = [
                    ['label' => 'Libre', 'count' => $libreCount, 'bar' => 'bg-green-500', 'text' => 'text-green-700'],
                    ['label' => 'Occupée', 'count' => $occupeeCount, 'bar' => 'bg-orange-500', 'text' => 'text-orange-700'],
                    ['label' => 'Hors service', 'count' => $horsServiceCount, 'bar' => 'bg-red-500', 'text' => 'text-red-700'],
                ];
            @endphp

            <div class="space-y-3">
                @foreach($bars as $bar)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600">{{ $bar['label'] }}</span>
                            <span class="font-semibold {{ $bar['text'] }}">{{ $bar['count'] }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $bar['bar'] }} rounded-full transition-all"
                                style="width: {{ round($bar['count'] / $total * 100) }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Stations récentes --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-800">Dernières stations</h2>
                <a href="{{ route('admin.stations.index') }}"
                    class="text-xs text-green-600 hover:text-green-700 font-medium transition">
                    Voir tout &rarr;
                </a>
            </div>
            <div class="space-y-1">
                @foreach($recentStations as $station)
                        <div class="flex items-center justify-between py-2
                                        border-b border-slate-50 last:border-0">
                            <div>
                                <div class="text-sm font-medium text-slate-700">{{ $station->name }}</div>
                                <div class="text-xs text-slate-400">{{ $station->city }}</div>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
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
    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-slate-800 mb-4">Journal des actions récentes</h2>

        @forelse($recentLogs as $log)
            <div class="flex items-center gap-4 py-2.5 border-b border-slate-50 last:border-0">
                <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center
                                text-xs font-semibold text-green-700 flex-shrink-0">
                    {{ strtoupper(substr($log->admin->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-sm text-slate-700">
                        <span class="font-medium">{{ $log->admin->name ?? 'Admin' }}</span>
                        &mdash;
                        <span class="font-mono text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">
                            {{ $log->action }}
                        </span>
                    </span>
                </div>
                <span class="text-xs text-slate-400 flex-shrink-0">
                    {{ $log->created_at->diffForHumans() }}
                </span>
            </div>
        @empty
            <p class="text-sm text-slate-400 text-center py-4">Aucune action enregistrée.</p>
        @endforelse
    </div>

@endsection