@extends('layouts.app')
@section('title', 'Modifier le fournisseur')
@section('page-title', 'Modifier le fournisseur')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('suppliers.show', $supplier) }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>
    <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-5">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom de l'entreprise</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Nom du contact</label><input type="text" name="contact_name" value="{{ old('contact_name', $supplier->contact_name) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Téléphone</label><input type="tel" name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">E-mail</label><input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Ville</label><input type="text" name="city" value="{{ old('city', $supplier->city) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse</label><input type="text" name="address" value="{{ old('address', $supplier->address) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Site web</label><input type="url" name="website" value="{{ old('website', $supplier->website) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="flex items-center gap-2 mt-auto">
                    <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $supplier->is_active)) class="rounded">
                    <label for="is_active" class="text-sm text-slate-700">Fournisseur actif</label>
                </div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label><textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes', $supplier->notes) }}</textarea></div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Enregistrer les modifications</button>
            <a href="{{ route('suppliers.show', $supplier) }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Annuler</a>
        </div>
    </form>
</div>
@endsection
