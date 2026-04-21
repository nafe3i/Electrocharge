<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name', 'ElectroCharge') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>

<body class="bg-gray-100 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col min-h-screen flex-shrink-0">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-gray-200">
            <a href="{{ route('home') }}" class="text-green-600 font-bold text-lg">
                ⚡ ElectroCharge
            </a>
            <p class="text-xs text-gray-400 mt-0.5">Dashboard Admin</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 text-sm">

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg
                      {{ request()->routeIs('admin.dashboard') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Vue d'ensemble
            </a>

            <a href="{{ route('admin.stations.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg
                      {{ request()->routeIs('admin.stations.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Stations
            </a>

            <!-- {{-- Ces routes seront créées plus tard --}}
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 ">
                <span>👥</span> Utilisateurs
            </a>

            <a href="{{ route('admin.reviews.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 ">
                <span>💬</span> Avis
            </a>

            <a href="" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 cursor-not-allowed">
                <span>📈</span> Statistiques
            </a>

            <a href="{{ route('admin.logs.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 cursor-not-allowed">
                <span>📋</span> Logs
            </a> -->

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg
          {{ request()->routeIs('admin.users.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Utilisateurs
            </a>

            <a href="{{ route('admin.reviews.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg
          {{ request()->routeIs('admin.reviews.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Avis
            </a>

            <a href="{{ route('admin.logs.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg
          {{ request()->routeIs('admin.logs.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Logs
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 cursor-not-allowed">
                Statistiques
            </a>

        </nav>

        {{-- Footer sidebar --}}
        <div class="px-4 py-4 border-t border-gray-200">
            <div class="flex items-center gap-3 mb-3">
                {{-- Avatar initiales --}}
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center
                            justify-content text-green-700 font-medium text-sm
                            items-center justify-center">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 leading-none">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">Administrateur</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-sm text-red-500 hover:text-red-700
                               px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenu principal --}}
    <div class="flex-1 flex flex-col min-h-screen">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
            <h1 class="text-lg font-medium text-gray-800">
                @yield('page-title', 'Dashboard')
            </h1>
            <div class="text-sm text-gray-400">
                {{ now()->format('d/m/Y') }}
            </div>
        </header>

        {{-- Messages flash --}}
        @if(session('success'))
            <div class="mx-8 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-800
                                                rounded-lg px-4 py-3 text-sm flex justify-between">
                    {{ session('success') }}
                    <button onclick="this.parentElement.remove()">✕</button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-8 mt-4">
                <div class="bg-red-50 border border-red-200 text-red-800
                                                rounded-lg px-4 py-3 text-sm flex justify-between">
                    {{ session('error') }}
                    <button onclick="this.parentElement.remove()">✕</button>
                </div>
            </div>
        @endif

        {{-- Contenu --}}
        <main class="flex-1 px-8 py-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>