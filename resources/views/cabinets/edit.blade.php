@extends('layouts.app')
@section('title', 'Modifier le cabinet médical')
@section('page-title', 'Modifier le cabinet médical')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('cabinets.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux cabinets
    </a>

    <form method="POST" action="{{ route('cabinets.update', $cabinet) }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <h3 class="font-semibold text-slate-800">Informations du cabinet</h3>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom du cabinet <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $cabinet->name) }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                <input type="text" name="description" value="{{ old('description', $cabinet->description) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       @checked(old('is_active', $cabinet->is_active))
                       class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Actif (visible par les patients)</label>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <h3 class="font-semibold text-slate-800">Personnel assigné</h3>
            <p class="text-xs text-slate-500 -mt-2">Le médecin assigné sera automatiquement réservé lorsqu'un patient sélectionne ce cabinet.</p>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Médecin</label>
                <select name="doctor_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Non assigné —</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(old('doctor_id', $cabinet->doctor_id) == $doctor->id)>
                            {{ $doctor->name }}
                            @if($doctor->doctorProfile?->specialization)
                                — {{ $doctor->doctorProfile->specialization }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Secrétaire</label>
                <select name="secretary_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Non assignée —</option>
                    @foreach($secretaries as $secretary)
                        <option value="{{ $secretary->id }}" @selected(old('secretary_id', $cabinet->secretary_id) == $secretary->id)>
                            {{ $secretary->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                Enregistrer les modifications
            </button>
            <a href="{{ route('cabinets.index') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Annuler</a>
        </div>
    </form>
</div>
@endsection
