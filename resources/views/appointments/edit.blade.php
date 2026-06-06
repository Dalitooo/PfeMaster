@extends('layouts.app')
@section('title', 'Modifier le rendez-vous')
@section('page-title', 'Modifier le rendez-vous')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('appointments.show', $appointment) }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>
    <form method="POST" action="{{ route('appointments.update', $appointment) }}" class="space-y-5"
          onsubmit="return checkApptDate(this)">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <h3 class="font-semibold text-slate-800">Modifier le rendez-vous</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Patient</label>
                    <select name="patient_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" @selected(old('patient_id', $appointment->patient_id) == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Médecin</label>
                    <select name="doctor_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}" @selected(old('doctor_id', $appointment->doctor_id) == $d->id)>Dr. {{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Date</label>
                    <input type="text" name="appointment_date" id="edit_appt_date" required readonly
                           value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                           placeholder="jj/mm/aaaa"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Heure</label>
                    <input type="time" name="appointment_time" id="edit_appt_time" required
                           value="{{ old('appointment_time', $appointment->appointment_date->format('H:i')) }}"
                           min="09:00" max="19:00" step="900"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p id="edit_date_error" class="mt-1 text-xs text-red-600 hidden"></p>
                    @error('appointment_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-400">Lun–Ven : 09h00–19h00 · Sam : 09h00–13h00 · Dim : fermé</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Durée (minutes)</label>
                    <select name="duration_minutes" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach([15, 30, 45, 60, 90, 120] as $d)
                            <option value="{{ $d }}" @selected(old('duration_minutes', $appointment->duration_minutes) == $d)>{{ $d }} minutes</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Type</label>
                    <select name="type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['checkup','consultation','procedure','follow_up','emergency'] as $t)
                            <option value="{{ $t }}" @selected(old('type', $appointment->type) === $t)>{{ ucwords(str_replace('_', ' ', $t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Statut</label>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['pending','confirmed','in_progress','completed','cancelled','no_show'] as $s)
                            <option value="{{ $s }}" @selected(old('status', $appointment->status) === $s)>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Motif de la visite</label>
                    <textarea name="reason" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('reason', $appointment->reason) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes', $appointment->notes) }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Enregistrer les modifications</button>
            <a href="{{ route('appointments.show', $appointment) }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Annuler</a>
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
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('edit_appt_date');
    if (el) {
        flatpickr(el, {
            locale: flatpickr.l10ns.fr,
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            altInputClass: 'w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer',
            defaultDate: el.value || null,
            disableMobile: true,
            disable: [d => d.getDay() === 0],
        });
    }
});

function checkApptDate(form) {
    const dateInput = document.getElementById('edit_appt_date');
    const timeInput = document.getElementById('edit_appt_time');
    const errEl = document.getElementById('edit_date_error');
    if (!dateInput.value || !timeInput.value) return true;
    const d = new Date(dateInput.value + 'T' + timeInput.value);
    const dow = d.getDay();
    const minutes = d.getHours() * 60 + d.getMinutes();
    let msg = '';
    if (dow === 0) {
        msg = 'Les rendez-vous ne sont pas disponibles le dimanche.';
    } else if (dow === 6 && (minutes < 540 || minutes >= 780)) {
        msg = 'Le samedi, les rendez-vous sont disponibles de 09h00 à 13h00.';
    } else if (dow !== 6 && (minutes < 540 || minutes >= 1140)) {
        msg = 'Du lundi au vendredi, les rendez-vous sont disponibles de 09h00 à 19h00.';
    }
    if (msg) {
        errEl.textContent = msg;
        errEl.classList.remove('hidden');
        timeInput.classList.add('border-red-400');
        return false;
    }
    errEl.classList.add('hidden');
    timeInput.classList.remove('border-red-400');
    return true;
}
</script>
@endpush
@endsection
