@php
    $user = auth()->user();
    $role = $user->role;
@endphp

<aside id="sidebar" class="hidden lg:flex flex-col w-64 bg-white border-r border-slate-200 shrink-0">
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <div class="font-bold text-slate-800 text-sm leading-tight">SmileCare</div>
            <div class="text-xs text-slate-400">Cabinet dentaire</div>
        </div>
    </div>

    {{-- User badge --}}
    <div class="px-4 py-3 mx-3 mt-3 rounded-xl bg-blue-50 border border-blue-100">
        <div class="flex items-center gap-2">
            <img src="{{ $user->getAvatarUrl() }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
            <div class="min-w-0">
                <div class="text-sm font-semibold text-slate-800 truncate">{{ $user->name }}</div>
                <div class="text-xs text-blue-600">{{ $user->getRoleLabel() }}</div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        @php
            $navItem = function(string $href, string $icon, string $label, string $activePattern = '') use (&$navItem) {
                $active = $activePattern ? request()->routeIs($activePattern) : request()->is(ltrim(parse_url($href, PHP_URL_PATH), '/'));
                $cls = $active
                    ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white'
                    : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900';
                return "<a href=\"$href\" class=\"$cls\">$icon $label</a>";
            };
        @endphp

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Tableau de bord
        </a>

        {{-- Patients section --}}
        @if(in_array($role, ['super_admin', 'admin', 'doctor', 'secretary']))
            <div class="pt-3 pb-1 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Patients</div>

            <a href="{{ route('patients.index') }}"
               class="{{ request()->routeIs('patients.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Patients
            </a>
        @endif

        {{-- Appointments section --}}
        @if(in_array($role, ['super_admin', 'admin', 'doctor', 'secretary', 'patient']))
            <div class="pt-3 pb-1 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Planning</div>

            <a href="{{ route('appointments.index') }}"
               class="{{ request()->routeIs('appointments.index') || request()->routeIs('appointments.show') || request()->routeIs('appointments.create') || request()->routeIs('appointments.edit') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Rendez-vous
            </a>

            <a href="{{ route('appointments.calendar') }}"
               class="{{ request()->routeIs('appointments.calendar') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Calendrier
            </a>
        @endif

        {{-- Treatments --}}
        @if(in_array($role, ['super_admin', 'admin', 'doctor', 'secretary', 'patient']))
            <div class="pt-3 pb-1 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Clinique</div>

            <a href="{{ route('treatments.index') }}"
               class="{{ request()->routeIs('treatments.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Traitements
            </a>

            <a href="{{ route('treatment-records.index') }}"
               class="{{ request()->routeIs('treatment-records.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Dossiers médicaux
            </a>
        @endif

        {{-- Finance --}}
        @if(in_array($role, ['super_admin', 'admin', 'secretary']))
            <div class="pt-3 pb-1 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Finance</div>

            <a href="{{ route('invoices.index') }}"
               class="{{ request()->routeIs('invoices.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Factures
            </a>
        @endif

        {{-- Inventory --}}
        @if(in_array($role, ['super_admin', 'admin', 'doctor']))
            <div class="pt-3 pb-1 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Inventaire</div>

            @if(in_array($role, ['super_admin', 'admin']))
            <a href="{{ route('suppliers.index') }}"
               class="{{ request()->routeIs('suppliers.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Fournisseurs
            </a>
            @endif

            <a href="{{ route('supply-items.index') }}"
               class="{{ request()->routeIs('supply-items.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Fournitures
            </a>

            <a href="{{ route('supply-orders.index') }}"
               class="{{ request()->routeIs('supply-orders.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                {{ $role === 'doctor' ? 'Mes demandes' : 'Bons de commande' }}
            </a>
        @endif

        {{-- Administration --}}
        @if(in_array($role, ['super_admin', 'admin']))
            <div class="pt-3 pb-1 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Administration</div>

            @if($role === 'super_admin')
                <a href="{{ route('cabinets.index') }}"
                   class="{{ request()->routeIs('cabinets.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Cabinets médicaux
                </a>
            @endif

            <a href="{{ route('users.index') }}"
               class="{{ request()->routeIs('users.*') ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white' : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4.5 h-4.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Gestion du personnel
            </a>
        @endif
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-slate-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Se déconnecter
            </button>
        </form>
    </div>
</aside>
