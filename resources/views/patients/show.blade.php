@extends('layouts.app')
@section('title', $patient->name)
@section('page-title', $patient->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('patients.index') }}" class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux patients
    </a>
    <div class="flex items-center gap-2">
        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
           class="flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-50 text-blue-700 text-sm font-medium hover:bg-blue-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Prendre un rendez-vous
        </a>
        @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'secretary']))
            <a href="{{ route('patients.edit', $patient) }}"
               class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Modifier
            </a>
        @endif
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Profile card --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center">
            <img src="{{ $patient->getAvatarUrl() }}" class="w-20 h-20 rounded-2xl mx-auto object-cover">
            <h2 class="font-semibold text-slate-800 mt-3 text-lg">{{ $patient->name }}</h2>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $patient->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                {{ $patient->is_active ? 'Actif' : 'Inactif' }}
            </span>
            <div class="mt-4 space-y-2 text-sm text-left">
                <div class="flex items-center gap-2 text-slate-600"><svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>{{ $patient->email }}</div>
                @if($patient->phone)<div class="flex items-center gap-2 text-slate-600"><svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>{{ $patient->phone }}</div>@endif
            </div>
        </div>

        @if($patient->patientProfile)
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-3">Informations médicales</h3>
            <dl class="space-y-2 text-sm">
                @if($patient->patientProfile->date_of_birth)
                <div class="flex justify-between"><dt class="text-slate-500">Âge</dt><dd class="font-medium">{{ $patient->patientProfile->age }} ans</dd></div>
                @endif
                @if($patient->patientProfile->gender)
                <div class="flex justify-between"><dt class="text-slate-500">Genre</dt><dd class="font-medium capitalize">{{ $patient->patientProfile->gender }}</dd></div>
                @endif
                @if($patient->patientProfile->blood_type)
                <div class="flex justify-between"><dt class="text-slate-500">Groupe sanguin</dt><dd class="font-medium">{{ $patient->patientProfile->blood_type }}</dd></div>
                @endif
                @if($patient->patientProfile->insurance_provider)
                <div class="flex justify-between"><dt class="text-slate-500">Assurance</dt><dd class="font-medium">{{ $patient->patientProfile->insurance_provider }}</dd></div>
                @endif
                @if($patient->patientProfile->allergies)
                <div><dt class="text-slate-500 mb-1">Allergies :</dt><dd class="text-red-700 bg-red-50 rounded-lg px-2.5 py-1.5 text-xs">{{ $patient->patientProfile->allergies }}</dd></div>
                @endif
                @if($patient->patientProfile->emergency_contact_name)
                <div><dt class="text-slate-500 mb-1">Contact d'urgence</dt><dd class="font-medium">{{ $patient->patientProfile->emergency_contact_name }} — {{ $patient->patientProfile->emergency_contact_phone }}</dd></div>
                @endif
            </dl>
        </div>
        @endif
    </div>

    {{-- Tabs area --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Appointments --}}
        <div class="bg-white rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Rendez-vous</h3>
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="text-sm text-blue-600 hover:underline">+ Nouveau</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($appointments as $appt)
                    <a href="{{ route('appointments.show', $appt) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50">
                        <div class="w-10 text-center">
                            <div class="text-xs font-bold text-slate-800">{{ $appt->appointment_date->format('M j') }}</div>
                            <div class="text-xs text-slate-400">{{ $appt->appointment_date->format('H:i') }}</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-slate-800 capitalize">{{ $appt->type }}</div>
                            <div class="text-xs text-slate-400">Dr. {{ $appt->doctor->name }}</div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $appt->getStatusColorClass() }} shrink-0">{{ ucfirst($appt->status) }}</span>
                    </a>
                @empty
                    <div class="px-5 py-6 text-center text-sm text-slate-400">Aucun rendez-vous pour l'instant.</div>
                @endforelse
            </div>
            @if($appointments->hasPages())<div class="px-5 py-3 border-t">{{ $appointments->links() }}</div>@endif
        </div>

        {{-- Treatment Records --}}
        <div class="bg-white rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Dossiers de traitement</h3>
                <a href="{{ route('treatment-records.create', ['patient_id' => $patient->id]) }}" class="text-sm text-blue-600 hover:underline">+ Nouveau</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($treatmentRecords as $record)
                    <div class="flex items-center gap-4 px-5 py-3.5">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-slate-800">{{ $record->treatment->name }}</div>
                            <div class="text-xs text-slate-400">Dr. {{ $record->doctor->name }} · {{ $record->created_at->format('M j, Y') }}</div>
                        </div>
                        @if($record->tooth_number)<span class="text-xs bg-slate-100 px-2 py-0.5 rounded-lg text-slate-600">Dent {{ $record->tooth_number }}</span>@endif
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $record->getStatusColorClass() }} shrink-0">{{ ucfirst($record->status) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-6 text-center text-sm text-slate-400">Aucun dossier de traitement pour l'instant.</div>
                @endforelse
            </div>
            @if($treatmentRecords->hasPages())<div class="px-5 py-3 border-t">{{ $treatmentRecords->links() }}</div>@endif
        </div>

        {{-- Invoices --}}
        <div class="bg-white rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Factures</h3>
                <a href="{{ route('invoices.create', ['patient_id' => $patient->id]) }}" class="text-sm text-blue-600 hover:underline">+ Nouvelle</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($invoices as $invoice)
                    <a href="{{ route('invoices.show', $invoice) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-slate-800">{{ $invoice->invoice_number }}</div>
                            <div class="text-xs text-slate-400">{{ $invoice->created_at->format('M j, Y') }}</div>
                        </div>
                        <div class="text-sm font-bold text-slate-800">DT {{ number_format($invoice->total, 2) }}</div>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $invoice->getStatusColorClass() }} shrink-0">{{ ucfirst($invoice->status) }}</span>
                    </a>
                @empty
                    <div class="px-5 py-6 text-center text-sm text-slate-400">Aucune facture pour l'instant.</div>
                @endforelse
            </div>
            @if($invoices->hasPages())<div class="px-5 py-3 border-t">{{ $invoices->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
