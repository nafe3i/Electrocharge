@extends('layouts.admin')

@section('page-title', 'Gestion des stations')

@section('content')

    {{-- Header de la page --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-medium text-gray-800">Stations de recharge</h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $stations->total() }} station(s) au total
            </p>
        </div>
        <a href="{{ route('admin.stations.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm
                  hover:bg-green-700 transition flex items-center gap-2">
            + Ajouter une station
        </a>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('admin.stations.index') }}" class="bg-white border border-gray-200 rounded-xl p-4 mb-6
                 flex flex-wrap gap-3 items-end">

        {{-- Recherche --}}
        <div class="flex-1 min-w-48">
            <label class="block text-xs text-gray-500 mb-1">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, ville, opérateur..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        {{-- Filtre ville --}}
        <div class="min-w-36">
            <label class="block text-xs text-gray-500 mb-1">Ville</label>
            <select name="city" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Toutes</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                        {{ $city }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filtre statut --}}
        <div class="min-w-36">
            <label class="block text-xs text-gray-500 mb-1">Statut</label>
            <select name="active" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Tous</option>
                <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        {{-- Boutons --}}
        <div class="flex gap-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm
                           hover:bg-green-700 transition">
                Filtrer
            </button>
            <a href="{{ route('admin.stations.index') }}" class="border border-gray-200 text-gray-600 px-4 py-2 rounded-lg
                      text-sm hover:bg-gray-50 transition">
                Reset
            </a>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-500 font-medium">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="hover:text-gray-800 flex items-center gap-1">
                            Nom
                            @if(request('sort') === 'name')
                                {{ request('direction') === 'asc' ? '↑' : '↓' }}
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-4 py-3 text-gray-500 font-medium">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'city', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="hover:text-gray-800">
                            Ville
                        </a>
                    </th>
                    <th class="text-left px-4 py-3 text-gray-500 font-medium">Opérateur</th>
                    <th class="text-center px-4 py-3 text-gray-500 font-medium">Connecteurs</th>
                    <th class="text-center px-4 py-3 text-gray-500 font-medium">Avis</th>
                    <th class="text-center px-4 py-3 text-gray-500 font-medium">Favoris</th>
                    <th class="text-center px-4 py-3 text-gray-500 font-medium">Statut</th>
                    <th class="text-right px-4 py-3 text-gray-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($stations as $station)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- Nom --}}
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $station->name }}</div>
                            @if($station->ocm_id)
                                <div class="text-xs text-gray-400">OCM #{{ $station->ocm_id }}</div>
                            @endif
                        </td>

                        {{-- Ville --}}
                        <td class="px-4 py-3 text-gray-600">
                            {{ $station->city ?? '—' }}
                        </td>

                        {{-- Opérateur --}}
                        <td class="px-4 py-3 text-gray-600">
                            {{ $station->operator_name ?? '—' }}
                        </td>

                        {{-- Connecteurs --}}
                        <td class="px-4 py-3 text-center">
                            <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ $station->connectors_count }}
                            </span>
                        </td>

                        {{-- Avis --}}
                        <td class="px-4 py-3 text-center">
                            <span class="bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ $station->reviews_count }}
                            </span>
                        </td>

                        {{-- Favoris --}}
                        <td class="px-4 py-3 text-center">
                            <span class="bg-pink-50 text-pink-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ $station->favorites_count }}
                            </span>
                        </td>

                        {{-- Statut actif/inactif --}}
                        <td class="px-4 py-3 text-center">
                            @if($station->is_active)
                                <span class="bg-green-50 text-green-700 px-2 py-0.5
                                                     rounded-full text-xs font-medium">
                                    Active
                                </span>
                            @else
                                <span class="bg-red-50 text-red-700 px-2 py-0.5
                                                     rounded-full text-xs font-medium">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">

                                {{-- Voir --}}
                                <a href="{{ route('stations.show', $station) }}"
                                    class="text-gray-400 hover:text-blue-600 transition text-xs" target="_blank">
                                    Voir
                                </a>

                                {{-- Modifier --}}
                                <a href="{{ route('admin.stations.edit', $station) }}"
                                    class="text-gray-400 hover:text-green-600 transition text-xs">
                                    Modifier
                                </a>

                                {{-- Désactiver / Activer --}}
                                <form method="POST" action="{{ route('admin.stations.destroy', $station) }}"
                                    onsubmit="return confirm('Confirmer cette action ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition text-xs">
                                        {{ $station->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            Aucune station trouvée.
                            <a href="{{ route('admin.stations.create') }}" class="text-green-600 hover:underline ml-1">
                                Ajouter la première station
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($stations->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $stations->links() }}
            </div>
        @endif
    </div>

@endsection