<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileCare — Clinique Dentaire Moderne</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 antialiased font-sans">

{{-- ── Navbar ──────────────────────────────────────────────────────────────── --}}
<header x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="font-bold text-slate-800 text-lg">SmileCare</span>
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
            <a href="#services" class="hover:text-blue-600 transition-colors">Services</a>
            <a href="#why-us"   class="hover:text-blue-600 transition-colors">Pourquoi nous</a>
            <a href="#team"     class="hover:text-blue-600 transition-colors">Notre équipe</a>
            <a href="#contact"  class="hover:text-blue-600 transition-colors">Contact</a>
        </nav>

        <div class="hidden md:flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                    Tableau de bord
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                    Se connecter
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                    Prendre RDV
                </a>
            @endauth
        </div>

        <button @click="open = !open" class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-50">
            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="open"  class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div x-show="open" x-cloak class="md:hidden border-t border-slate-100 bg-white px-6 py-4 space-y-3 text-sm font-medium text-slate-700">
        <a href="#services" @click="open=false" class="block py-1 hover:text-blue-600">Services</a>
        <a href="#why-us"   @click="open=false" class="block py-1 hover:text-blue-600">Pourquoi nous</a>
        <a href="#team"     @click="open=false" class="block py-1 hover:text-blue-600">Notre équipe</a>
        <a href="#contact"  @click="open=false" class="block py-1 hover:text-blue-600">Contact</a>
        <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="block text-center px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold">Tableau de bord</a>
            @else
                <a href="{{ route('login') }}"    class="block text-center px-4 py-2 rounded-lg border border-slate-200 text-slate-700">Se connecter</a>
                <a href="{{ route('register') }}" class="block text-center px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold">Prendre RDV</a>
            @endauth
        </div>
    </div>
</header>

{{-- ── Hero ─────────────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50 pt-20 pb-28">
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-blue-100 rounded-full opacity-40 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 -left-24 w-72 h-72 bg-indigo-100 rounded-full opacity-40 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-6 flex flex-col lg:flex-row items-center gap-16">
        <div class="flex-1 text-center lg:text-left">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                Clinique dentaire moderne en Tunisie
            </span>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-6">
                Votre sourire mérite<br>
                <span class="text-blue-600">des soins d'excellence</span>
            </h1>
            <p class="text-slate-500 text-lg leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                SmileCare réunit des dentistes expérimentés, une technologie moderne et une approche centrée sur le patient pour offrir des soins dentaires exceptionnels à toute la famille.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200 text-center">
                        Tableau de bord
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200 text-center">
                        Prendre un rendez-vous
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-6 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold hover:border-blue-300 hover:text-blue-600 transition-colors text-center">
                        Espace patient
                    </a>
                @endauth
            </div>

            <div class="mt-10 flex flex-wrap justify-center lg:justify-start gap-6 text-sm text-slate-500">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span>Spécialistes certifiés</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span>Équipements modernes</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span>Horaires flexibles</span>
                </div>
            </div>
        </div>

        <div class="flex-1 flex justify-center">
            <div class="relative w-80 h-80 lg:w-96 lg:h-96">
                <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-2xl shadow-blue-300 flex items-center justify-center">
                    <svg class="w-40 h-40 text-white opacity-30" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.5 2 6 4.5 6 7c0 1.5.5 3 1.5 4L6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2l-1.5-8c1-.9 1.5-2.5 1.5-4C18 4.5 15.5 2 12 2z"/>
                    </svg>
                </div>
                <div class="absolute -top-4 -left-4 bg-white rounded-2xl shadow-xl p-3.5 flex items-center gap-3 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-slate-800 leading-none">2 500+</div>
                        <div class="text-xs text-slate-500 mt-0.5">Patients satisfaits</div>
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 bg-white rounded-2xl shadow-xl p-3.5 flex items-center gap-3 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-slate-800 leading-none">15+ ans</div>
                        <div class="text-xs text-slate-500 mt-0.5">D'expérience</div>
                    </div>
                </div>
                <div class="absolute top-1/2 -right-8 -translate-y-1/2 bg-white rounded-2xl shadow-xl p-3.5 border border-slate-100">
                    <div class="flex items-center gap-1 mb-1">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <div class="text-xs font-semibold text-slate-700">4,9 / 5,0</div>
                    <div class="text-xs text-slate-400">Note patients</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Stats bar ────────────────────────────────────────────────────────────── --}}
