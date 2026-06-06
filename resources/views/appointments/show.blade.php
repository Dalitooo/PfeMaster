@extends('layouts.app')
@section('title', 'Détails du rendez-vous')
@section('page-title', 'Détails du rendez-vous')

@php
    $user         = auth()->user();
    $isDoctor     = $user->isDoctor();
    $status       = $appointment->status;
    $hasTreatment = $appointment->treatmentRecords->isNotEmpty();
    $hasCnam      = $appointment->cnamBulletins->isNotEmpty();
    $isOpen       = $status === 'in_progress';
    $isDone       = $status === 'completed';

    $statusLabels = [
        'pending'     => 'En attente',
        'confirmed'   => 'Confirmé',
        'in_progress' => 'En cours',
        'completed'   => 'Terminé',
        'cancelled'   => 'Annulé',
        'no_show'     => 'Absent',
    ];

    $stepDone = [
        in_array($status, ['in_progress', 'completed']),
        $hasTreatment,
        $hasCnam,
        $isDone,
    ];
    $currentStep = 4;
    foreach ($stepDone as $i => $done) {
        if (!$done) { $currentStep = $i; break; }
    }
    $stepMeta = [
        ['label' => 'Ouvrir',         'subtitle' => 'Lancer la consultation'],
        ['label' => 'Dossier médical', 'subtitle' => 'Actes & traitements'],
        ['label' => 'Bulletin CNAM',   'subtitle' => 'Bulletin de soin'],
        ['label' => 'Terminer',        'subtitle' => 'Clôturer'],
    ];

    $hasOrdonnance = $appointment->ordonnances->isNotEmpty();
    $openDossier   = $errors->hasAny(['treatment_id', 'cost', 'tooth_number', 'status', 'notes', 'scheduled_date', 'completed_date']);
    $openCnam      = $errors->hasAny(['dental_acts', 'prostheses']);
    $openOrdonnance = $errors->hasAny(['items', 'items.*.medicament']);
@endphp

@section('content')

