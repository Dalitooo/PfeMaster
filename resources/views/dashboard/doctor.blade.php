@extends('layouts.app')
@section('title', 'Tableau de bord médecin')
@section('page-title', 'Mon tableau de bord')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Rendez-vous aujourd\'hui', 'value' => $stats['today_appointments'],   'color' => 'bg-blue-600'],
        ['label' => 'Total patients',            'value' => $stats['total_patients'],       'color' => 'bg-emerald-600'],
        ['label' => 'Traitements en attente',    'value' => $stats['pending_treatments'],   'color' => 'bg-amber-600'],
        ['label' => 'Terminés ce mois',          'value' => $stats['completed_this_month'], 'color' => 'bg-violet-600'],
    ] as $card)
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="w-10 h-10 rounded-xl {{ $card['color'] }} flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $card['value'] }}</div>
            <div class="text-sm text-slate-500 mt-0.5">{{ $card['label'] }}</div>
        </div>
    @endforeach
</div>

<div class="mb-6 {{ $lowStockItems->isNotEmpty() ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-200' }} border rounded-2xl p-5">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2 {{ $lowStockItems->isNotEmpty() ? 'text-amber-800' : 'text-slate-600' }}">
            <svg class="w-5 h-5 {{ $lowStockItems->isNotEmpty() ? 'text-amber-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span class="font-semibold text-sm">Articles en stock faible</span>
        </div>
        <a href="{{ route('supply-items.index', ['low_stock' => 1]) }}" class="text-xs {{ $lowStockItems->isNotEmpty() ? 'text-amber-700' : 'text-slate-400' }} hover:underline">Voir tout</a>
    </div>
    @if($lowStockItems->isNotEmpty())
    <div class="divide-y divide-amber-100">
        @foreach($lowStockItems as $item)
        <div class="flex items-center justify-between py-2.5 text-sm">
            <div>
                <span class="font-medium text-slate-800">{{ $item->name }}</span>
                <span class="ml-2 text-xs text-slate-500">{{ $item->supplier->company_name }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                    {{ $item->stock_quantity }} / {{ $item->min_stock_level }} {{ $item->unit }}
                </span>
                <a href="{{ route('supply-orders.create', ['item_id' => $item->id]) }}"
                   class="flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-200 text-amber-900 hover:bg-amber-300">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Demander
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-sm text-slate-400 text-center py-2">Tous les stocks sont suffisants.</p>
    @endif
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Planning du jour</h2>
            <span class="text-xs text-slate-400">{{ today()->format('l, M j') }}</span>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($todayAppointments as $appt)
                <a href="{{ route('appointments.show', $appt) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50">
                    <div class="text-center w-14 shrink-0">
                        <div class="text-base font-bold text-slate-800">{{ $appt->appointment_date->format('H:i') }}</div>
                        <div class="text-xs text-slate-400">{{ $appt->duration_minutes }}min</div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-800 truncate">{{ $appt->patient->name }}</div>
                        <div class="text-xs text-slate-400 capitalize">{{ $appt->type }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $appt->getStatusColorClass() }} shrink-0">
                        {{ ucfirst($appt->status) }}
                    </span>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">Aucun rendez-vous aujourd'hui.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Prochains rendez-vous</h2>
            <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($upcomingAppointments as $appt)
                <a href="{{ route('appointments.show', $appt) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50">
                    <img src="{{ $appt->patient->getAvatarUrl() }}" class="w-9 h-9 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-800 truncate">{{ $appt->patient->name }}</div>
                        <div class="text-xs text-slate-400">{{ $appt->appointment_date->format('M j, H:i') }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $appt->getStatusColorClass() }} shrink-0">
                        {{ ucfirst($appt->status) }}
                    </span>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">Aucun rendez-vous à venir.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