<section class="bg-blue-600 py-10">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach([['2 500+','Patients traités'],['15+','Ans d\'expérience'],['6','Spécialistes'],['98%','Taux de satisfaction']] as [$num,$label])
            <div>
                <div class="text-3xl font-extrabold text-white">{{ $num }}</div>
                <div class="text-blue-200 text-sm mt-1">{{ $label }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── Services ─────────────────────────────────────────────────────────────── --}}
<section id="services" class="py-24 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-blue-600 text-sm font-semibold uppercase tracking-widest">Ce que nous proposons</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mt-2">Des soins dentaires complets</h2>
            <p class="text-slate-500 mt-3 max-w-xl mx-auto">Du détartrage de routine aux restaurations complexes, nous offrons une gamme complète de traitements dentaires sous un même toit.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['#3B82F6','M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z','Dentisterie restauratrice','Couronnes, obturations, bridges et implants pour restaurer la fonction et l\'esthétique.'],
                ['#8B5CF6','M4.5 12.375a8 8 0 1015 0m-15 0a8 8 0 1115 0M12 4v.01M12 8v.01','Orthodontie','Appareils dentaires et aligneurs transparents pour un sourire parfaitement aligné.'],
                ['#10B981','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','Soins préventifs','Détartrage, radiographies, fluoration et scellants pour prévenir les problèmes.'],
                ['#EF4444','M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z','Chirurgie orale','Extractions, dents de sagesse et interventions chirurgicales réalisées en toute sécurité.'],
                ['#F59E0B','M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z','Dentisterie esthétique','Blanchiment, facettes et relooking du sourire pour renforcer votre confiance.'],
                ['#06B6D4','M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z','Parodontologie','Traitement des maladies des gencives et détartrage profond pour une bouche saine.'],
            ] as [$color,$path,$title,$desc])
                <div class="bg-white rounded-2xl p-6 border border-slate-100 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-50 transition-all group">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color: {{ $color }}20;">
                        <svg class="w-6 h-6" style="color: {{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">{{ $title }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Why Us ───────────────────────────────────────────────────────────────── --}}
<section id="why-us" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6 flex flex-col lg:flex-row items-center gap-16">
        <div class="flex-1 grid grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white">
                <div class="text-4xl font-extrabold mb-1">15+</div>
                <div class="text-blue-200 text-sm">Années<br>d'excellence</div>
            </div>
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 mt-6">
                <div class="text-4xl font-extrabold text-slate-800 mb-1">6</div>
                <div class="text-slate-500 text-sm">Dentistes<br>experts</div>
            </div>
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <div class="text-4xl font-extrabold text-slate-800 mb-1">18</div>
                <div class="text-slate-500 text-sm">Traitements<br>dentaires</div>
            </div>
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white mt-6">
                <div class="text-4xl font-extrabold mb-1">98%</div>
                <div class="text-indigo-200 text-sm">Satisfaction<br>patients</div>
            </div>
        </div>

        <div class="flex-1">
            <span class="text-blue-600 text-sm font-semibold uppercase tracking-widest">Pourquoi choisir SmileCare</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mt-2 mb-6">Votre confort est notre priorité</h2>
            <div class="space-y-5">
                @foreach([
                    ['Technologie de pointe','Radiographies numériques, scanner 3D et dentisterie laser pour des traitements précis et confortables.'],
                    ['Spécialistes expérimentés','Notre équipe détient des certifications avancées dans toutes les grandes spécialités dentaires.'],
                    ['Tarifs transparents','Plans de traitement clairs avec factures détaillées — aucuns frais cachés.'],
                    ['Planning flexible','Réservation en ligne, rendez-vous en soirée et le week-end disponibles.'],
                ] as [$title,$desc])
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800">{{ $title }}</div>
                            <div class="text-slate-500 text-sm mt-0.5">{{ $desc }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── Team ─────────────────────────────────────────────────────────────────── --}}
