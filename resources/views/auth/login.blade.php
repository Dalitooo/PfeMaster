<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — SmileCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex">

    {{-- ── Left panel ─────────────────────────────────────────────────────── --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500 flex-col items-center justify-center p-16 overflow-hidden">

        {{-- Decorative circles --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-32 -right-20 w-[28rem] h-[28rem] bg-white/5 rounded-full"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2"></div>

        <div class="relative z-10 text-center text-white max-w-md">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white/20 backdrop-blur mb-8 shadow-2xl">
                <svg class="w-11 h-11 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
            </div>

            <h1 class="text-4xl font-extrabold mb-4 leading-tight">SmileCare</h1>
            <p class="text-xl font-light text-blue-100 mb-12 leading-relaxed">
                Gérez votre clinique dentaire<br>avec simplicité et efficacité.
            </p>

            <div class="grid grid-cols-1 gap-4 text-left">
                <div class="flex items-center gap-4 bg-white/10 backdrop-blur rounded-2xl p-4">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-white">Rendez-vous en ligne</div>
                        <div class="text-xs text-blue-200">Prise de RDV simple et rapide</div>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white/10 backdrop-blur rounded-2xl p-4">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-white">Dossiers médicaux</div>
                        <div class="text-xs text-blue-200">Historique complet des soins</div>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white/10 backdrop-blur rounded-2xl p-4">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-white">Facturation intégrée</div>
                        <div class="text-xs text-blue-200">Factures générées automatiquement</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Right panel ─────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col items-center justify-center bg-white px-8 py-12">
        <div class="w-full max-w-sm">

            {{-- Mobile logo --}}
            <div class="flex flex-col items-center mb-8 lg:hidden">
                <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-3 shadow-lg shadow-blue-200">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-800">SmileCare</h1>
            </div>

            <h2 class="text-2xl font-bold text-slate-800 mb-1">Se connecter</h2>
            <p class="text-sm text-slate-500 mb-8">Bienvenue ! Veuillez entrer vos identifiants.</p>

            @if (session('status'))
                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse e-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="vous@exemple.com"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-slate-700">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Mot de passe oublié ?</a>
                        @endif
                    </div>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="remember" class="text-sm text-slate-600">Se souvenir de moi</label>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors shadow-lg shadow-blue-100">
                    Se connecter
                </button>
            </form>

            @if (Route::has('register'))
                <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500 mb-3">Pas encore de compte ?</p>
                    <a href="{{ route('register') }}"
                       class="w-full inline-block text-center py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-700 text-sm font-semibold hover:border-blue-400 hover:text-blue-600 transition-colors">
                        Créer un compte
                    </a>
                </div>
            @endif
        </div>
    </div>

</body>
</html>
