@extends('layouts.admin')

@section('page-title', 'Ajouter une station')

@section('content')

<div class="max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.stations.index') }}"
           class="text-gray-400 hover:text-gray-600 text-sm">
            ← Retour
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-600">Nouvelle station</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="text-base font-medium text-gray-800 mb-6 pb-4 border-b border-gray-100">
            Informations de la station
        </h2>

        <form method="POST" action="{{ route('admin.stations.store') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4">

                {{-- Nom --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nom de la station <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Ex: FastVolt Afriquia Casablanca"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Adresse --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Adresse
                    </label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           placeholder="Ex: Route Nationale N1, Km 5"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                                  text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Ville --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Ville <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           placeholder="Ex: Casablanca"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('city') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Opérateur --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Opérateur
                    </label>
                    <input type="text" name="operator_name" value="{{ old('operator_name') }}"
                           placeholder="Ex: Fastvolt, ONEE..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                                  text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Latitude --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="latitude" value="{{ old('latitude') }}"
                           step="any" placeholder="Ex: 33.589886"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('latitude') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Longitude --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Longitude <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="longitude" value="{{ old('longitude') }}"
                           step="any" placeholder="Ex: -7.603869"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('longitude') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Horaires --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Horaires d'ouverture
                    </label>
                    <input type="text" name="opening_hours" value="{{ old('opening_hours') }}"
                           placeholder="Ex: 24h/24, 7j/7"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                                  text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Photo URL --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        URL de la photo
                    </label>
                    <input type="url" name="photo_url" value="{{ old('photo_url') }}"
                           placeholder="https://..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5
                                  text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('photo_url') ? 'border-red-400' : '' }}">
                    @error('photo_url')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
                    Créer la station
                </button>
            </div>
        </form>
    </div>
</div>

@endsection