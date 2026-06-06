@extends('layouts.app')
@section('title', 'Tableau de bord secrétaire')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Rendez-vous aujourd\'hui', 'value' => $stats['today_appointments'],   'color' => 'bg-blue-600'],
        ['label' => 'En attente de confirmation','value' => $stats['pending_appointments'], 'color' => 'bg-amber-600'],
        ['label' => 'Total patients',            'value' => $stats['total_patients'],       'color' => 'bg-emerald-600'],
        ['label' => 'Factures en attente',       'value' => $stats['pending_invoices'],     'color' => 'bg-violet-600'],
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

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Planning du jour</h2>
            <a href="{{ route('appointments.create') }}" class="text-sm text-blue-600 hover:underline">+ Nouveau</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($todayAppointments as $appt)
                <a href="{{ route('appointments.show', $appt) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50">
                    <div class="text-center w-14 shrink-0">
                        <div class="text-sm font-bold text-slate-800">{{ $appt->appointment_date->format('H:i') }}</div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-800 truncate">{{ $appt->patient->name }}</div>
                        <div class="text-xs text-slate-400">Dr. {{ $appt->doctor->name }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $appt->getStatusColorClass() }}">
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
            <h2 class="font-semibold text-slate-800">Confirmations en attente</h2>
            <a href="{{ route('appointments.index', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($pendingAppointments as $appt)
                <div class="flex items-center gap-4 px-5 py-3.5">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-800 truncate">{{ $appt->patient->name }}</div>
                        <div class="text-xs text-slate-400">{{ $appt->appointment_date->format('M j, H:i') }} — Dr. {{ $appt->doctor->name }}</div>
                    </div>
                    <form method="POST" action="{{ route('appointments.status', $appt) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-blue-600 text-white text-xs font-medium hover:bg-blue-700">Confirmer</button>
                    </form>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">Aucun rendez-vous en attente.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
