@extends('layouts.app')
@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex items-center gap-5 mb-6">
            <img src="{{ auth()->user()->getAvatarUrl() }}" class="w-16 h-16 rounded-2xl object-cover">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h2>
                <p class="text-slate-500 text-sm">{{ auth()->user()->getRoleLabel() }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Téléphone</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse</label>
                    <input type="text" name="address" value="{{ old('address', auth()->user()->address) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- CNAM section (patients only) --}}
            @if(auth()->user()->isPatient())
            <div class="border-t border-slate-100 pt-4 mt-2">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 rounded bg-violet-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-700">Informations CNAM</h4>
                    <span class="text-xs text-slate-400">(pour le bulletin de remboursement)</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Identifiant unique CNAM</label>
                        <input type="text" name="cnam_id"
                               value="{{ old('cnam_id', $user->patientProfile?->cnam_id) }}"
                               placeholder="ex : 12345678"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                        @error('cnam_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Type d'assurance</label>
                        <select name="cnam_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 bg-white">
                            <option value="">-- Sélectionner --</option>
                            <option value="cnss" {{ old('cnam_type', $user->patientProfile?->cnam_type) === 'cnss' ? 'selected' : '' }}>CNSS</option>
                            <option value="cnrps" {{ old('cnam_type', $user->patientProfile?->cnam_type) === 'cnrps' ? 'selected' : '' }}>CNRPS</option>
                        </select>
                        @error('cnam_type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            @endif

            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Mettre à jour le profil</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Changer le mot de passe</h3>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-4 max-w-sm">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Mot de passe actuel</label>
                    <input type="password" name="current_password" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('current_password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nouveau mot de passe</label>
                    <input type="password" name="password" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-700 text-white text-sm font-medium hover:bg-slate-800">Mettre à jour le mot de passe</button>
        </form>
    </div>
</div>
@endsection
