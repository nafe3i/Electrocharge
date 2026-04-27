<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Connexion</h1>
        <p class="text-sm text-slate-500 mt-1">Accédez à votre espace ElectroCharge.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                Adresse e-mail
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="exemple@email.com" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm
                          focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-slate-700">
                    Mot de passe
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs font-medium text-green-600 hover:text-green-700 hover:underline">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm
                          focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember"
                    class="rounded border-slate-300 text-green-600 focus:ring-green-500">
                <span>Se souvenir de moi</span>
            </label>
        </div>

        <button type="submit" class="w-full rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white
                       hover:bg-green-700 transition focus:outline-none focus:ring-2
                       focus:ring-green-500 focus:ring-offset-2">
            Se connecter
        </button>

        <p class="text-center text-sm text-slate-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-700 hover:underline">
                Créer un compte
            </a>
        </p>
    </form>
</x-guest-layout>