@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Bonjour, {{ auth()->user()->name }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">Bienvenue sur votre espace ElectroCharge.</p>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            <a href="{{ route('favorites.index') }}" class="group bg-white border border-slate-200 rounded-xl p-5
                      hover:border-green-300 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Favoris</span>
                    <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-900 tabular-nums">{{ $favoritesCount }}</div>
                <div class="text-sm text-slate-500 mt-1">station(s) sauvegardée(s)</div>
            </a>

            <a href="{{ route('alerts.index') }}" class="group bg-white border border-slate-200 rounded-xl p-5
                      hover:border-green-300 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Alertes actives</span>
                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-900 tabular-nums">{{ $alertsCount }}</div>
                <div class="text-sm text-slate-500 mt-1">alerte(s) en cours</div>
            </a>

            <a href="{{ route('history.index') }}" class="group bg-white border border-slate-200 rounded-xl p-5
                      hover:border-green-300 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Historique</span>
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-900 tabular-nums">{{ $historyCount }}</div>
                <div class="text-sm text-slate-500 mt-1">recherche(s) effectuée(s)</div>
            </a>

        </div>

        {{-- Action rapide --}}
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl p-6 flex items-center justify-between">
            <div>
                <p class="text-white font-semibold text-base">Trouver une station</p>
                <p class="text-green-100 text-sm mt-0.5">Explorez la carte interactive en temps réel</p>
            </div>
            <a href="{{ route('map') }}" class="flex-shrink-0 bg-white text-green-700 px-5 py-2.5 rounded-xl
                      text-sm font-semibold hover:bg-green-50 transition shadow-sm">
                Ouvrir la carte
            </a>
        </div>

    </div>
@endsection