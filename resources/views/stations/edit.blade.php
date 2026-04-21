@extends('layouts.admin')

@section('page-title', 'Modifier la station')

@section('content')

<div class="max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.stations.index') }}"
           class="text-gray-400 hover:text-gray-600 text-sm">← Retour</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-600">{{ $station->name }}</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="text-base font-medium text-gray-800 mb-6 pb-4 border-b border-gray-100">
            Modifier les informations
        </h2>

        <form method="POST" action="{{ route('admin.stations.update', $station) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $station->name) }}"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse</label>
                    <input type="text" name="address"
                           value="{{ old('address', $station->address) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                                  text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Ville <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city"
                           value="{{ old('city', $station->city) }}"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('city') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Opérateur
                    </label>
                    <input type="text" name="operator_name"
                           value="{{ old('operator_name', $station->operator_name) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                                  text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
<div class="col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-1.5">
        Assigner à un opérateur
    </label>
    <select name="operator_id"
            class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                   text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="">Aucun opérateur</option>
        @foreach($operators as $op)
            <option value="{{ $op->id }}"
                    {{ old('operator_id', $station->operator_id) == $op->id ? 'selected' : '' }}>
                {{ $op->name }} — {{ $op->email }}
            </option>
        @endforeach
    </select>
</div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="latitude" step="any"
                           value="{{ old('latitude', $station->latitude) }}"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('latitude') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Longitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="longitude" step="any"
                           value="{{ old('longitude', $station->longitude) }}"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('longitude') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Horaires d'ouverture
                    </label>
                    <input type="text" name="opening_hours"
                           value="{{ old('opening_hours', $station->opening_hours) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                                  text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        URL de la photo
                    </label>
                    <input type="url" name="photo_url"
                           value="{{ old('photo_url', $station->photo_url) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                                  text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Toggle actif/inactif --}}
                <div class="col-span-2 flex items-center gap-3 py-2">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', $station->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-green-600
                                  focus:ring-green-500">
                    <label for="is_active" class="text-sm text-gray-700">
                        Station active (visible sur la carte)
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.stations.index') }}"
                   class="border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg
                          text-sm hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm
                               hover:bg-green-700 transition font-medium">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

@endsection