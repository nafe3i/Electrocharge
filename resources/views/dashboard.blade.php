@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-xl font-medium text-gray-800 mb-6">
        Bonjour, {{ auth()->user()->name }} 👋
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <a href="{{ route('favorites.index') }}"
           class="bg-white border border-gray-200 rounded-xl p-5 hover:border-green-300 transition">
            <div class="text-2xl mb-2">❤️</div>
            <div class="font-medium text-gray-800">Mes favoris</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ auth()->user()->favorites()->count() }} station(s)
            </div>
        </a>

        <a href="{{ route('alerts.index') }}"
           class="bg-white border border-gray-200 rounded-xl p-5 hover:border-green-300 transition">
            <div class="text-2xl mb-2">🔔</div>
            <div class="font-medium text-gray-800">Mes alertes</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ auth()->user()->alerts()->where('is_active', true)->count() }} active(s)
            </div>
        </a>

        <a href="{{ route('history.index') }}"
           class="bg-white border border-gray-200 rounded-xl p-5 hover:border-green-300 transition">
            <div class="text-2xl mb-2">🕐</div>
            <div class="font-medium text-gray-800">Historique</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ auth()->user()->searchHistory()->count() }} recherche(s)
            </div>
        </a>
    </div>
</div>

@endsection
