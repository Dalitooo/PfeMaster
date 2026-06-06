@extends('layouts.app')
@section('title', 'Modifier la fourniture')
@section('page-title', 'Modifier la fourniture')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('supply-items.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>
    <form method="POST" action="{{ route('supply-items.update', $supplyItem) }}" class="space-y-5">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Fournisseur</label>
                    <select name="supplier_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($suppliers as $s)<option value="{{ $s->id }}" @selected(old('supplier_id', $supplyItem->supplier_id) == $s->id)>{{ $s->company_name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Catégorie</label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $supplyItem->category_id) == $c->id)>{{ $c->name }}</option>@endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Affecter au médecin</label>
                    <select name="doctor_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Stock partagé (non affecté) —</option>
                        @foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id', $supplyItem->doctor_id) == $d->id)>{{ $d->name }}</option>@endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom de l'article</label>
                    <input type="text" name="name" value="{{ old('name', $supplyItem->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">SKU</label><input type="text" name="sku" value="{{ old('sku', $supplyItem->sku) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Unité</label><input type="text" name="unit" value="{{ old('unit', $supplyItem->unit) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Prix unitaire (DT)</label><input type="number" name="unit_price" value="{{ old('unit_price', $supplyItem->unit_price) }}" min="0" step="0.01" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Quantité en stock</label><input type="number" name="stock_quantity" value="{{ old('stock_quantity', $supplyItem->stock_quantity) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Niveau de stock minimum</label><input type="number" name="min_stock_level" value="{{ old('min_stock_level', $supplyItem->min_stock_level) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="flex items-center gap-2 mt-auto">
                    <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $supplyItem->is_active)) class="rounded">
                    <label for="is_active" class="text-sm text-slate-700">Article actif</label>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Enregistrer les modifications</button>
            <a href="{{ route('supply-items.index') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Annuler</a>
        </div>
    </form>
</div>
@endsection
