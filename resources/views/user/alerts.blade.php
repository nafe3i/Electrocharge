@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Mes alertes</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Soyez notifié quand une station devient disponible
                </p>
            </div>
            <button onclick="document.getElementById('create-alert').classList.toggle('hidden')" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium
                           hover:bg-green-700 transition">
                Nouvelle alerte
            </button>
        </div>

        {{-- Formulaire création --}}
        {{-- Rouvert automatiquement si erreurs de validation --}}
        <div id="create-alert"
            class="{{ $errors->any() ? '' : 'hidden' }} bg-white border border-slate-200 rounded-xl p-5 mb-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Créer une alerte</h2>
            <form method="POST" action="{{ route('alerts.store') }}">
                @csrf
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Type d'alerte
                        </label>
                        <select name="type" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="disponibilite" {{ old('type') === 'disponibilite' ? 'selected' : '' }}>
                                Disponibilité
                            </option>
                            <option value="prix" {{ old('type') === 'prix' ? 'selected' : '' }}>
                                Baisse de prix
                            </option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Station</label>
                        <select name="station_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Toutes les stations</option>
                            @foreach($stations as $s)
                                <option value="{{ $s->id }}" {{ old('station_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('station_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Seuil prix (MAD/kWh)
                            <span class="text-slate-400 font-normal">— requis pour le type "Baisse de prix"</span>
                        </label>
                        <input type="number" name="threshold_price" step="0.01" value="{{ old('threshold_price') }}"
                            placeholder="Ex: 2.50" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                      {{ $errors->has('threshold_price') ? 'border-red-400' : '' }}">
                        @error('threshold_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="document.getElementById('create-alert').classList.add('hidden')" class="border border-slate-200 text-slate-600 px-4 py-2 rounded-lg
                                   text-sm hover:bg-slate-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm
                                   font-medium hover:bg-green-700 transition">
                        Créer l'alerte
                    </button>
                </div>
            </form>
        </div>

        {{-- Liste des alertes --}}
        <div class="space-y-3">
            @forelse($alerts as $alert)
                <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-4">

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-medium text-slate-800">
                                {{ $alert->type === 'disponibilite' ? 'Disponibilité' : 'Baisse de prix' }}
                            </span>
                            @if($alert->station)
                                <span class="text-xs text-slate-400">
                                    &mdash; {{ $alert->station->name }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400">&mdash; Toutes les stations</span>
                            @endif
                        </div>
                        @if($alert->threshold_price)
                            <div class="text-xs text-slate-500">
                                Seuil : {{ $alert->threshold_price }} MAD/kWh
                            </div>
                        @endif
                        <div class="mt-1">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                             {{ $alert->is_active
                ? 'bg-green-50 text-green-700'
                : 'bg-slate-100 text-slate-500' }}">
                                {{ $alert->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    {{-- Toggle actif/inactif --}}
                    <form method="POST" action="{{ route('alerts.toggle', $alert) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full
                                           transition-colors {{ $alert->is_active ? 'bg-green-500' : 'bg-slate-200' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow
                                             transition-transform
                                             {{ $alert->is_active ? 'translate-x-6' : 'translate-x-1' }}">
                            </span>
                        </button>
                    </form>

                    {{-- Supprimer --}}
                    <form method="POST" action="{{ route('alerts.destroy', $alert) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-300 hover:text-red-500 transition
                                           rounded-lg hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-14 bg-white border border-slate-200 rounded-xl">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-700 mb-1">Aucune alerte configurée</h2>
                    <p class="text-slate-400 text-sm">Créez une alerte pour être notifié automatiquement</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection