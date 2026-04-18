<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectroCharge Maroc — Stations de recharge VE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900">

    {{-- Navbar --}}
    <nav class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
        <span class="text-green-600 font-bold text-xl">⚡ ElectroCharge</span>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('map') }}" class="text-gray-600 hover:text-green-600">Carte</a>
            @auth
                <a href="{{ route('home') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    Mon espace
                </a>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-green-600">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    S'inscrire
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-5xl mx-auto px-6 py-20 text-center">
        <div class="inline-block bg-green-50 text-green-700 text-sm px-4 py-1.5
                    rounded-full mb-6 font-medium">
            Mobilité électrique au Maroc
        </div>
        <h1 class="text-5xl font-bold text-gray-900 leading-tight mb-6">
            Trouvez une borne de recharge<br>
            <span class="text-green-600">où que vous soyez</span>
        </h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
            ElectroCharge centralise toutes les stations de recharge du Maroc.
            Consultez les disponibilités en temps réel, comparez les prix
            et planifiez vos trajets sereinement.
        </p>
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <a href="{{ route('map') }}"
               class="bg-green-600 text-white px-8 py-3.5 rounded-xl text-base
                      font-medium hover:bg-green-700 transition">
                Explorer la carte
            </a>
            <a href="{{ route('register') }}"
               class="border border-gray-200 text-gray-700 px-8 py-3.5 rounded-xl
                      text-base font-medium hover:bg-gray-50 transition">
                Créer un compte
            </a>
        </div>
    </section>

    {{-- Stats --}}
    <section class="bg-gray-50 border-y border-gray-100 py-12">
        <div class="max-w-4xl mx-auto px-6 grid grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold text-green-600 mb-1">
                    {{ \App\Models\Station::where('is_active', true)->count() }}+
                </div>
                <div class="text-sm text-gray-500">Stations répertoriées</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-green-600 mb-1">
                    {{ \App\Models\Station::where('is_active', true)->distinct('city')->count('city') }}+
                </div>
                <div class="text-sm text-gray-500">Villes couvertes</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-green-600 mb-1">
                    {{ \App\Models\Connector::distinct('type')->count('type') }}
                </div>
                <div class="text-sm text-gray-500">Types de connecteurs</div>
            </div>
        </div>
    </section>

    {{-- Fonctionnalités --}}
    <section class="max-w-5xl mx-auto px-6 py-20">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
            Tout ce dont vous avez besoin
        </h2>
        <p class="text-center text-gray-500 mb-14">
            Une plateforme pensée pour les conducteurs de véhicules électriques au Maroc
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center
                            justify-center text-2xl mb-4">🗺️</div>
                <h3 class="font-semibold text-gray-800 mb-2">Carte interactive</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Visualisez toutes les stations sur une carte. Filtrez par type
                    de connecteur, puissance et disponibilité.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center
                            justify-center text-2xl mb-4">⚡</div>
                <h3 class="font-semibold text-gray-800 mb-2">Statuts en temps réel</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Sachez avant de partir si une borne est libre, occupée
                    ou hors service. Mise à jour toutes les 15 minutes.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center
                            justify-center text-2xl mb-4">🔔</div>
                <h3 class="font-semibold text-gray-800 mb-2">Alertes personnalisées</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Recevez une alerte quand une station devient disponible
                    ou quand le prix baisse sous votre seuil.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center
                            justify-center text-2xl mb-4">❤️</div>
                <h3 class="font-semibold text-gray-800 mb-2">Favoris</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Sauvegardez vos stations préférées pour y accéder
                    rapidement à tout moment.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center
                            justify-center text-2xl mb-4">💬</div>
                <h3 class="font-semibold text-gray-800 mb-2">Avis communautaires</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Consultez les avis d'autres conducteurs et partagez
                    votre expérience sur chaque station.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center
                            justify-center text-2xl mb-4">📊</div>
                <h3 class="font-semibold text-gray-800 mb-2">Comparaison des prix</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Comparez les tarifs en MAD/kWh entre les différentes
                    stations avant de vous déplacer.
                </p>
            </div>
        </div>
    </section>

    {{-- CTA final --}}
    <section class="bg-green-600 py-16">
        <div class="max-w-2xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                Prêt à recharger malin ?
            </h2>
            <p class="text-green-100 mb-8">
                Rejoignez la communauté ElectroCharge et simplifiez vos trajets.
            </p>
            <a href="{{ route('register') }}"
               class="bg-white text-green-700 px-8 py-3.5 rounded-xl font-medium
                      hover:bg-green-50 transition inline-block">
                Créer mon compte gratuitement
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-8 text-center text-sm text-gray-400">
        © {{ date('Y') }} ElectroCharge Maroc — Données fournies par Open Charge Map
    </footer>

</body>
</html>