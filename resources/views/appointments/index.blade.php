@extends('layouts.app')
@section('title', 'Rendez-vous')
@section('page-title', 'Rendez-vous')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">{{ $appointments->total() }} rendez-vous</p>
    <div class="flex items-center gap-2">
        @if(auth()->user()->isDoctor())
        <a href="{{ route('appointments.bordereau', ['date' => request('date', today()->format('Y-m-d'))]) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Bordereau du jour
        </a>
        @endif
        <a href="{{ route('appointments.create') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ auth()->user()->isPatient() ? 'Prendre un rendez-vous' : 'Nouveau rendez-vous' }}
        </a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="flex flex-wrap gap-3 mb-4">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
               class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
    </div>
    <input type="date" name="date" value="{{ request('date') }}"
           class="px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    <select name="status" class="px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Tous les statuts</option>
        @foreach(['pending','confirmed','in_progress','completed','cancelled','no_show'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200">Filtrer</button>
    @if(request()->hasAny(['search','date','status']))
        <a href="{{ route('appointments.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200">Effacer</a>
    @endif
    <a href="{{ route('appointments.calendar') }}" class="ml-auto flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
        Vue calendrier
    </a>
</form>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Date &amp; Heure</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Patient</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden md:table-cell">Médecin</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Type</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Statut</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($appointments as $appt)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-slate-800">{{ $appt->appointment_date->format('M j, Y') }}</div>
                        <div class="text-xs text-slate-400">{{ $appt->appointment_date->format('H:i') }} · {{ $appt->duration_minutes }}min</div>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <img src="{{ $appt->patient->getAvatarUrl() }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                            <span class="font-medium text-slate-800 truncate max-w-[120px]">{{ $appt->patient->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-slate-600">Dr. {{ $appt->doctor->name }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 capitalize">{{ str_replace('_', ' ', $appt->type) }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $appt->getStatusColorClass() }}">
                            {{ ucwords(str_replace('_', ' ', $appt->status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('appointments.show', $appt) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @if(!in_array($appt->status, ['completed', 'cancelled']) && in_array(auth()->user()->role, ['super_admin', 'admin', 'secretary', 'doctor']))
                                <a href="{{ route('appointments.edit', $appt) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Aucun rendez-vous trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($appointments->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
