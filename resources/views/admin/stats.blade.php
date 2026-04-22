@extends('layouts.admin')

@section('page-title', 'Statistiques')

@section('content')

{{-- Chiffres clés --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="text-sm text-gray-500 mb-1">Stations totales</div>
        <div class="text-3xl font-semibold text-gray-900">{{ $stats['total_stations'] }}</div>
        <div class="text-xs text-green-600 mt-1">{{ $stats['stations_active'] }} actives</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="text-sm text-gray-500 mb-1">Utilisateurs</div>
        <div class="text-3xl font-semibold text-gray-900">{{ $stats['total_users'] }}</div>
        <div class="text-xs text-gray-400 mt-1">{{ $stats['total_operators'] }} opérateurs</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <div class="text-sm text-gray-500 mb-1">Avis publiés</div>
        <div class="text-3xl font-semibold text-gray-900">{{ $stats['total_reviews'] }}</div>
        <div class="text-xs text-gray-400 mt-1">{{ $stats['total_favorites'] }} favoris</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Statuts des bornes --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h2 class="text-base font-medium text-gray-800 mb-4">Statuts des bornes</h2>
        @php $total = array_sum($statuts) ?: 1; @endphp
        <div class="space-y-3">
            @foreach(['libre' => ['Libre', 'bg-green-500', 'text-green-700'],
                      'occupee' => ['Occupée', 'bg-orange-500', 'text-orange-700'],
                      'hors_service' => ['Hors service', 'bg-red-500', 'text-red-700']] as $key => $info)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">{{ $info[0] }}</span>
                    <span class="font-medium {{ $info[2] }}">
                        {{ $statuts[$key] }} ({{ round($statuts[$key] / $total * 100) }}%)
                    </span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $info[1] }} rounded-full"
                         style="width: {{ round($statuts[$key] / $total * 100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Types de connecteurs --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h2 class="text-base font-medium text-gray-800 mb-4">Types de connecteurs</h2>
        <div class="space-y-3">
            @foreach($parType as $type)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <span class="text-sm text-gray-700 font-medium">{{ $type->type }}</span>
                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                    {{ $type->total }} connecteur(s)
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Stations par ville --}}
<div class="bg-white border border-gray-200 rounded-xl p-5">
    <h2 class="text-base font-medium text-gray-800 mb-4">Stations par ville (Top 10)</h2>
    @php $maxVille = $parVille->max('total') ?: 1; @endphp
    <div class="space-y-3">
        @foreach($parVille as $ville)
        <div>
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-700">{{ $ville->city }}</span>
                <span class="font-medium text-gray-900">{{ $ville->total }} station(s)</span>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full"
                     style="width: {{ round($ville->total / $maxVille * 100) }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
