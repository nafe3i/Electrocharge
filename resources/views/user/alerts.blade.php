@extends('layouts.app')

@section('content')

    <div class="max-w-2xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-medium text-gray-800">Mes alertes</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Soyez notifié quand une station devient disponible
                </p>
            </div>
            <button onclick="document.getElementById('create-alert').classList.toggle('hidden')" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm
                           hover:bg-green-700 transition">
                + Nouvelle alerte
            </button>
        </div>

        {{-- Formulaire création alerte --}}
        <div id="create-alert" class="hidden bg-white border border-gray-200
                                      rounded-xl p-5 mb-6">
            <h2 class="text-sm font-medium text-gray-700 mb-4">Créer une alerte</h2>
            <form method="POST" action="{{ route('alerts.store') }}">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Type d'alerte</label>
                        <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2
                                       text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="disponibilite">Disponibilité</option>
                            <option value="prix">Baisse de prix</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">
                            Seuil de prix (MAD/kWh)
                        </label>
                        <input type="number" name="threshold_price" step="0.01" placeholder="Ex: 2.50" class="w-full border border-gray-200 rounded-lg px-3 py-2
                                      text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="document.getElementById('create-alert').classList.add('hidden')" class="border border-gray-200 text-gray-600 px-4 py-2 rounded-lg
                                   text-sm hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm
                                   hover:bg-green-700 transition">
                        Créer l'alerte
                    </button>
                </div>
            </form>
        </div>

        {{-- Liste des alertes --}}
        <div class="space-y-3">
            @forelse($alerts as $alert)
                <div class="bg-white border border-gray-200 rounded-xl p-4
                                flex items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-medium text-gray-800">
                                {{ $alert->type === 'disponibilite' ? 'Disponibilité' : 'Baisse de prix' }}
                            </span>
                            @if($alert->station)
                                <span class="text-xs text-gray-500">
                                    — {{ $alert->station->name }}
                                </span>
                            @endif
                        </div>
                        @if($alert->threshold_price)
                            <div class="text-xs text-gray-500">
                                Seuil : {{ $alert->threshold_price }} MAD/kWh
                            </div>
                        @endif
                    </div>

                    {{-- Toggle actif/inactif --}}
                    <form method="POST" action="{{ route( 'alerts.toggle',$alert) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full
                                           transition-colors
                                           {{ $alert->is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white
                                             transition-transform
                                             {{ $alert->is_active ? 'translate-x-6' : 'translate-x-1' }}">
                            </span>
                        </button>
                    </form>

                    {{-- Supprimer --}}
                    <form method="POST" action="{{ route('alerts.destroy', $alert) }} }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Supprimer cette alerte ?')"
                            class="text-gray-400 hover:text-red-500 transition text-lg">
                            ×
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-14 bg-white border border-gray-200 rounded-xl">
                    <div class="text-5xl mb-4">🔔</div>
                    <h2 class="text-lg font-medium text-gray-700 mb-2">
                        Aucune alerte configurée
                    </h2>
                    <p class="text-gray-400 text-sm">
                        Créez une alerte pour être notifié automatiquement
                    </p>
                </div>
            @endforelse
        </div>
    </div>

@endsection