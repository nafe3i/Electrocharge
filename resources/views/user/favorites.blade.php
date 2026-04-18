@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-medium text-gray-800">Mes favoris</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $favorites->count() }} station(s) sauvegardée(s)
            </p>
        </div>
        <a href="{{ route('map') }}"
           class="text-sm text-green-600 hover:underline">
            Explore la carte →
        </a>
    </div>

    @forelse($favorites as $favorite)
        @php $station = $favorite->station; @endphp
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-3
                    flex items-center gap-4">

            {{-- Photo ou placeholder --}}
            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-green-50
                        flex items-center justify-center">
                @if($station->photo_url)
                    <img src="{{ $station->photo_url }}"
                         alt="{{ $station->name }}"
                         class="w-full h-full object-cover">
                @else
                    <span class="text-2xl">⚡</span>
                @endif
            </div>

            {{-- Infos --}}
            <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-800 truncate">{{ $station->name }}</div>
                <div class="text-sm text-gray-500">
                    {{ $station->city }} · {{ $station->operator_name ?? 'Opérateur inconnu' }}
                </div>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-xs text-gray-400">
                        {{ $station->connectors->count() }} connecteur(s)
                    </span>
                    @if($station->connectors->isNotEmpty())
                        <span class="text-xs text-gray-400">
                            {{ $station->connectors->pluck('type')->unique()->join(', ') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('stations.show', $station) }}"
                   class="text-sm border border-gray-200 text-gray-600 px-3 py-1.5
                          rounded-lg hover:bg-gray-50 transition">
                    Voir
                </a>
                <form method="POST" action="{{ route('favorites.destroy', $station) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Retirer des favoris ?')"
                            class="text-sm border border-red-200 text-red-500 px-3 py-1.5
                                   rounded-lg hover:bg-red-50 transition">
                        Retirer
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center py-16 bg-white border border-gray-200 rounded-xl">
            <div class="text-5xl mb-4">🤍</div>
            <h2 class="text-lg font-medium text-gray-700 mb-2">
                Pas encore de favoris
            </h2>
            <p class="text-gray-400 text-sm mb-6">
                Explorez la carte et sauvegardez vos stations préférées
            </p>
            <a href="{{ route('map') }}"
               class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm
                      hover:bg-green-700 transition">
                Explorer la carte
            </a>
        </div>
    @endforelse
</div>

@endsection