{{-- Top bar --}}
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('appointments.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $appointment->getStatusColorClass() }}">
            {{ $statusLabels[$status] ?? ucfirst($status) }}
        </span>
        @if(!in_array($status, ['completed','cancelled']) && !$isDoctor)
        <a href="{{ route('appointments.edit', $appointment) }}"
           class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Modifier
        </a>
        @endif
    </div>
</div>

{{-- ── DOCTOR VIEW ─────────────────────────────────────────────────────────── --}}
@if($isDoctor && !in_array($status, ['cancelled','no_show']))
<div x-data="{ showDossier: {{ $openDossier ? 'true' : 'false' }}, showCnam: {{ $openCnam ? 'true' : 'false' }}, showOrdonnance: {{ $openOrdonnance ? 'true' : 'false' }}, activePanel: {{ $currentStep < 4 ? $currentStep : 3 }} }">

    {{-- Action panels — always rendered, visibility controlled by activePanel --}}
    <div class="mb-6">

        {{-- Panel 0 : Ouvrir --}}
        <div x-show="activePanel === 0" x-cloak>
            @if(!$isOpen && !$isDone)
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 flex items-center justify-between gap-6">
                <div>
                    <div class="font-semibold text-blue-900 mb-1">Ouvrir le rendez-vous</div>
                    <div class="text-sm text-blue-700">Le patient est présent ? Lancez la consultation pour commencer à enregistrer les actes.</div>
                </div>
                <form method="POST" action="{{ route('appointments.status', $appointment) }}" class="shrink-0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 whitespace-nowrap">
                        Ouvrir la consultation
                    </button>
                </form>
            </div>
            @else
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-9 h-9 rounded-full bg-emerald-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <div class="font-semibold text-slate-800 text-sm">Consultation ouverte</div>
                    <div class="text-xs text-slate-500 mt-0.5">Le rendez-vous est en cours.</div>
                </div>
            </div>
            @endif
        </div>

        {{-- Panel 1+2 : Dossier médical & Bulletin CNAM --}}
        <div x-show="activePanel === 1 || activePanel === 2" x-cloak>
            <div class="grid sm:grid-cols-2 gap-4">
                {{-- Dossier médical --}}
                <div class="bg-white border rounded-2xl p-5 {{ $hasTreatment ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200' }}">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $hasTreatment ? 'bg-emerald-100' : 'bg-blue-100' }}">
                            @if($hasTreatment)
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @else
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Dossier médical</div>
                            @if($hasTreatment)
                                <div class="text-xs text-emerald-600 font-medium mt-0.5">{{ $appointment->treatmentRecords->count() }} acte(s) enregistré(s)</div>
                            @else
                                <div class="text-xs text-slate-400 mt-0.5">Actes & traitements effectués</div>
                            @endif
                        </div>
                    </div>
                    <button type="button" @click="showDossier = true"
                            class="block w-full text-center px-4 py-2 rounded-xl text-sm font-semibold
                                {{ $hasTreatment ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        {{ $hasTreatment ? '+ Ajouter un acte' : 'Créer le dossier' }}
                    </button>
                </div>

                {{-- Bulletin CNAM --}}
                <div class="bg-white border rounded-2xl p-5 {{ $hasCnam ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200' }}">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $hasCnam ? 'bg-emerald-100' : 'bg-violet-100' }}">
                            @if($hasCnam)
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @else
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Bulletin CNAM</div>
                            @if($hasCnam)
                                <div class="text-xs text-emerald-600 font-medium mt-0.5">Bulletin créé</div>
                            @else
                                <div class="text-xs text-slate-400 mt-0.5">Bulletin de soin pour remboursement</div>
                            @endif
                        </div>
                    </div>
                    <button type="button" @click="showCnam = true"
                            class="block w-full text-center px-4 py-2 rounded-xl text-sm font-semibold
                                {{ $hasCnam ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-violet-600 text-white hover:bg-violet-700' }}">
                        {{ $hasCnam ? 'Modifier le bulletin' : 'Créer le bulletin' }}
                    </button>
                    @if(!$hasCnam)
                    <form method="POST" action="{{ route('cnam.skip', $appointment) }}" class="mt-2">
                        @csrf
                        <button type="submit"
                                class="block w-full text-center px-4 py-2 rounded-xl text-sm font-medium text-slate-500 bg-slate-100 hover:bg-slate-200 hover:text-slate-700">
                            Patient non assuré CNAM
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Panel 3 : Terminer --}}
        <div x-show="activePanel === 3" x-cloak>
            @if($isDone)
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <div class="font-semibold text-emerald-900">Consultation terminée</div>
                    <div class="text-sm text-emerald-700 mt-0.5">Cette consultation a été clôturée avec succès.</div>
                </div>
            </div>
            @elseif($isOpen)
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 flex items-center justify-between gap-6">
                <div>
                    <div class="font-semibold text-emerald-900 mb-1">Terminer la consultation</div>
                    <div class="text-sm text-emerald-700">
                        @if(!$hasTreatment || !$hasCnam)
                            Complétez d'abord le dossier médical{{ !$hasCnam ? ' et le bulletin CNAM' : '' }} avant de clôturer.
                        @else
                            Dossier et bulletin créés. Vous pouvez clôturer la consultation.
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('appointments.status', $appointment) }}" class="shrink-0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit"
                            {{ (!$hasTreatment || !$hasCnam) ? 'disabled' : '' }}
                            class="px-5 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap
                                {{ (!$hasTreatment || !$hasCnam) ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                        Terminer la consultation
                    </button>
                </form>
            </div>
            @else
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <div class="font-semibold text-slate-600 text-sm">Étape verrouillée</div>
                    <div class="text-xs text-slate-400 mt-0.5">Ouvrez d'abord la consultation pour accéder à cette étape.</div>
                </div>
            </div>
            @endif
        </div>

    </div>

    {{-- Appointment info (compact) --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="font-bold text-slate-800">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('l j F Y') }}
                </div>
                <div class="text-sm text-slate-500 mt-0.5">
                    {{ $appointment->appointment_date->format('H:i') }} — {{ $appointment->getEndTime()->format('H:i') }}
                    · {{ $appointment->duration_minutes }} min
                    · <span class="capitalize">{{ ['checkup'=>'Bilan','consultation'=>'Consultation','procedure'=>'Procédure','follow_up'=>'Suivi','emergency'=>'Urgence'][$appointment->type] ?? $appointment->type }}</span>
                </div>
            </div>
            <a href="{{ route('patients.show', $appointment->patient) }}" class="flex items-center gap-2.5 hover:bg-slate-50 px-3 py-2 rounded-xl">
                <img src="{{ $appointment->patient->getAvatarUrl() }}" class="w-9 h-9 rounded-full object-cover">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-medium text-slate-800">{{ $appointment->patient->name }}</div>
                    <div class="text-xs text-slate-400">Patient</div>
                </div>
            </a>
        </div>
        @if($appointment->patient->patientProfile?->allergies)
        <div class="mt-3 bg-red-50 rounded-xl px-3 py-2 text-xs text-red-700">
            <span class="font-semibold">Allergies :</span> {{ $appointment->patient->patientProfile->allergies }}
        </div>
        @endif
        @if($appointment->reason || $appointment->notes)
        <div class="mt-3 pt-3 border-t border-slate-100 text-sm text-slate-500 space-y-1">
            @if($appointment->reason)<div><span class="font-medium text-slate-700">Motif :</span> {{ $appointment->reason }}</div>@endif
            @if($appointment->notes)<div><span class="font-medium text-slate-700">Notes :</span> {{ $appointment->notes }}</div>@endif
        </div>
        @endif

        @if($isOpen || $isDone)
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
            <div class="text-xs text-slate-400">
                @if($hasOrdonnance)
                    {{ $appointment->ordonnances->count() }} ordonnance(s) rédigée(s)
                @else
                    Aucune ordonnance
                @endif
            </div>
            <button type="button" @click="showOrdonnance = true"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                        {{ $hasOrdonnance ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ $hasOrdonnance ? 'Nouvelle ordonnance' : 'Rédiger une ordonnance' }}
            </button>
        </div>
        @endif
    </div>

    {{-- ════════════════════════════════════════════════════════════════
         MODAL: Dossier médical
    ═════════════════════════════════════════════════════════════════ --}}
    <div x-show="showDossier"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
         style="display:none"
         @click.self="showDossier = false">

        <div class="bg-white rounded-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Enregistrer un acte</h2>
                </div>
                <button type="button" @click="showDossier = false" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('treatment-records.store') }}"
                  x-data="{
                      cost: '{{ old('cost', '') }}',
                      prices: {{ $treatments->pluck('price','id')->toJson() }},
                      setPrice(id) { this.cost = this.prices[id] ?? ''; }
                  }">
                @csrf
                <input type="hidden" name="patient_id"     value="{{ $appointment->patient_id }}">
                <input type="hidden" name="doctor_id"      value="{{ $appointment->doctor_id }}">
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                <div class="p-6 space-y-4">

                    @if($errors->any() && $openDossier)
                    <div class="bg-red-50 rounded-xl px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Traitement <span class="text-red-500">*</span></label>
                        <select name="treatment_id" required @change="setPrice($event.target.value)"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner un traitement</option>
                            @foreach($treatments as $t)
                            <option value="{{ $t->id }}" @selected(old('treatment_id') == $t->id)>
                                {{ $t->name }} (DT {{ number_format($t->price, 3, ',', ' ') }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Numéro de dent</label>
                            <input type="text" name="tooth_number" value="{{ old('tooth_number') }}"
                                   placeholder="ex. 11, 21"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Coût (DT) <span class="text-red-500">*</span></label>
                            <input type="number" name="cost" min="0" step="0.001" required
                                   x-model="cost"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Statut</label>
                            <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach(['planned' => 'Planifié', 'in_progress' => 'En cours', 'completed' => 'Terminé', 'cancelled' => 'Annulé'] as $val => $lbl)
                                <option value="{{ $val }}" @selected(old('status', 'completed') === $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Date de réalisation</label>
                            <input type="date" name="completed_date"
                                   value="{{ old('completed_date', $appointment->appointment_date->format('Y-m-d')) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 flex items-center gap-3">
                    <button type="submit"
                            class="flex-1 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 text-center">
                        Enregistrer l'acte
                    </button>
                    <button type="button" @click="showDossier = false"
                            class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════
         MODAL: Bulletin CNAM
    ═════════════════════════════════════════════════════════════════ --}}
    <div x-show="showCnam"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
         style="display:none"
         @click.self="showCnam = false">

        <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 sticky top-0 bg-white z-10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Bulletin de soin CNAM</h2>
                        <p class="text-xs text-slate-400">{{ $appointment->patient->name }} — RDV du {{ $appointment->appointment_date->format('d/m/Y') }}</p>
                    </div>
                </div>
                <button type="button" @click="showCnam = false" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div x-data="cnamForm()">
                <form method="POST" action="{{ route('cnam.store', $appointment) }}">
                    @csrf

                    <div class="p-6 space-y-5">

                        {{-- Actes de soins --}}
                        <div class="rounded-xl border border-slate-200 overflow-hidden">
                            <div class="px-4 py-3 bg-blue-50 border-b border-blue-100">
                                <h3 class="font-bold text-blue-900 text-sm">Consultations et actes de soins dentaires</h3>
                                <p class="text-xs text-blue-600 mt-0.5">Indiquez la dent traitée et les actes pratiqués.</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50 border-b border-slate-200">
                                        <tr>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500 w-28">Date</th>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500 w-16">Dent</th>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500">Code acte</th>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500 w-24">Cotation</th>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500 w-28">Honoraires (DT)</th>
                                            <th class="w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(row, i) in acts" :key="i">
                                            <tr class="border-b border-slate-100">
                                                <td class="px-2 py-1.5"><input type="date" :name="`dental_acts[${i}][date]`" x-model="row.date" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400"></td>
                                                <td class="px-2 py-1.5"><input type="text" :name="`dental_acts[${i}][dent]`" x-model="row.dent" placeholder="11" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400"></td>
                                                <td class="px-2 py-1.5"><input type="text" :name="`dental_acts[${i}][code_acte]`" x-model="row.code_acte" placeholder="D01" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400"></td>
                                                <td class="px-2 py-1.5"><input type="text" :name="`dental_acts[${i}][cotation]`" x-model="row.cotation" placeholder="K50" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400"></td>
                                                <td class="px-2 py-1.5"><input type="text" :name="`dental_acts[${i}][honoraires]`" x-model="row.honoraires" placeholder="30.000" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400"></td>
                                                <td class="px-2 py-1.5 text-center">
                                                    <button type="button" @click="removeAct(i)" class="p-1 rounded text-slate-300 hover:text-red-500 hover:bg-red-50">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-4 py-2.5 border-t border-slate-100">
                                <button type="button" @click="addAct()" class="flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-700 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Ajouter une ligne
                                </button>
                            </div>
                        </div>

                        {{-- Prothèses --}}
                        <div class="rounded-xl border border-slate-200 overflow-hidden">
                            <div class="px-4 py-3 bg-amber-50 border-b border-amber-100">
                                <h3 class="font-bold text-amber-900 text-sm">Prothèses dentaires</h3>
                                <p class="text-xs text-amber-600 mt-0.5">Laisser vide si aucune prothèse.</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50 border-b border-slate-200">
                                        <tr>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500 w-28">Date</th>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500 w-20">Dents</th>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500">Code acte</th>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500 w-24">Cotation</th>
                                            <th class="text-left px-3 py-2 text-xs font-semibold text-slate-500 w-28">Honoraires (DT)</th>
                                            <th class="w-8"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(row, i) in prostheses" :key="i">
                                            <tr class="border-b border-slate-100">
                                                <td class="px-2 py-1.5"><input type="date" :name="`prostheses[${i}][date]`" x-model="row.date" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400"></td>
                                                <td class="px-2 py-1.5"><input type="text" :name="`prostheses[${i}][dents]`" x-model="row.dents" placeholder="11,21" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400"></td>
                                                <td class="px-2 py-1.5"><input type="text" :name="`prostheses[${i}][code_acte]`" x-model="row.code_acte" placeholder="PD01" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400"></td>
                                                <td class="px-2 py-1.5"><input type="text" :name="`prostheses[${i}][cotation]`" x-model="row.cotation" placeholder="P50" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400"></td>
                                                <td class="px-2 py-1.5"><input type="text" :name="`prostheses[${i}][honoraires]`" x-model="row.honoraires" placeholder="150.000" class="w-full px-2 py-1 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400"></td>
                                                <td class="px-2 py-1.5 text-center">
                                                    <button type="button" @click="removeProsthese(i)" class="p-1 rounded text-slate-300 hover:text-red-500 hover:bg-red-50">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-4 py-2.5 border-t border-slate-100">
                                <button type="button" @click="addProsthese()" class="flex items-center gap-1.5 text-xs text-amber-600 hover:text-amber-700 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Ajouter une prothèse
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center gap-3 sticky bottom-0 bg-white">
                        <button type="submit"
                                class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 text-white text-sm font-semibold hover:bg-violet-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Générer le bulletin
                        </button>
                        <button type="button" @click="showCnam = false"
                                class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════
         MODAL: Ordonnance
    ═════════════════════════════════════════════════════════════════ --}}
    <div x-show="showOrdonnance"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
         style="display:none"
         @click.self="showOrdonnance = false">

        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 sticky top-0 bg-white z-10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-sm">℞</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Rédiger une ordonnance</h2>
                        <p class="text-xs text-slate-400">{{ $appointment->patient->name }}</p>
                    </div>
                </div>
                <button type="button" @click="showOrdonnance = false" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div x-data="ordonnanceForm()">
                <form method="POST" action="{{ route('ordonnances.store', $appointment) }}">
                    @csrf

                    <div class="p-6 space-y-4">

                        @if($errors->any() && $openOrdonnance)
                        <div class="bg-red-50 rounded-xl px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Medication rows --}}
                        <div class="space-y-3">
                            <template x-for="(item, i) in items" :key="i">
                                <div class="bg-slate-50 rounded-xl p-4 space-y-3 relative">
                                    <button type="button" @click="remove(i)"
                                            x-show="items.length > 1"
                                            class="absolute top-3 right-3 p-1 rounded text-slate-300 hover:text-red-500 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>

                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0" x-text="i + 1"></div>
                                        <input type="text" :name="`items[${i}][medicament]`" x-model="item.medicament"
                                               placeholder="Nom du médicament *" required
                                               class="flex-1 px-3 py-2 rounded-lg border border-slate-200 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <label class="block text-xs text-slate-500 mb-1">Dosage</label>
                                            <input type="text" :name="`items[${i}][dosage]`" x-model="item.dosage"
                                                   placeholder="ex. 500 mg"
                                                   class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-slate-500 mb-1">Fréquence</label>
                                            <input type="text" :name="`items[${i}][frequence]`" x-model="item.frequence"
                                                   placeholder="ex. 3x/jour"
                                                   class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-slate-500 mb-1">Durée</label>
                                            <input type="text" :name="`items[${i}][duree]`" x-model="item.duree"
                                                   placeholder="ex. 7 jours"
                                                   class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs text-slate-500 mb-1">Instructions particulières</label>
                                        <input type="text" :name="`items[${i}][instructions]`" x-model="item.instructions"
                                               placeholder="ex. Prendre pendant les repas"
                                               class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="add()"
                                class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium w-full justify-center py-2 border-2 border-dashed border-blue-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Ajouter un médicament
                        </button>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes / recommandations</label>
                            <textarea name="notes" rows="2"
                                      placeholder="ex. Rincer à l'eau tiède salée après chaque repas..."
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 flex items-center gap-3 sticky bottom-0 bg-white">
                        <button type="submit"
                                class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Générer l'ordonnance
                        </button>
                        <button type="button" @click="showOrdonnance = false"
                                class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>{{-- end x-data --}}

