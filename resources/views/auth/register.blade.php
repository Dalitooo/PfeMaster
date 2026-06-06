<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte — SmileCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex bg-slate-50">

    {{-- ── Left panel ─────────────────────────────────────────────────────── --}}
    <div class="hidden lg:flex lg:w-5/12 relative bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500 flex-col items-center justify-center p-14 overflow-hidden shrink-0">

        <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-32 -right-20 w-[28rem] h-[28rem] bg-white/5 rounded-full"></div>
        <div class="absolute top-1/3 left-1/2 w-48 h-48 bg-white/5 rounded-full -translate-x-1/2"></div>

        <div class="relative z-10 text-white max-w-xs w-full">
            {{-- Logo --}}
            <div class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shadow-xl">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight">SmileCare</span>
            </div>

            <h2 class="text-3xl font-extrabold mb-3 leading-tight">Votre sourire<br>entre de bonnes mains</h2>
            <p class="text-blue-100 text-sm leading-relaxed mb-10">
                Créez votre compte en quelques secondes et accédez à votre espace santé dentaire personnalisé.
            </p>

            {{-- Steps --}}
            <div class="space-y-3">
                @foreach([
                    ['1', 'Créez votre compte', 'Inscription gratuite et sécurisée'],
                    ['2', 'Choisissez votre médecin', 'Filtrez par spécialité et cabinet'],
                    ['3', 'Prenez rendez-vous', 'Confirmez en ligne, 24h/24'],
                ] as [$n, $title, $sub])
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-3">
                    <div class="w-8 h-8 rounded-xl bg-white/25 flex items-center justify-center shrink-0 text-white font-bold text-xs">{{ $n }}</div>
                    <div>
                        <div class="text-sm font-semibold text-white">{{ $title }}</div>
                        <div class="text-xs text-blue-200">{{ $sub }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Right panel ─────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col items-center justify-start lg:justify-center bg-white overflow-y-auto px-6 py-10">
        <div class="w-full max-w-lg">

            {{-- Mobile logo --}}
            <div class="flex items-center gap-3 mb-8 lg:hidden">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shadow-md shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-slate-800">SmileCare</span>
            </div>

            <div class="mb-7">
                <h2 class="text-2xl font-bold text-slate-800">Créer un compte</h2>
                <p class="text-sm text-slate-500 mt-1">Déjà inscrit ? <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Se connecter</a></p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-700 text-sm flex gap-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <ul class="space-y-0.5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- ── Section : Identité ──────────────────────────────────── --}}
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3">Informations personnelles</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Prénom <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </span>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                                       placeholder="Ahmed"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-slate-50 focus:bg-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </span>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                       placeholder="Trabelsi"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-slate-50 focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse e-mail <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="vous@exemple.com"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-slate-50 focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Téléphone <span class="text-slate-400 font-normal text-xs">(optionnel)</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       placeholder="+216 XX XXX XXX"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-slate-50 focus:bg-white">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section : Sécurité ───────────────────────────────────── --}}
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3">Sécurité</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Mot de passe <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <input type="password" name="password" required
                                       placeholder="••••••••"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-slate-50 focus:bg-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirmation <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </span>
                                <input type="password" name="password_confirmation" required
                                       placeholder="••••••••"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-slate-50 focus:bg-white">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section : CNAM ───────────────────────────────────────── --}}
                <div class="rounded-2xl border border-violet-200 bg-gradient-to-br from-violet-50 to-purple-50 p-4">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-violet-900">Informations CNAM</span>
                            <span class="ml-2 text-xs text-violet-400 font-normal">optionnel</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Identifiant unique</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                                </span>
                                <input type="text" name="cnam_id" value="{{ old('cnam_id') }}"
                                       placeholder="ex : 12345678"
                                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-violet-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition bg-white">
                            </div>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Type d'assurance</label>
                            <div class="flex gap-2 h-[42px]">
                                <label class="flex-1 flex items-center justify-center gap-2 cursor-pointer rounded-xl border-2 transition-all px-3
                                    {{ old('cnam_type') === 'cnss' ? 'border-violet-500 bg-violet-100 text-violet-700' : 'border-slate-200 bg-white text-slate-600 hover:border-violet-300' }}">
                                    <input type="radio" name="cnam_type" value="cnss"
                                           {{ old('cnam_type') === 'cnss' ? 'checked' : '' }}
                                           class="sr-only">
                                    <span class="text-sm font-semibold">CNSS</span>
                                </label>
                                <label class="flex-1 flex items-center justify-center gap-2 cursor-pointer rounded-xl border-2 transition-all px-3
                                    {{ old('cnam_type') === 'cnrps' ? 'border-violet-500 bg-violet-100 text-violet-700' : 'border-slate-200 bg-white text-slate-600 hover:border-violet-300' }}">
                                    <input type="radio" name="cnam_type" value="cnrps"
                                           {{ old('cnam_type') === 'cnrps' ? 'checked' : '' }}
                                           class="sr-only">
                                    <span class="text-sm font-semibold">CNRPS</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Créer mon compte
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-400">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Se connecter</a>
            </p>
        </div>
    </div>

<script>
document.querySelectorAll('input[name="cnam_type"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('input[name="cnam_type"]').forEach(r => {
            const lbl = r.closest('label');
            lbl.classList.toggle('border-violet-500', r.checked);
            lbl.classList.toggle('bg-violet-100', r.checked);
            lbl.classList.toggle('text-violet-700', r.checked);
            lbl.classList.toggle('border-slate-200', !r.checked);
            lbl.classList.toggle('bg-white', !r.checked);
            lbl.classList.toggle('text-slate-600', !r.checked);
        });
    });
});
</script>
</body>
</html>
