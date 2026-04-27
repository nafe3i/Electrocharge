@extends('layouts.admin')

@section('page-title', 'Gestion des stations')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-base font-semibold text-slate-800">Stations de recharge</h2>
            <p class="text-sm text-slate-500 mt-0.5">{{ $stations->total() }} station(s) au total</p>
        </div>
        <a href="{{ route('admin.stations.create') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2
                  rounded-lg text-sm font-medium hover:bg-green-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter une station
        </a>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('admin.stations.index') }}"
        class="bg-white border border-slate-200 rounded-xl p-4 mb-5 flex flex-wrap gap-3 items-end">

        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-slate-600 mb-1">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, ville, opérateur..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>

        <div class="min-w-36">
            <label class="block text-xs font-medium text-slate-600 mb-1">Ville</label>
            <select name="city" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">Toutes</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                        {{ $city }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="min-w-36">
            <label class="block text-xs font-medium text-slate-600 mb-1">Statut</label>
            <select name="active" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">Tous</option>
                <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm
                           font-medium hover:bg-green-700 transition">
                Filtrer
            </button>
            <a href="{{ route('admin.stations.index') }}" class="border border-slate-200 text-slate-600 px-4 py-2 rounded-lg
                      text-sm hover:bg-slate-50 transition">
                Réinitialiser
            </a>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-slate-500 font-medium">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="hover:text-slate-800 flex items-center gap-1">
                            Nom
                            @if(request('sort') === 'name')
                                <span class="text-xs">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-4 py-3 text-slate-500 font-medium">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'city', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                            class="hover:text-slate-800">
                            Ville
                        </a>
                    </th>
                    <th class="text-left px-4 py-3 text-slate-500 font-medium">Opérateur</th>
                    <th class="text-center px-4 py-3 text-slate-500 font-medium">Connecteurs</th>
                    <th class="text-center px-4 py-3 text-slate-500 font-medium">Avis</th>
                    <th class="text-center px-4 py-3 text-slate-500 font-medium">Favoris</th>
                    <th class="text-center px-4 py-3 text-slate-500 font-medium">Statut</th>
                    <th class="text-right px-4 py-3 text-slate-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($stations as $station)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800">{{ $station->name }}</div>
                            @if($station->ocm_id)
                                <div class="text-xs text-slate-400">OCM #{{ $station->ocm_id }}</div>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-slate-600">{{ $station->city ?? '—' }}</td>

                        <td class="px-4 py-3 text-slate-600">{{ $station->operator_name ?? '—' }}</td>

                        <td class="px-4 py-3 text-center">
                            <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ $station->connectors_count }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ $station->reviews_count }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="bg-rose-50 text-rose-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ $station->favorites_count }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span
                                class="text-xs px-2 py-0.5 rounded-full font-medium
                                             {{ $station->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                {{ $station->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('stations.show', $station) }}" target="_blank"
                                    class="text-xs text-slate-400 hover:text-blue-600 transition">
                                    Voir
                                </a>
                                <a href="{{ route('admin.stations.edit', $station) }}"
                                    class="text-xs text-slate-400 hover:text-green-600 transition">
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('admin.stations.destroy', $station) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-slate-400 hover:text-red-600 transition">
                                        {{ $station->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                            Aucune station trouvée.
                            <a href="{{ route('admin.stations.create') }}" class="text-green-600 hover:underline ml-1">
                                Ajouter la première station
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($stations->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">
                {{ $stations->links() }}
            </div>
        @endif
    </div>

@endsection