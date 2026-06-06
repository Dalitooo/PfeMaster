@extends('layouts.app')
@section('title', 'Mon tableau de bord')
@section('page-title', 'Mon espace santé')

@section('content')
<div class="mb-6 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold">Bienvenue, {{ $user->name }}</h2>
        <p class="text-blue-200 text-sm mt-1">{{ today()->format('l, F j, Y') }}</p>
        @if($upcomingAppointments->isNotEmpty())
            <div class="mt-2 text-sm text-blue-100">
                Prochain : <span class="font-semibold text-white">{{ $upcomingAppointments->first()->appointment_date->format('M j à H:i') }}</span>
                · Dr. {{ $upcomingAppointments->first()->doctor->name }}
            </div>
        @endif
    </div>
    <a href="{{ route('appointments.create') }}"
       class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-blue-600 text-sm font-semibold hover:bg-blue-50 transition-colors shadow">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Prendre un rendez-vous
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Prochains rendez-vous</h2>
            <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($upcomingAppointments as $appt)
                <a href="{{ route('appointments.show', $appt) }}" class="block px-5 py-3.5 hover:bg-slate-50">
                    <div class="text-sm font-medium text-slate-800">{{ $appt->appointment_date->format('M j, Y') }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">{{ $appt->appointment_date->format('H:i') }} avec Dr. {{ $appt->doctor->name }}</div>
                    <span class="mt-1 inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $appt->getStatusColorClass() }}">
                        {{ ucfirst($appt->status) }}
                    </span>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">Aucun rendez-vous à venir.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Traitements récents</h2>
            <a href="{{ route('treatment-records.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentTreatments as $record)
                <div class="px-5 py-3.5">
                    <div class="text-sm font-medium text-slate-800">{{ $record->treatment->name }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">Dr. {{ $record->doctor->name }} · {{ $record->created_at->format('M j, Y') }}</div>
                    <span class="mt-1 inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $record->getStatusColorClass() }}">
                        {{ ucfirst($record->status) }}
                    </span>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">Aucun dossier de traitement.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Mes soins — coût total</h2>
            <p class="text-xs text-slate-400 mt-0.5">Actes terminés</p>
        </div>
        <div class="flex-1 flex flex-col items-center justify-center px-5 py-8 text-center">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="text-3xl font-bold text-slate-800">
                DT {{ number_format($totalCost, 3, ',', ' ') }}
            </div>
            <p class="text-sm text-slate-400 mt-1">Total des soins réalisés</p>
            <a href="{{ route('treatment-records.index') }}"
               class="mt-4 inline-flex items-center gap-1.5 text-sm text-blue-600 hover:underline font-medium">
                Voir tous les dossiers
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>
@endsection
