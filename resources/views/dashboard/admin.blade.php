@extends('layouts.app')
@section('title', 'Tableau de bord administrateur')
@section('page-title', 'Tableau de bord')

@section('content')
{{-- Stats grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $cards = [
            ['label' => 'Total patients',          'value' => number_format($stats['total_patients']),      'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'blue'],
            ['label' => 'Rendez-vous aujourd\'hui', 'value' => $stats['today_appointments'],               'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'emerald'],
            ['label' => 'Recettes du mois',         'value' => 'DT ' . number_format($stats['monthly_revenue'], 0), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'violet'],
            ['label' => 'Rendez-vous en attente',   'value' => $stats['pending_appointments'],             'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber'],
        ];
    @endphp

    @foreach($cards as $card)
        @php
            $colors = [
                'blue'    => ['bg' => 'bg-blue-50',   'icon' => 'bg-blue-600',   'text' => 'text-blue-600'],
                'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-600','text' => 'text-emerald-600'],
                'violet'  => ['bg' => 'bg-violet-50',  'icon' => 'bg-violet-600', 'text' => 'text-violet-600'],
                'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'bg-amber-600',  'text' => 'text-amber-600'],
            ];
            $c = $colors[$card['color']];
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl {{ $c['icon'] }} flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $card['value'] }}</div>
            <div class="text-sm text-slate-500 mt-0.5">{{ $card['label'] }}</div>
        </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Upcoming appointments --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Prochains rendez-vous</h2>
            <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($upcomingAppointments as $appt)
                <div class="flex items-center gap-4 px-5 py-3.5">
                    <img src="{{ $appt->patient->getAvatarUrl() }}" class="w-9 h-9 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-800 truncate">{{ $appt->patient->name }}</div>
                        <div class="text-xs text-slate-400">Dr. {{ $appt->doctor->name }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-slate-800">{{ $appt->appointment_date->format('M j') }}</div>
                        <div class="text-xs text-slate-400">{{ $appt->appointment_date->format('H:i') }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $appt->getStatusColorClass() }}">
                        {{ ucfirst($appt->status) }}
                    </span>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">Aucun rendez-vous à venir.</div>
            @endforelse
        </div>
    </div>

    {{-- Quick stats sidebar --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Statistiques rapides</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Médecins</span>
                    <span class="font-semibold text-slate-800">{{ $stats['total_doctors'] }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Factures impayées</span>
                    <span class="font-semibold {{ $stats['unpaid_invoices'] > 0 ? 'text-red-600' : 'text-slate-800' }}">
                        {{ $stats['unpaid_invoices'] }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Articles en stock faible</span>
                    <span class="font-semibold {{ $stats['low_stock_items'] > 0 ? 'text-amber-600' : 'text-slate-800' }}">
                        {{ $stats['low_stock_items'] }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Recettes totales</span>
                    <span class="font-semibold text-emerald-600">DT {{ number_format($stats['total_revenue'], 0) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-3">Actions rapides</h3>
            <div class="space-y-2">
                <a href="{{ route('patients.create') }}" class="flex items-center gap-2 p-2.5 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-sm text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Enregistrer un patient
                </a>
                <a href="{{ route('appointments.create') }}" class="flex items-center gap-2 p-2.5 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-sm text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Prendre un rendez-vous
                </a>
                <a href="{{ route('invoices.create') }}" class="flex items-center gap-2 p-2.5 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-sm text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    Créer une facture
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