{{-- ── ADMIN / SECRETARY VIEW ──────────────────────────────────────────────── --}}
@else
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">

        {{-- Appointment details --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="text-2xl font-bold text-slate-800 mb-1">
                {{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('l j F Y') }}
            </div>
            <div class="text-slate-500 mb-5">
                {{ $appointment->appointment_date->format('H:i') }} — {{ $appointment->getEndTime()->format('H:i') }}
                ({{ $appointment->duration_minutes }} min)
            </div>
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Patient</div>
                    <a href="{{ route('patients.show', $appointment->patient) }}" class="flex items-center gap-3 hover:bg-slate-50 -mx-2 px-2 py-2 rounded-xl">
                        <img src="{{ $appointment->patient->getAvatarUrl() }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <div class="font-medium text-slate-800">{{ $appointment->patient->name }}</div>
                            <div class="text-xs text-slate-400">{{ $appointment->patient->phone ?? $appointment->patient->email }}</div>
                        </div>
                    </a>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Médecin</div>
                    <div class="flex items-center gap-3 px-2 py-2">
                        <img src="{{ $appointment->doctor->getAvatarUrl() }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <div class="font-medium text-slate-800">Dr. {{ $appointment->doctor->name }}</div>
                            <div class="text-xs text-slate-400">{{ $appointment->doctor->doctorProfile?->specialization ?? 'Dentiste généraliste' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 grid md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-slate-500">Type :</span>
                    <span class="font-medium ml-2">{{ ['checkup'=>'Bilan','consultation'=>'Consultation','procedure'=>'Procédure','follow_up'=>'Suivi','emergency'=>'Urgence'][$appointment->type] ?? ucfirst($appointment->type) }}</span>
                </div>
                @if($appointment->reason)<div class="md:col-span-2"><span class="text-slate-500">Motif :</span> <span class="ml-2">{{ $appointment->reason }}</span></div>@endif
                @if($appointment->notes)<div class="md:col-span-2"><span class="text-slate-500">Notes :</span> <span class="ml-2">{{ $appointment->notes }}</span></div>@endif
            </div>
        </div>

        {{-- Status panel --}}
        @if(!in_array($status, ['completed','cancelled']))
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-3">Mettre à jour le statut</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(['confirmed','in_progress','completed','cancelled','no_show'] as $s)
                    @if($status !== $s)
                    <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $s }}">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-sm font-medium border border-slate-200 hover:bg-slate-50
                            {{ $s === 'completed' ? 'text-emerald-700 border-emerald-200 bg-emerald-50' : '' }}
                            {{ $s === 'cancelled' ? 'text-red-700 border-red-200 bg-red-50' : '' }}">
                            {{ $statusLabels[$s] ?? ucfirst($s) }}
                        </button>
                    </form>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Treatment records --}}
        <div class="bg-white rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Dossiers de traitement</h3>
                @if(in_array($user->role, ['doctor','super_admin','admin']))
                <a href="{{ route('treatment-records.create', ['patient_id' => $appointment->patient_id, 'appointment_id' => $appointment->id]) }}"
                   class="text-sm text-blue-600 hover:underline">+ Ajouter</a>
                @endif
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($appointment->treatmentRecords as $record)
                <div class="flex items-center gap-4 px-5 py-3.5">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-slate-800">{{ $record->treatment->name }}</div>
                        @if($record->tooth_number)<div class="text-xs text-slate-400">Dent {{ $record->tooth_number }}</div>@endif
                        @if($record->notes)<div class="text-xs text-slate-500 mt-0.5">{{ Str::limit($record->notes, 80) }}</div>@endif
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $record->getStatusColorClass() }} shrink-0">{{ ucfirst($record->status) }}</span>
                </div>
                @empty
                <div class="px-5 py-6 text-center text-sm text-slate-400">Aucun dossier de traitement.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        @if($appointment->patient->patientProfile)
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-3">Informations patient</h3>
            <dl class="space-y-2 text-sm">
                @if($appointment->patient->patientProfile->allergies)
                <div class="bg-red-50 rounded-lg px-3 py-2">
                    <dt class="text-xs font-semibold text-red-700">Allergies :</dt>
                    <dd class="text-red-600 text-xs mt-0.5">{{ $appointment->patient->patientProfile->allergies }}</dd>
                </div>
                @endif
                @if($appointment->patient->patientProfile->blood_type)
                <div class="flex justify-between">
                    <dt class="text-slate-500">Groupe sanguin</dt>
                    <dd class="font-medium">{{ $appointment->patient->patientProfile->blood_type }}</dd>
                </div>
                @endif
            </dl>
        </div>
        @endif

        @if(!$user->isPatient())
            @if(!$appointment->invoice && in_array($user->role, ['super_admin','admin','secretary']))
            <a href="{{ route('invoices.create', ['appointment_id' => $appointment->id, 'patient_id' => $appointment->patient_id]) }}"
               class="flex items-center gap-2 w-full px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium hover:bg-emerald-100 border border-emerald-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Créer une facture
            </a>
            @elseif($appointment->invoice)
            <a href="{{ route('invoices.show', $appointment->invoice) }}"
               class="flex items-center gap-2 w-full px-4 py-3 rounded-xl bg-blue-50 text-blue-700 text-sm font-medium hover:bg-blue-100 border border-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Facture n°{{ $appointment->invoice->invoice_number }}
            </a>
            @endif
        @endif

        @if(in_array($user->role, ['doctor','super_admin','admin']))
        <a href="{{ route('cnam.create', $appointment) }}"
           class="flex items-center gap-2 w-full px-4 py-3 rounded-xl bg-violet-50 text-violet-700 text-sm font-medium hover:bg-violet-100 border border-violet-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Bulletin CNAM
        </a>
        @endif
    </div>
</div>
@endif

@push('scripts')
<script>
function ordonnanceForm() {
    return {
        items: [{ medicament: '', dosage: '', frequence: '', duree: '', instructions: '' }],
        add()      { this.items.push({ medicament: '', dosage: '', frequence: '', duree: '', instructions: '' }); },
        remove(i)  { if (this.items.length > 1) this.items.splice(i, 1); },
    };
}

function cnamForm() {
    const today = '{{ $appointment->appointment_date->format('Y-m-d') }}';
    return {
        acts: [{ date: today, dent: '', code_acte: '', cotation: '', honoraires: '' }],
        prostheses: [],
        addAct()           { this.acts.push({ date: today, dent: '', code_acte: '', cotation: '', honoraires: '' }); },
        removeAct(i)       { if (this.acts.length > 1) this.acts.splice(i, 1); },
        addProsthese()     { this.prostheses.push({ date: today, dents: '', code_acte: '', cotation: '', honoraires: '' }); },
        removeProsthese(i) { this.prostheses.splice(i, 1); },
    };
}
</script>
@endpush

@endsection
