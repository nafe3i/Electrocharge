@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Historique</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ $history->count() }} recherche(s) enregistrée(s)
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            @forelse($history as $item)
                <div
                    class="flex items-center gap-4 px-5 py-3.5 border-b border-slate-100 last:border-0 hover:bg-slate-50 transition">

                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-800 truncate">
                            {{ $item->query ?? 'Recherche sans texte' }}
                        </div>
                        @if($item->filters)
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($item->filters as $key => $val)
                                    <span class="bg-slate-100 text-slate-500 text-xs px-1.5 py-0.5 rounded">
                                        {{ $key }}: {{ $val }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="text-xs text-slate-400 flex-shrink-0">
                        {{ $item->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm">Aucun historique pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection