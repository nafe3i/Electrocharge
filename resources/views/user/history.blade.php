@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-medium text-gray-800">Historique</h1>
            <p class="text-sm text-gray-500 mt-1">
                Vos {{ $history->count() }} dernières recherches
            </p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        @forelse($history as $item)
            <div class="flex items-center gap-4 px-5 py-3.5
                        border-b border-gray-100 last:border-0 hover:bg-gray-50">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center
                            justify-center text-gray-500 flex-shrink-0">
                    🔍
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800 truncate">
                        {{ $item->query ?? 'Recherche sans texte' }}
                    </div>
                    @if($item->filters)
                        <div class="text-xs text-gray-400 mt-0.5">
                            Filtres :
                            @foreach($item->filters as $key => $val)
                                <span class="bg-gray-100 px-1.5 py-0.5 rounded">
                                    {{ $key }}: {{ $val }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="text-xs text-gray-400 flex-shrink-0">
                    {{ $item->created_at->diffForHumans() }}
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-4xl mb-3">📋</div>
                <p class="text-gray-500 text-sm">Aucun historique pour le moment.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection