<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectroCharge Maroc — Stations de recharge VE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-slate-900 antialiased">

    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-green-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
                <span class="font-semibold text-slate-800 text-sm">
                    ElectroCharge <span class="text-green-600">Maroc</span>
                </span>
            </a>

            <nav class="flex items-center gap-1 text-sm">
                <a href="{{ route('map') }}"
                    class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">Carte</a>
                @auth
                    @role('admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">Administration</a>
                    @endrole
                    @role('operator')
                    <a href="{{ route('operator.dashboard') }}"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">Mon espace</a>
                    @endrole
                    @role('user')
                    <a href="{{ route('dashboard') }}"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">Mon espace</a>
                    @endrole
                @else
                    <a href="{{ route('login') }}"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900 transition">Connexion</a>
                    <a href="{{ route('register') }}"
                        class="ml-1 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">S'inscrire</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="max-w-5xl mx-auto px-6 pt-20 pb-16 text-center">
        <div class="inline-flex items-center gap-2 bg-green-50 text-green-700 text-xs font-semibold
                    px-3 py-1.5 rounded-full mb-8 border border-green-200 uppercase tracking-wide">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            Mobilité électrique au Maroc
        </div>

        <h1 class="text-5xl font-bold text-slate-900 leading-tight mb-6 tracking-tight">
            Trouvez une borne de recharge<br>
            <span class="text-green-600">où que vous soyez</span>
        </h1>

        <p class="text-lg text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
            ElectroCharge centralise toutes les stations de recharge du Maroc.
            Consultez les disponibilités en temps réel, comparez les prix
            et planifiez vos trajets sereinement.
        </p>

        <div class="flex items-center justify-center gap-3 flex-wrap">
            <a href="{{ route('map') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-7 py-3.5
                      rounded-xl text-sm font-semibold hover:bg-green-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                Explorer la carte
            </a>
            @guest
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 border border-slate-200 text-slate-700
                              px-7 py-3.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                    Créer un compte gratuit
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 border border-slate-200 text-slate-700
                              px-7 py-3.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                    Mon espace
                </a>
            @endguest
        </div>
    </section>

    {{-- Stats --}}
    <section class="border-y border-slate-100 bg-slate-50 py-12">
        <div class="max-w-3xl mx-auto px-6 grid grid-cols-3 divide-x divide-slate-200 text-center">
            <div class="px-8">
                <div class="text-4xl font-bold text-green-600 mb-1 tabular-nums">{{ $stationsCount }}+</div>
                <div class="text-sm text-slate-500 font-medium">Stations répertoriées</div>
            </div>
            <div class="px-8">
                <div class="text-4xl font-bold text-green-600 mb-1 tabular-nums">{{ $citiesCount }}+</div>
                <div class="text-sm text-slate-500 font-medium">Villes couvertes</div>
            </div>
            <div class="px-8">
                <div class="text-4xl font-bold text-green-600 mb-1 tabular-nums">{{ $connectorsCount }}</div>
                <div class="text-sm text-slate-500 font-medium">Types de connecteurs</div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="max-w-5xl mx-auto px-6 py-20">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-slate-900 mb-3 tracking-tight">Tout ce dont vous avez besoin</h2>
            <p class="text-slate-500 max-w-xl mx-auto text-base">
                Une plateforme pensée pour les conducteurs de véhicules électriques au Maroc
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div
                class="bg-white border border-slate-100 rounded-2xl p-6 hover:border-slate-200 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm">Carte interactive</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Visualisez toutes les stations. Filtrez par type de
                    connecteur, puissance et disponibilité.</p>
            </div>

            <div
                class="bg-white border border-slate-100 rounded-2xl p-6 hover:border-slate-200 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm">Statuts en temps réel</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Sachez avant de partir si une borne est libre, occupée
                    ou hors service.</p>
            </div>

            <div
                class="bg-white border border-slate-100 rounded-2xl p-6 hover:border-slate-200 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm">Alertes personnalisées</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Recevez une notification quand une station devient
                    disponible ou quand le prix baisse.</p>
            </div>

            <div
                class="bg-white border border-slate-100 rounded-2xl p-6 hover:border-slate-200 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm">Stations favorites</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Sauvegardez vos stations préférées pour y accéder
                    rapidement à tout moment.</p>
            </div>

            <div
                class="bg-white border border-slate-100 rounded-2xl p-6 hover:border-slate-200 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm">Avis communautaires</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Consultez les avis d'autres conducteurs et partagez
                    votre expérience.</p>
            </div>

            <div
                class="bg-white border border-slate-100 rounded-2xl p-6 hover:border-slate-200 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm">Comparaison des prix</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Comparez les tarifs en MAD/kWh entre les différentes
                    stations avant de vous déplacer.</p>
            </div>

        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-gradient-to-br from-green-600 to-emerald-700 py-20">
        <div class="max-w-2xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-4 tracking-tight">Prêt à recharger malin ?</h2>
            <p class="text-green-100 mb-8 text-base">Rejoignez la communauté ElectroCharge et simplifiez vos trajets au
                Maroc.</p>
            @guest
                <a href="{{ route('register') }}"
                    class="inline-block bg-white text-green-700 px-8 py-3.5 rounded-xl font-semibold text-sm hover:bg-green-50 transition shadow-sm">
                    Créer mon compte gratuitement
                </a>
            @else
                <a href="{{ route('map') }}"
                    class="inline-block bg-white text-green-700 px-8 py-3.5 rounded-xl font-semibold text-sm hover:bg-green-50 transition shadow-sm">
                    Explorer la carte
                </a>
            @endguest
        </div>
    </section>

    <footer class="border-t border-slate-100 py-8">
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between text-sm text-slate-400">
            <span class="font-medium text-slate-500">ElectroCharge <span class="text-green-600">Maroc</span></span>
            <span>&copy; {{ date('Y') }} &mdash; Données fournies par Open Charge Map</span>
        </div>
    </footer>

</body>

</html>