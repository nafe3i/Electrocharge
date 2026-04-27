<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ElectroCharge Maroc') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen">

    <header class="h-16 bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <span class="w-7 h-7 rounded-lg bg-green-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
                <span class="font-semibold text-slate-800 text-sm tracking-tight">
                    ElectroCharge <span class="text-green-600">Maroc</span>
                </span>
            </a>

            {{-- Liens nav --}}
            <nav class="flex items-center gap-1 text-sm">
                <a href="{{ route('map') }}"
                    class="px-3 py-2 rounded-lg transition
                          {{ request()->routeIs('map') ? 'bg-green-50 text-green-700 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                    Carte
                </a>

                @auth
                    @role('admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-3 py-2 rounded-lg transition
                                      {{ request()->routeIs('admin.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        Administration
                    </a>
                    @endrole

                    @role('operator')
                    <a href="{{ route('operator.dashboard') }}"
                        class="px-3 py-2 rounded-lg transition
                                      {{ request()->routeIs('operator.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        Mon espace
                    </a>
                    @endrole

                    @role('user')
                    <a href="{{ route('favorites.index') }}"
                        class="px-3 py-2 rounded-lg transition
                                      {{ request()->routeIs('favorites.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        Favoris
                    </a>
                    <a href="{{ route('alerts.index') }}"
                        class="px-3 py-2 rounded-lg transition
                                      {{ request()->routeIs('alerts.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        Alertes
                    </a>
                    <a href="{{ route('history.index') }}"
                        class="px-3 py-2 rounded-lg transition
                                      {{ request()->routeIs('history.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        Historique
                    </a>
                    @endrole
                @endauth
            </nav>

            {{-- Zone droite --}}
            <div class="flex items-center gap-2">
                @auth
                    {{-- Cloche notifications --}}
                    <a href="{{ route('notifications.index') }}"
                        class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($unread > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </a>

                    {{-- Menu utilisateur --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg
                                           hover:bg-slate-100 transition text-sm">
                            <span class="w-7 h-7 rounded-full bg-green-100 text-green-700 flex items-center
                                             justify-center font-semibold text-xs flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="font-medium text-slate-700 hidden sm:block max-w-[120px] truncate">
                                {{ auth()->user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white border border-slate-200
                                        rounded-xl shadow-lg py-1 z-50">

                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Mon profil
                            </a>

                            @role('user')
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Tableau de bord
                            </a>
                            @endrole

                            <div class="border-t border-slate-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm
                                                   text-slate-600 hover:bg-slate-50 transition text-left">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 text-sm text-slate-600 hover:text-slate-900 transition">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-green-600 text-white
                                  rounded-lg hover:bg-green-700 transition shadow-sm">
                        S'inscrire
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Flash success --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-6">
            <div class="flex items-center justify-between bg-green-50 border border-green-200
                            text-green-800 rounded-xl px-4 py-3 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
                <button onclick="this.closest('div').parentElement.remove()"
                    class="text-green-400 hover:text-green-600 transition ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Flash error --}}
    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-6">
            <div class="flex items-center justify-between bg-red-50 border border-red-200
                            text-red-800 rounded-xl px-4 py-3 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
                <button onclick="this.closest('div').parentElement.remove()"
                    class="text-red-400 hover:text-red-600 transition ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>