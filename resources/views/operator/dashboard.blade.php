@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-xl font-medium text-gray-800 mb-6">
        Bonjour, {{ auth()->user()->name }} 👋
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="text-2xl mb-2">⚡</div>
            <div class="font-medium text-gray-800">Mes stations</div>
            <div class="text-sm text-gray-500 mt-1">
                Gérez le statut de vos bornes
            </div>
            <a href="{{ route('map') }}"
               class="inline-block mt-3 text-sm text-green-600 hover:underline">
                Voir la carte →
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="text-2xl mb-2">📊</div>
            <div class="font-medium text-gray-800">Statistiques</div>
            <div class="text-sm text-gray-500 mt-1">
                Disponible prochainement
            </div>
        </div>

    </div>
</div>

@endsection
