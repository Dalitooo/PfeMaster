@extends('layouts.app')
@section('title', 'Modifier le dossier de traitement')
@section('page-title', 'Modifier le dossier de traitement')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('treatment-records.show', $treatmentRecord) }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>
    <form method="POST" action="{{ route('treatment-records.update', $treatmentRecord) }}" class="space-y-5">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Patient</label>
                    <select name="patient_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $treatmentRecord->patient_id) == $p->id)>{{ $p->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Médecin</label>
                    <select name="doctor_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id', $treatmentRecord->doctor_id) == $d->id)>Dr. {{ $d->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Traitement</label>
                    <select name="treatment_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($treatments as $t)<option value="{{ $t->id }}" @selected(old('treatment_id', $treatmentRecord->treatment_id) == $t->id)>{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Numéro de dent</label>
                    <input type="text" name="tooth_number" value="{{ old('tooth_number', $treatmentRecord->tooth_number) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Statut</label>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['planned','in_progress','completed','cancelled'] as $s)
                            <option value="{{ $s }}" @selected(old('status', $treatmentRecord->status) === $s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Coût (DT)</label>
                    <input type="number" name="cost" value="{{ old('cost', $treatmentRecord->cost) }}" min="0" step="0.01" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Date planifiée</label>
                    <input type="date" name="scheduled_date" value="{{ old('scheduled_date', $treatmentRecord->scheduled_date?->format('Y-m-d')) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Date de réalisation</label>
                    <input type="date" name="completed_date" value="{{ old('completed_date', $treatmentRecord->completed_date?->format('Y-m-d')) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes', $treatmentRecord->notes) }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Enregistrer les modifications</button>
            <a href="{{ route('treatment-records.show', $treatmentRecord) }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Annuler</a>
        </div>
    </form>
</div>
@endsection
