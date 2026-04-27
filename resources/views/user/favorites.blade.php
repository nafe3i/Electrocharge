@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Mes favoris</h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $favorites->count() }} station(s) sauvegardée(s)
                </p>
            </div>
            <a href="{{ route('map') }}" class="text-sm text-green-600 hover:text-green-700 font-medium transition">
                Explorer la carte &rarr;
            </a>
        </div>

        @forelse($favorites as $favorite)
            @php $station = $favorite->station; @endphp
            <div class="bg-white border border-slate-200 rounded-xl p-4 mb-3 flex items-center gap-4">

                {{-- Thumbnail --}}
                <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0 bg-slate-100
                                flex items-center justify-center">
                    @if($station->photo_url)
                        <img src="{{ $station->photo_url }}" alt="{{ $station->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-slate-800 truncate">{{ $station->name }}</div>
                    <div class="text-sm text-slate-500 mt-0.5">
                        {{ $station->city }} &middot; {{ $station->operator_name ?? 'Opérateur inconnu' }}
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs text-slate-400">
                            {{ $station->connectors->count() }} connecteur(s)
                        </span>
                        @if($station->connectors->isNotEmpty())
                            <span class="text-xs text-slate-400">
                                {{ $station->connectors->pluck('type')->unique()->join(', ') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('stations.show', $station) }}" class="text-sm border border-slate-200 text-slate-600 px-3 py-1.5
                                  rounded-lg hover:bg-slate-50 transition">
                        Voir
                    </a>
                    <form method="POST" action="{{ route('favorites.destroy', $station) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm border border-red-200 text-red-500 px-3 py-1.5
                                           rounded-lg hover:bg-red-50 transition">
                            Retirer
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white border border-slate-200 rounded-xl">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="text-base font-semibold text-slate-700 mb-1">Aucun favori enregistré</h2>
                <p class="text-slate-400 text-sm mb-6">Explorez la carte et sauvegardez vos stations préférées</p>
                <a href="{{ route('map') }}"
                    class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                    Explorer la carte
                </a>
            </div>
        @endforelse
    </div>
@endsection