@extends('layouts.app')
@section('title', 'Bulletin de soin CNAM')
@section('page-title', 'Bulletin de soin CNAM')

@section('content')
<div class="max-w-3xl">

    <a href="{{ route('appointments.show', $appointment) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour au rendez-vous
    </a>

    {{-- Header info --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="flex-1">
            <div class="font-semibold text-slate-800">Patient : {{ $appointment->patient->name }}</div>
            <div class="text-sm text-slate-500">
                RDV du {{ $appointment->appointment_date->format('d/m/Y') }} —
                Dr. {{ $appointment->doctor->name }}
                @if($appointment->doctor->doctorProfile?->specialization)
                    · {{ $appointment->doctor->doctorProfile->specialization }}
                @endif
            </div>
        </div>
        <div class="text-right text-xs text-slate-400">
            <div class="font-medium text-slate-600">CNAM</div>
            <div>Bulletin de remboursement</div>
        </div>
    </div>

    <form method="POST" action="{{ route('cnam.store', $appointment) }}"
          x-data="cnamForm()">
        @csrf

        {{-- ── Consultations & Actes de soins dentaires ──────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-200 mb-6">
            <div class="px-5 py-4 border-b border-slate-100 bg-blue-50 rounded-t-2xl">
                <h3 class="font-bold text-blue-900 text-sm uppercase tracking-wider">Consultations et actes de soins dentaires</h3>
                <p class="text-xs text-blue-700 mt-0.5">Il est indispensable d'indiquer la dent traitée et de désigner les actes pratiqués.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600 w-28">Date</th>
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600 w-20">Dent</th>
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600">Code acte</th>
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600 w-28">Cotation</th>
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600 w-28">Honoraires (DT)</th>
                            <th class="px-3 py-2.5 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, i) in acts" :key="i">
                            <tr class="border-b border-slate-100">
                                <td class="px-2 py-2">
                                    <input type="date" :name="`dental_acts[${i}][date]`" x-model="row.date"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" :name="`dental_acts[${i}][dent]`" x-model="row.dent"
                                           placeholder="ex: 11"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" :name="`dental_acts[${i}][code_acte]`" x-model="row.code_acte"
                                           placeholder="ex: D01, SC1…"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" :name="`dental_acts[${i}][cotation]`" x-model="row.cotation"
                                           placeholder="ex: K50"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" :name="`dental_acts[${i}][honoraires]`" x-model="row.honoraires"
                                           placeholder="ex: 30.000"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <button type="button" @click="removeAct(i)"
                                            class="p-1 rounded text-slate-300 hover:text-red-500 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3 border-t border-slate-100">
                <button type="button" @click="addAct()"
                        class="flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter une ligne
                </button>
            </div>
        </div>

        {{-- ── Prothèses dentaires ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-slate-200 mb-6">
            <div class="px-5 py-4 border-b border-slate-100 bg-amber-50 rounded-t-2xl">
                <h3 class="font-bold text-amber-900 text-sm uppercase tracking-wider">Prothèses dentaires</h3>
                <p class="text-xs text-amber-700 mt-0.5">Laisser vide si aucune prothèse.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600 w-28">Date</th>
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600 w-24">Dents</th>
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600">Code acte</th>
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600 w-28">Cotation</th>
                            <th class="text-left px-3 py-2.5 text-xs font-semibold text-slate-600 w-28">Honoraires (DT)</th>
                            <th class="px-3 py-2.5 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, i) in prostheses" :key="i">
                            <tr class="border-b border-slate-100">
                                <td class="px-2 py-2">
                                    <input type="date" :name="`prostheses[${i}][date]`" x-model="row.date"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" :name="`prostheses[${i}][dents]`" x-model="row.dents"
                                           placeholder="ex: 11,21"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" :name="`prostheses[${i}][code_acte]`" x-model="row.code_acte"
                                           placeholder="ex: PD01"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" :name="`prostheses[${i}][cotation]`" x-model="row.cotation"
                                           placeholder="ex: P50"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="text" :name="`prostheses[${i}][honoraires]`" x-model="row.honoraires"
                                           placeholder="ex: 150.000"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-2 py-2">
                                    <button type="button" @click="removeProsthese(i)"
                                            class="p-1 rounded text-slate-300 hover:text-red-500 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3 border-t border-slate-100">
                <button type="button" @click="addProsthese()"
                        class="flex items-center gap-1.5 text-sm text-amber-600 hover:text-amber-700 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter une prothèse
                </button>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Générer le bulletin
            </button>
            <a href="{{ route('appointments.show', $appointment) }}"
               class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                Annuler
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function cnamForm() {
    const today = new Date().toISOString().split('T')[0];
    return {
        acts: [{ date: today, dent: '', code_acte: '', cotation: '', honoraires: '' }],
        prostheses: [],
        addAct()      { this.acts.push({ date: today, dent: '', code_acte: '', cotation: '', honoraires: '' }); },
        removeAct(i)  { if (this.acts.length > 1) this.acts.splice(i, 1); },
        addProsthese()       { this.prostheses.push({ date: today, dents: '', code_acte: '', cotation: '', honoraires: '' }); },
        removeProsthese(i)   { this.prostheses.splice(i, 1); },
    };
}
</script>
@endpush
@endsection
