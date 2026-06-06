<header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-4">
        {{-- Mobile menu button --}}
        <button class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Page title --}}
        <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Tableau de bord')</h1>
    </div>

    <div class="flex items-center gap-3">
        {{-- Quick add appointment (secretary/admin) --}}
        @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'secretary']))
            <a href="{{ route('appointments.create') }}"
               class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau rendez-vous
            </a>
        @endif

        {{-- Current date --}}
        <div class="hidden md:flex items-center gap-1.5 text-sm text-slate-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ now()->format('D, M j Y') }}
        </div>

        {{-- Notification bell --}}
        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    class="relative p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($unreadCount > 0)
                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center leading-none">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
                @endif
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak
                 class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 z-50 overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                    <span class="font-semibold text-slate-800 text-sm">Notifications</span>
                    @if($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.markAllRead') }}">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:underline font-medium">
                            Tout marquer comme lu
                        </button>
                    </form>
                    @endif
                </div>

                {{-- List --}}
                @php $recent = auth()->user()->notifications()->limit(8)->get(); @endphp
                <div class="divide-y divide-slate-50 max-h-80 overflow-y-auto">
                    @forelse($recent as $notif)
                    @php
                        $data  = $notif->data;
                        $event = $data['event'] ?? 'booked';
                        $icon  = match($event) {
                            'booked'      => ['bg' => 'bg-blue-100',   'color' => 'text-blue-600',   'path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            'confirmed'   => ['bg' => 'bg-emerald-100','color' => 'text-emerald-600','path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'cancelled'   => ['bg' => 'bg-red-100',    'color' => 'text-red-600',    'path' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'completed'   => ['bg' => 'bg-violet-100', 'color' => 'text-violet-600', 'path' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                            default       => ['bg' => 'bg-slate-100',  'color' => 'text-slate-500',  'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        };
                    @endphp
                    <a href="{{ route('notifications.read', $notif->id) }}"
                       class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition-colors {{ $notif->read_at ? 'opacity-60' : '' }}">
                        <div class="w-8 h-8 rounded-full {{ $icon['bg'] }} flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 {{ $icon['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-slate-700 leading-snug {{ $notif->read_at ? '' : 'font-semibold' }}">
                                {{ $data['message'] }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notif->read_at)
                        <div class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-1.5"></div>
                        @endif
                    </a>
                    @empty
                    <div class="px-4 py-8 text-center text-sm text-slate-400">
                        <svg class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Aucune notification
                    </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                @if($recent->isNotEmpty())
                <div class="border-t border-slate-100 px-4 py-2.5 text-center">
                    <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:underline font-medium">
                        Voir toutes les notifications
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Profile dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-lg hover:bg-slate-50">
                <img src="{{ auth()->user()->getAvatarUrl() }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" @click.outside="open = false" x-cloak
                 class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50">
                <div class="px-4 py-2 border-b border-slate-100">
                    <div class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-400">{{ auth()->user()->email }}</div>
                </div>
                <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
