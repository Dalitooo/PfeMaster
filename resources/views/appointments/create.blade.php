@extends('layouts.app')
@section('title', 'Prendre un rendez-vous')
@section('page-title', 'Prendre un rendez-vous')

@section('content')
@php
    $cabinetsJson = $cabinets->map(fn($c) => [
        'id'          => $c->id,
        'name'        => $c->name,
        'description' => $c->description,
        'specialty'   => $c->doctor?->doctorProfile?->specialization ?? 'General',
        'doctor'      => $c->doctor ? [
            'id'   => $c->doctor->id,
            'name' => $c->doctor->name,
        ] : null,
        'secretary'   => $c->secretary ? ['name' => $c->secretary->name] : null,
    ])->values()->toJson();
@endphp

<div x-data="bookingWizard({{ $cabinetsJson }}, {{ $selfBook ? 'true' : 'false' }}, {{ ($isSecretary ?? false) ? 'true' : 'false' }}, {{ ($secretaryCabinet ?? null)?->id ?? 'null' }})"
     class="max-w-2xl">

    {{-- Back link --}}
    <a href="{{ route('appointments.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>

    {{-- Step indicator --}}
    <div class="w-full mb-8 bg-white rounded-2xl border border-slate-200 px-6 pt-5 pb-6">

        {{-- Header: step counter + current step name --}}
        <div class="flex items-center justify-between mb-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">
                Étape <span x-text="currentStepIndex + 1"></span> sur <span x-text="steps.length"></span>
            </p>
            <p class="text-sm font-bold text-blue-600" x-text="steps[currentStepIndex]"></p>
        </div>

        {{-- Nodes + connectors --}}
        <div class="flex items-start w-full">
            <template x-for="(label, i) in steps" :key="i">
                <div class="flex items-start" :class="i < steps.length - 1 ? 'flex-1' : ''">

                    {{-- Circle + label + subtitle --}}
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold shrink-0 transition-all duration-300"
                             :class="{
                                 'bg-emerald-500 text-white shadow shadow-emerald-200':                       isCompleted(i),
                                 'bg-blue-600 text-white ring-4 ring-blue-100 shadow shadow-blue-200':        i === currentStepIndex,
                                 'bg-white text-slate-300 border-2 border-slate-200':                        i > currentStepIndex,
                             }">
                            <template x-if="isCompleted(i)">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="!isCompleted(i)">
                                <span x-text="i + 1"></span>
                            </template>
                        </div>

                        <span class="text-xs font-semibold whitespace-nowrap transition-colors"
                              :class="{
                                  'text-emerald-600': isCompleted(i),
                                  'text-blue-600':    i === currentStepIndex,
                                  'text-slate-400':   i > currentStepIndex,
                              }"
                              x-text="label"></span>

                        <span class="text-xs text-slate-400 whitespace-nowrap text-center hidden sm:block"
                              x-text="stepSubtitles[i]"></span>
                    </div>

                    {{-- Connector --}}
                    <template x-if="i < steps.length - 1">
                        <div class="flex-1 h-0.5 mx-3 mt-5 rounded-full transition-all duration-500"
                             :class="isCompleted(i) ? 'bg-emerald-400' : 'bg-slate-200'">
                        </div>
                    </template>

                </div>
            </template>
        </div>
    </div>

    <form method="POST" action="{{ route('appointments.store') }}" @submit.prevent="submitForm">
        @csrf
        @if($secretaryCabinet ?? null)
            <input type="hidden" name="cabinet_id" value="{{ $secretaryCabinet->id }}">
        @else
            <input type="hidden" name="cabinet_id" :value="cabinetId">
        @endif
        <input type="hidden" name="patient_id" :value="patientId">

        {{-- ── Step 0 : Patient (staff only) ──────────────────────────────── --}}
        @if(!$selfBook)
        <div x-show="step === 0" x-cloak class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Sélectionner un patient</h3>
                <div class="relative mb-3">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="patientSearch" placeholder="Rechercher un patient par nom…"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="space-y-1 max-h-64 overflow-y-auto">
                    @foreach($patients as $patient)
                    <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-200 border border-transparent"
                           x-show="!'{{ strtolower($patient->name) }}'.includes(patientSearch.toLowerCase()) ? false : true">
                        <input type="radio" name="_patient_pick" value="{{ $patient->id }}"
                               @change="patientId = {{ $patient->id }}"
                               class="accent-blue-600 shrink-0">
                        <img src="{{ $patient->getAvatarUrl() }}" class="w-8 h-8 rounded-full object-cover shrink-0">
                        <div>
                            <div class="text-sm font-medium text-slate-800">{{ $patient->name }}</div>
                            <div class="text-xs text-slate-400">{{ $patient->email }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" @click="isSecretary ? step = 3 : step = 1" :disabled="!patientId"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed">
                    Continuer
                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
        @else
        <input type="hidden" name="_self_book" value="1">
        @endif

        {{-- ── Step 1 : Specialty ──────────────────────────────────────────── --}}
        <div x-show="step === 1" x-cloak class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-1">De quel type de soins avez-vous besoin ?</h3>
                <p class="text-xs text-slate-500 mb-5">Choisissez une spécialité pour voir les cabinets disponibles.</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <template x-for="spec in specialties" :key="spec.name">
                        <button type="button" @click="selectSpecialty(spec.name)"
                                class="flex flex-col items-center gap-2.5 p-4 rounded-xl border-2 border-slate-100 hover:border-blue-300 hover:bg-blue-50 transition-colors text-center group">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-colors"
                                 :style="`background-color: ${spec.color}20;`">
                                <svg class="w-5 h-5 transition-colors" :style="`color: ${spec.color}`"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="spec.icon"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-slate-700" x-text="spec.name"></div>
                                <div class="text-xs text-slate-400 mt-0.5" x-text="spec.count + ' cabinet' + (spec.count !== 1 ? 's' : '')"></div>
                            </div>
                        </button>
                    </template>

                    {{-- Show all --}}
                    <button type="button" @click="selectSpecialty(null)"
                            class="flex flex-col items-center gap-2.5 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-blue-300 hover:bg-blue-50 transition-colors text-center">
                        <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-700">Tout afficher</div>
                            <div class="text-xs text-slate-400 mt-0.5" x-text="cabinets.length + ' cabinets'"></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Step 2 : Pick Office ────────────────────────────────────────── --}}
        <div x-show="step === 2" x-cloak class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-slate-800">Choisir un cabinet médical</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            <span x-show="selectedSpecialty" x-text="selectedSpecialty"></span>
                            <span x-show="!selectedSpecialty">Toutes spécialités</span>
                            · <span x-text="filteredCabinets.length"></span> disponible(s)
                        </p>
                    </div>
                    <button type="button" @click="step = 1"
                            class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Changer de spécialité
                    </button>
                </div>

                {{-- Search --}}
                <div class="relative mb-4">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="officeSearch" placeholder="Rechercher par cabinet ou médecin…"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Office list --}}
                <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                    <template x-for="cabinet in filteredCabinets" :key="cabinet.id">
                        <button type="button" @click="selectCabinet(cabinet.id)"
                                class="w-full flex items-start gap-4 p-4 rounded-xl border-2 text-left transition-colors hover:border-blue-400 hover:bg-blue-50"
                                :class="cabinetId === cabinet.id ? 'border-blue-500 bg-blue-50' : 'border-slate-100'">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-slate-800 text-sm" x-text="cabinet.name"></div>
                                <div class="text-xs text-slate-500 mt-0.5" x-text="cabinet.description" x-show="cabinet.description"></div>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                                    <template x-if="cabinet.doctor">
                                        <div class="flex items-center gap-1.5">
                                            <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(cabinet.doctor.name)}&background=2563EB&color=fff&size=32`"
                                                 class="w-5 h-5 rounded-full object-cover">
                                            <span class="text-xs text-slate-600" x-text="'Dr. ' + cabinet.doctor.name"></span>
                                            <span class="text-xs text-blue-600 font-medium" x-text="'· ' + cabinet.specialty"></span>
                                        </div>
                                    </template>
                                    <template x-if="!cabinet.doctor">
                                        <span class="text-xs text-slate-400 italic">Aucun médecin assigné</span>
                                    </template>
                                    <template x-if="cabinet.secretary">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span class="text-xs text-slate-500" x-text="cabinet.secretary.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div x-show="cabinetId === cabinet.id" class="shrink-0">
                                <div class="w-5 h-5 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>
                        </button>
                    </template>

                    <div x-show="filteredCabinets.length === 0" class="py-10 text-center text-slate-400 text-sm">
                        Aucun cabinet ne correspond à votre recherche.
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Step 3 : Details ────────────────────────────────────────────── --}}
        <div x-show="step === 3" x-cloak class="space-y-5">

            {{-- Selected office summary --}}
            <template x-if="selectedCabinet">
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-800" x-text="selectedCabinet.name"></div>
                            <div class="text-xs text-blue-700 mt-0.5"
                                 x-text="selectedCabinet.doctor ? 'Dr. ' + selectedCabinet.doctor.name + ' · ' + selectedCabinet.specialty : 'Aucun médecin assigné'"></div>
                        </div>
                    </div>
                    @if($selfBook)
                    <button type="button" @click="step = 2"
                            class="text-xs text-blue-600 hover:underline shrink-0">Modifier</button>
                    @endif
                </div>
            </template>

            {{-- Schedule form --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
                <h3 class="font-semibold text-slate-800 mb-2">Planning &amp; Détails</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Date <span class="text-red-500">*</span></label>
                        <input type="text" name="appointment_date" id="create_appt_date" x-model="apptDate"
                               value="{{ old('appointment_date') }}" readonly placeholder="jj/mm/aaaa"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Heure <span class="text-red-500">*</span></label>
                        <select name="appointment_time" x-model="apptTime" @change="validateDate()"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="dateError ? 'border-red-400 ring-2 ring-red-200' : ''">
                            <option value="">-- Choisir une heure --</option>
                            @php
                                for ($h = 9; $h < 19; $h++) {
                                    echo "<option value=\"" . sprintf('%02d:00', $h) . "\">" . sprintf('%02dh00', $h) . "</option>";
                                    echo "<option value=\"" . sprintf('%02d:30', $h) . "\">" . sprintf('%02dh30', $h) . "</option>";
                                }
                            @endphp
                        </select>
                        <p x-show="dateError" x-text="dateError" class="mt-1 text-xs text-red-600"></p>
                        @error('appointment_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-400">Lun–Ven : 09h00–19h00 · Sam : 09h00–13h00 · Dim : fermé</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Durée</label>
                        <select name="duration_minutes" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach([15 => '15 min', 30 => '30 min', 45 => '45 min', 60 => '1 heure', 90 => '1 h 30 min', 120 => '2 heures'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('duration_minutes', 30) == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                            @foreach(['checkup' => 'Bilan', 'consultation' => 'Consultation', 'procedure' => 'Procédure', 'follow_up' => 'Suivi', 'emergency' => 'Urgence'] as $val => $lbl)
                                <label class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-slate-200 cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 text-sm transition-colors">
                                    <input type="radio" name="type" value="{{ $val }}" class="accent-blue-600" @checked(old('type', 'consultation') === $val)>
                                    <span class="font-medium text-slate-700">{{ $lbl }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Motif de la visite</label>
                        <textarea name="reason" rows="2" placeholder="Brève description du motif de la visite…"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('reason') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes complémentaires</label>
                        <textarea name="notes" rows="2" placeholder="Notes supplémentaires…"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                    Confirmer le rendez-vous
                </button>
                <button type="button" @click="isSecretary ? (cabinets.length === 1 ? step = 0 : step = 2) : step = 2"
                        class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                    Retour
                </button>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
function bookingWizard(cabinets, selfBook, isSecretary, secretaryCabinetId) {
    const SPECIALTY_META = {
        'General Dentistry': { color: '#3B82F6', icon: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z' },
        'Orthodontics':      { color: '#8B5CF6', icon: 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18' },
        'Oral Surgery':      { color: '#EF4444', icon: 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z' },
        'Cosmetic':          { color: '#F59E0B', icon: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z' },
        'Periodontics':      { color: '#06B6D4', icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' },
        'General':           { color: '#64748B', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    };

    return {
        cabinets,
        selfBook,
        isSecretary,
        step: selfBook ? 1 : 0,
        patientId: null,
        patientSearch: '',
        cabinetId: null,
        selectedSpecialty: null,
        officeSearch: '',
        apptDate: '{{ old('appointment_date') }}',
        apptTime: '{{ old('appointment_time') }}',
        dateError: '',

        init() {
            if (secretaryCabinetId) {
                this.cabinetId = secretaryCabinetId;
            }
        },

        validateDate() {
            if (!this.apptDate || !this.apptTime) { this.dateError = ''; return; }
            const d = new Date(this.apptDate + 'T' + this.apptTime);
            const dow = d.getDay(); // 0=Sun
            const minutes = d.getHours() * 60 + d.getMinutes();
            if (dow === 0) {
                this.dateError = 'Les rendez-vous ne sont pas disponibles le dimanche.';
            } else if (dow === 6) {
                this.dateError = (minutes < 540 || minutes >= 780)
                    ? 'Le samedi, les rendez-vous sont disponibles de 09h00 à 13h00.'
                    : '';
            } else {
                this.dateError = (minutes < 540 || minutes >= 1140)
                    ? 'Du lundi au vendredi, les rendez-vous sont disponibles de 09h00 à 19h00.'
                    : '';
            }
        },

        get steps() {
            if (isSecretary) return ['Patient', 'Détails'];
            const base = ['Spécialité', 'Cabinet', 'Détails'];
            return selfBook ? base : ['Patient', ...base];
        },

        get currentStepIndex() {
            if (isSecretary) return this.step === 0 ? 0 : 1;
            return selfBook ? this.step - 1 : this.step;
        },

        get stepSubtitles() {
            if (isSecretary) return ['Trouver le patient', 'Date & détails'];
            const base = ['Filtrer par spécialité', 'Choisir votre cabinet', 'Date & détails'];
            return selfBook ? base : ['Trouver le patient', ...base];
        },

        get specialties() {
            const map = {};
            this.cabinets.forEach(c => {
                const s = c.specialty || 'General';
                if (!map[s]) map[s] = 0;
                map[s]++;
            });
            return Object.entries(map).map(([name, count]) => ({
                name,
                count,
                color: (SPECIALTY_META[name] || SPECIALTY_META['General']).color,
                icon:  (SPECIALTY_META[name] || SPECIALTY_META['General']).icon,
            }));
        },

        get filteredCabinets() {
            const q = this.officeSearch.toLowerCase();
            return this.cabinets.filter(c => {
                const matchSpec  = !this.selectedSpecialty || c.specialty === this.selectedSpecialty;
                const matchSearch = !q ||
                    c.name.toLowerCase().includes(q) ||
                    (c.doctor?.name || '').toLowerCase().includes(q) ||
                    c.specialty.toLowerCase().includes(q);
                return matchSpec && matchSearch;
            });
        },

        get selectedCabinet() {
            return this.cabinets.find(c => c.id === this.cabinetId) || null;
        },

        selectSpecialty(name) {
            this.selectedSpecialty = name;
            this.officeSearch = '';
            this.step = 2;
        },

        selectCabinet(id) {
            this.cabinetId = id;
            this.step = 3;
        },

        isCompleted(i) {
            const lastStep = this.steps.length - 1;
            if (i === lastStep) return false;
            return i < this.currentStepIndex;
        },

        submitForm(e) {
            this.validateDate();
            if (!this.apptDate || !this.apptTime) {
                this.dateError = 'Veuillez sélectionner une date et une heure.';
                return;
            }
            if (this.dateError) return;
            e.target.submit();
        },
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('create_appt_date');
    if (!el) return;
    flatpickr(el, {
        locale: flatpickr.l10ns.fr,
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        altInputClass: 'w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer',
        minDate: 'today',
        disableMobile: true,
        disable: [d => d.getDay() === 0],
        onChange(_, dateStr) {
            el.value = dateStr;
            el.dispatchEvent(new Event('input'));
        },
    });
});
</script>
@endpush
@endsection
