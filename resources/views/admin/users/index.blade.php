@extends('layouts.admin')

@section('page-title', 'Gestion des utilisateurs')

@section('content')

    <div class="mb-6">
        <h2 class="text-base font-semibold text-slate-800">Utilisateurs</h2>
        <p class="text-sm text-slate-500 mt-0.5">{{ $users->total() }} utilisateur(s) au total</p>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}"
        class="bg-white border border-slate-200 rounded-xl p-4 mb-5 flex flex-wrap gap-3 items-end">

        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-slate-600 mb-1">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou email..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>

        <div class="min-w-36">
            <label class="block text-xs font-medium text-slate-600 mb-1">Rôle</label>
            <select name="role" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">Tous</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                Filtrer
            </button>
            <a href="{{ route('admin.users.index') }}"
                class="border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm hover:bg-slate-50 transition">
                Réinitialiser
            </a>
        </div>
    </form>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-4 py-3 text-slate-500 font-medium">Utilisateur</th>
                    <th class="text-left px-4 py-3 text-slate-500 font-medium">Email</th>
                    <th class="text-center px-4 py-3 text-slate-500 font-medium">Rôle</th>
                    <th class="text-center px-4 py-3 text-slate-500 font-medium">Inscrit le</th>
                    <th class="text-right px-4 py-3 text-slate-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center
                                                justify-center text-green-700 font-semibold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-slate-800">{{ $user->name }}</span>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>

                        <td class="px-4 py-3 text-center">
                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}">
                                @csrf
                                @method('PATCH')
                                <select name="role" onchange="this.form.submit()" class="border border-slate-200 rounded-lg px-2 py-1 text-xs bg-white
                                                   focus:outline-none focus:ring-2 focus:ring-green-500">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>

                        <td class="px-4 py-3 text-center text-slate-400 text-xs">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3 text-right">
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs transition">
                                        Supprimer
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-300 text-xs">Vous</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-slate-400">
                            Aucun utilisateur trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

@endsection