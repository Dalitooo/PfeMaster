@extends('layouts.app')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $notifications->total() }} notification(s)</p>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form method="POST" action="{{ route('notifications.markAllRead') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $notif)
            @php
                $data  = $notif->data;
                $event = $data['event'] ?? 'booked';
                $colors = [
                    'booked'    => ['bg' => 'bg-blue-100',    'text' => 'text-blue-600',    'path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    'confirmed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'cancelled' => ['bg' => 'bg-red-100',     'text' => 'text-red-600',     'path' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'completed' => ['bg' => 'bg-violet-100',  'text' => 'text-violet-600',  'path' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                ][$event] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-500', 'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
            @endphp
            <a href="{{ route('notifications.read', $notif->id) }}"
               class="flex items-start gap-4 px-5 py-4 hover:bg-slate-50 transition-colors {{ $notif->read_at ? '' : 'bg-blue-50/40' }}">
                <div class="w-10 h-10 rounded-full {{ $colors['bg'] }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $colors['path'] }}"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-800 {{ $notif->read_at ? '' : 'font-semibold' }}">
                        {{ $data['message'] }}
                    </p>
                    @if(!empty($data['appointment_date']))
                    <p class="text-xs text-slate-400 mt-0.5">RDV : {{ $data['appointment_date'] }}</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notif->read_at)
                <div class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0 mt-1.5"></div>
                @endif
            </a>
            @empty
            <div class="px-5 py-16 text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <p class="text-sm font-medium text-slate-400">Aucune notification</p>
            </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $notifications->links() }}</div>
        @endif
    </div>

</div>
@endsection
