@extends('layouts.admin')

@section('page-title', 'Modifier la station')

@section('content')
<div class="max-w-2xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 mb-6 text-sm">
        <a href="{{ route('admin.stations.index') }}"
           class="text-slate-400 hover:text-slate-600 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-600">{{ $station->name }}</span>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <h2 class="text-sm font-semibold text-slate-800 mb-6 pb-4 border-b border-slate-100">
            Modifier les informations
        </h2>

        <form method="POST" action="{{ route('admin.stations.update', $station) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">

                {{-- Nom --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $station->name) }}"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-slate-200' }}">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Adresse --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse</label>
                    <input type="text" name="address"
                           value="{{ old('address', $station->address) }}"
                           class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                {{-- Ville --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Ville <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city"
                           value="{{ old('city', $station->city) }}"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  {{ $errors->has('city') ? 'border-red-400' : 'border-slate-200' }}">
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Opérateur nom --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Opérateur (nom)
                    </label>
                    <input type="text" name="operator_name"
                           value="{{ old('operator_name', $station->operator_name) }}"
                           class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                {{-- Opérateur compte --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Assigner à un opérateur (compte)
                    </label>
                    <select name="operator_id"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Aucun opérateur</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->id }}"
                                    {{ old('operator_id', $station->operator_id) == $op->id ? 'selected' : '' }}>
                                {{ $op->name }} — {{ $op->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Latitude --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="latitude" step="any"
                           value="{{ old('latitude', $station->latitude) }}"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  {{ $errors->has('latitude') ? 'border-red-400' : 'border-slate-200' }}">
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Longitude --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Longitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="longitude" step="any"
                           value="{{ old('longitude', $station->longitude) }}"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  {{ $errors->has('longitude') ? 'border-red-400' : 'border-slate-200' }}">
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Horaires --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Horaires d'ouverture
                    </label>
                    <input type="text" name="opening_hours"
                           value="{{ old('opening_hours', $station->opening_hours) }}"
                           placeholder="Ex: 24h/24, 7j/7"
                           class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                {{-- Photo URL --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        URL de la photo
                    </label>
                    <input type="url" name="photo_url"
                           value="{{ old('photo_url', $station->photo_url) }}"
                           placeholder="https://..."
                           class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                {{-- Statut actif --}}
                <div class="col-span-2 flex items-center gap-3 py-1">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', $station->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
                    <label for="is_active" class="text-sm text-slate-700">
                        Station active (visible sur la carte)
                    </label>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.stations.index') }}"
                   class="border border-slate-200 text-slate-600 px-5 py-2.5 rounded-lg
                          text-sm hover:bg-slate-50 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm
                               font-medium hover:bg-green-700 transition">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
