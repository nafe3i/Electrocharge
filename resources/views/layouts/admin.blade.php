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

<body class="bg-slate-100 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-60 bg-white border-r border-slate-200 flex flex-col min-h-screen flex-shrink-0">

        {{-- Logo --}}
        <div class="px-5 py-4 border-b border-slate-200">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-green-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-semibold text-slate-800 leading-none">ElectroCharge</p>
                    <p class="text-xs text-slate-400 mt-0.5">Administration</p>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 text-sm">

            @php
                $navItems = [
                    [
                        'route' => 'admin.dashboard',
                        'match' => 'admin.dashboard',
                        'label' => 'Vue d\'ensemble',
                        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
                    ],
                    [
                        'route' => 'admin.stations.index',
                        'match' => 'admin.stations.*',
                        'label' => 'Stations',
                        'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'
                    ],
                    [
                        'route' => 'admin.users.index',
                        'match' => 'admin.users.*',
                        'label' => 'Utilisateurs',
                        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
                    ],
                    [
                        'route' => 'admin.reviews.index',
                        'match' => 'admin.reviews.*',
                        'label' => 'Avis',
                        'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'
                    ],
                    [
                        'route' => 'admin.stats.index',
                        'match' => 'admin.stats.*',
                        'label' => 'Statistiques',
                        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
                    ],
                    [
                        'route' => 'admin.logs.index',
                        'match' => 'admin.logs.*',
                        'label' => 'Journal',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
                    ],
                ];
            @endphp

            @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                                          {{ request()->routeIs($item['match'])
                ? 'bg-green-50 text-green-700 font-medium'
                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}" />
                        </svg>
                        {{ $item['label'] }}
                    </a>
            @endforeach

        </nav>

        {{-- Footer sidebar --}}
        <div class="px-4 py-4 border-t border-slate-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center
                            text-green-700 font-semibold text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-700 truncate leading-none">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">Administrateur</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 text-sm text-slate-500
                               hover:text-slate-700 px-2 py-1.5 rounded-lg hover:bg-slate-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenu principal --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">

        <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-base font-semibold text-slate-800">
                @yield('page-title', 'Dashboard')
            </h1>
            <span class="text-xs text-slate-400 font-medium">
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </header>

        @if(session('success'))
            <div class="mx-8 mt-4">
                <div class="flex items-center justify-between bg-green-50 border border-green-200
                                    text-green-800 rounded-xl px-4 py-3 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ session('success') }}
                    </div>
                    <button onclick="this.closest('div').parentElement.remove()"
                        class="text-green-400 hover:text-green-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-8 mt-4">
                <div class="flex items-center justify-between bg-red-50 border border-red-200
                                    text-red-800 rounded-xl px-4 py-3 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                    <button onclick="this.closest('div').parentElement.remove()"
                        class="text-red-400 hover:text-red-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <main class="flex-1 px-8 py-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>

</html>