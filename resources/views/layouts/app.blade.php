<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ElectroCharge Maroc') }}</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="text-green-600 font-bold text-xl">
            ⚡ ElectroCharge
        </a>

        {{-- Liens navigation --}}
        <div class="flex items-center gap-6 text-sm">
            <a href="{{ route('map') }}" class="text-gray-600 hover:text-green-600 transition">
                Carte
            </a>

            @auth
                {{-- Menu selon le rôle --}}
                @role('admin')
                <a href="{{ route('admin.stations.index') }}" class="text-gray-600 hover:text-green-600 transition">
                    Dashboard Admin
                </a>
                @endrole
                @role('user')
                <a href="{{ route('favorites.index') }}" class="text-gray-600 hover:text-green-600 transition">❤️
                    Favoris</a>
                <a href="{{ route('alerts.index') }}" class="text-gray-600 hover:text-green-600 transition">🔔 Alertes</a>
                <a href="{{ route('history.index') }}" class="text-gray-600 hover:text-green-600 transition">🕐
                    Historique</a>
                @endrole

                @role('operator')
                <a href="{{ route('operator.dashboard') }}" class="text-gray-600 hover:text-green-600 transition">
                    Mon Dashboard
                </a>
                @endrole

                {{-- Nom utilisateur + déconnexion --}}
                <div class="flex items-center gap-3">
                    <span class="text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 transition text-sm">
                            Déconnexion
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-green-600 transition">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                    class="bg-green-600 text-white px-4 py-1.5 rounded-lg hover:bg-green-700 transition text-sm">
                    S'inscrire
                </a>
            @endauth
        </div>
    </nav>

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-6">
            <div class="bg-green-50 border border-green-200 text-green-800
                            rounded-lg px-4 py-3 text-sm flex justify-between">
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" class="text-green-500">✕</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-6">
            <div class="bg-red-50 border border-red-200 text-red-800
                            rounded-lg px-4 py-3 text-sm flex justify-between">
                {{ session('error') }}
                <button onclick="this.parentElement.remove()" class="text-red-500">✕</button>
            </div>
        </div>
    @endif

    {{-- Contenu principal --}}
    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>