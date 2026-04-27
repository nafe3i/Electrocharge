<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Créer un compte</h1>
        <p class="text-sm text-slate-500 mt-1">Rejoignez ElectroCharge Maroc gratuitement.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nom complet</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm
                          focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none">
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm
                          focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm
                          focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                Confirmer le mot de passe
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm
                          focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit" class="w-full rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white
                       hover:bg-green-700 transition focus:outline-none focus:ring-2
                       focus:ring-green-500 focus:ring-offset-2">
            Créer mon compte
        </button>

        <p class="text-center text-sm text-slate-500">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-700 hover:underline">
                Se connecter
            </a>
        </p>
    </form>
</x-guest-layout>