<section id="team" class="py-24 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-blue-600 text-sm font-semibold uppercase tracking-widest">Nos spécialistes</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mt-2">Rencontrez notre équipe</h2>
            <p class="text-slate-500 mt-3 max-w-xl mx-auto">Nos dentistes cumulent des décennies d'expérience combinée et une véritable passion pour le soin des patients.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach([
                ['Dr. Amel Karoui','Dentisterie générale','amel.karoui@smilecare.tn'],
                ['Dr. Mohamed Slim','Orthodontie','mohamed.slim@smilecare.tn'],
                ['Dr. Sonia Gharbi','Chirurgie orale','sonia.gharbi@smilecare.tn'],
            ] as [$name,$spec,$email])
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg hover:shadow-blue-50 transition-all text-center p-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=2563EB&color=fff&size=96"
                         alt="{{ $name }}"
                         class="w-20 h-20 rounded-2xl mx-auto mb-4 object-cover">
                    <h3 class="font-bold text-slate-800">{{ $name }}</h3>
                    <p class="text-blue-600 text-sm font-medium mt-0.5">{{ $spec }}</p>
                    <p class="text-slate-400 text-xs mt-1">{{ $email }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ──────────────────────────────────────────────────────────────────── --}}
<section class="py-24 bg-gradient-to-r from-blue-600 to-indigo-600">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-4">Prêt pour un sourire en bonne santé ?</h2>
        <p class="text-blue-100 text-lg mb-8">Prenez rendez-vous dès aujourd'hui et faites le premier pas vers des soins dentaires d'exception.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ route('appointments.create') }}"
                   class="px-8 py-3.5 rounded-xl bg-white text-blue-600 font-bold hover:bg-blue-50 transition-colors shadow-lg">
                    Prendre un rendez-vous
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="px-8 py-3.5 rounded-xl bg-white text-blue-600 font-bold hover:bg-blue-50 transition-colors shadow-lg">
                    Créer un compte patient
                </a>
                <a href="{{ route('login') }}"
                   class="px-8 py-3.5 rounded-xl border-2 border-white/40 text-white font-bold hover:bg-white/10 transition-colors">
                    Se connecter
                </a>
            @endauth
        </div>
    </div>
</section>

{{-- ── Contact ──────────────────────────────────────────────────────────────── --}}
<section id="contact" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <div class="font-semibold text-slate-800 mb-1">Adresse</div>
                <div class="text-slate-500 text-sm">12 Avenue Habib Bourguiba<br>Tunis, 1001 Tunisie</div>
            </div>
        </div>
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <div>
                <div class="font-semibold text-slate-800 mb-1">Téléphone</div>
                <div class="text-slate-500 text-sm">+216 71 100 200<br>Lun–Ven, 8h–18h</div>
            </div>
        </div>
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="font-semibold text-slate-800 mb-1">E-mail</div>
                <div class="text-slate-500 text-sm">contact@smilecare.tn<br>Réponse sous 24 heures</div>
            </div>
        </div>
    </div>
</section>

{{-- ── Footer ───────────────────────────────────────────────────────────────── --}}
<footer class="bg-slate-900 text-slate-400 py-10">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="text-white font-semibold">SmileCare</span>
        </div>
        <div>&copy; {{ date('Y') }} SmileCare Clinique Dentaire. Tous droits réservés.</div>
        <div class="flex gap-5">
            <a href="{{ route('login') }}"    class="hover:text-white transition-colors">Connexion</a>
            <a href="{{ route('register') }}" class="hover:text-white transition-colors">Inscription</a>
        </div>
    </div>
</footer>

</body>
</html>
