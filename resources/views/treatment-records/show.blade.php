@extends('layouts.app')
@section('title', 'Dossier de traitement')
@section('page-title', 'Dossier de traitement')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('treatment-records.index') }}" class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour
        </a>
        @if(in_array(auth()->user()->role, ['super_admin','admin','doctor']))
            <a href="{{ route('treatment-records.edit', $treatmentRecord) }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Modifier
            </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $treatmentRecord->treatment->name }}</h2>
                <div class="text-sm text-slate-500 mt-1">{{ $treatmentRecord->treatment->category->name }}</div>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $treatmentRecord->getStatusColorClass() }}">
                {{ ucwords(str_replace('_', ' ', $treatmentRecord->status)) }}
            </span>
        </div>

        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-slate-500 mb-1">Patient</dt>
                <dd><a href="{{ route('patients.show', $treatmentRecord->patient) }}" class="font-medium text-blue-600 hover:underline">{{ $treatmentRecord->patient->name }}</a></dd>
            </div>
            <div>
                <dt class="text-slate-500 mb-1">Médecin</dt>
                <dd class="font-medium">Dr. {{ $treatmentRecord->doctor->name }}</dd>
            </div>
            @if($treatmentRecord->tooth_number)
            <div>
                <dt class="text-slate-500 mb-1">Numéro de dent</dt>
                <dd class="font-medium">{{ $treatmentRecord->tooth_number }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-slate-500 mb-1">Coût</dt>
                <dd class="font-bold text-slate-800">DT {{ number_format($treatmentRecord->cost, 2) }}</dd>
            </div>
            @if($treatmentRecord->scheduled_date)
            <div>
                <dt class="text-slate-500 mb-1">Date planifiée</dt>
                <dd class="font-medium">{{ $treatmentRecord->scheduled_date->format('M j, Y') }}</dd>
            </div>
            @endif
            @if($treatmentRecord->completed_date)
            <div>
                <dt class="text-slate-500 mb-1">Date de réalisation</dt>
                <dd class="font-medium">{{ $treatmentRecord->completed_date->format('M j, Y') }}</dd>
            </div>
            @endif
            @if($treatmentRecord->notes)
            <div class="col-span-2">
                <dt class="text-slate-500 mb-1">Notes</dt>
                <dd class="text-slate-700 bg-slate-50 rounded-xl p-3">{{ $treatmentRecord->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>
</div>
@endsection
