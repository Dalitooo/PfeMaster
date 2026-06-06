@extends('layouts.app')
@section('title', isset($prefillItem) ? 'Demande au fournisseur' : 'Nouveau bon de commande')
@section('page-title', isset($prefillItem) ? 'Demande au fournisseur' : 'Nouveau bon de commande')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('supply-orders.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>

    @if(session('warning'))
    <div class="flex items-center gap-3 mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
        <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <span>{{ session('warning') }}</span>
    </div>
    @endif

    @isset($prefillItem)
    <div class="flex items-center gap-3 mb-5 px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
        <svg class="w-5 h-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <span>Demande pour <strong>{{ $prefillItem->name }}</strong> — stock actuel : <strong>{{ $prefillItem->stock_quantity }} {{ $prefillItem->unit }}</strong> (min : {{ $prefillItem->min_stock_level }})</span>
    </div>
    @endisset
    <form method="POST" action="{{ route('supply-orders.store') }}" class="space-y-5" id="order-form">
        @csrf
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Fournisseur <span class="text-red-500">*</span></label>
                    <select name="supplier_id" id="supplier_select" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un fournisseur</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" data-items="{{ $s->items->toJson() }}"
                                @selected(old('supplier_id', isset($prefillItem) ? $prefillItem->supplier_id : '') == $s->id)>
                                {{ $s->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Livraison prévue</label>
                    <input type="date" name="expected_at" value="{{ old('expected_at') }}" min="{{ today()->addDay()->format('Y-m-d') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Médecin concerné <span class="text-red-500">*</span></label>
                    <select name="doctor_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un médecin</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-800">Articles commandés</h3>
                <button type="button" id="add-item" class="flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter un article
                </button>
            </div>

            <div id="items-container" class="space-y-3">
                <div class="item-row grid grid-cols-12 gap-3 items-end">
                    <div class="col-span-5">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Article</label>
                        <select name="items[0][item_id]" class="item-select w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Sélectionner un article</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Qté</label>
                        <input type="number" name="items[0][quantity]" min="1" value="1" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Prix unitaire</label>
                        <input type="number" name="items[0][unit_price]" min="0" step="0.01" value="0" required class="item-price w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2 flex justify-end">
                        <button type="button" class="remove-item p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex justify-end">
                <div class="text-right">
                    <span class="text-sm text-slate-500">Total : </span>
                    <span id="total-display" class="text-lg font-bold text-slate-800">DT 0.00</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Créer la commande</button>
            <a href="{{ route('supply-orders.index') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Annuler</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = 0;
let supplierItems = [];
@isset($prefillItem)
const prefillItemId = {{ $prefillItem->id }};
const prefillQty    = Math.max(1, {{ $prefillItem->min_stock_level }} - {{ $prefillItem->stock_quantity }});
@endisset

document.getElementById('supplier_select').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    supplierItems = opt.dataset.items ? JSON.parse(opt.dataset.items) : [];
    document.querySelectorAll('.item-select').forEach(updateItemSelect);
});

// Auto-trigger supplier change on page load when pre-filling
window.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('supplier_select');
    if (sel.value) {
        supplierItems = JSON.parse(sel.options[sel.selectedIndex].dataset.items || '[]');
        document.querySelectorAll('.item-select').forEach(updateItemSelect);
        @isset($prefillItem)
        const itemSel = document.querySelector('.item-select');
        if (itemSel) {
            itemSel.value = prefillItemId;
            itemSel.dispatchEvent(new Event('change'));
            const qtyInput = itemSel.closest('.item-row').querySelector('[name*="quantity"]');
            if (qtyInput) qtyInput.value = prefillQty;
            calcTotal();
        }
        @endisset
    }
});

function updateItemSelect(sel) {
    const val = sel.value;
    sel.innerHTML = '<option value="">Sélectionner un article</option>' + supplierItems.map(i =>
        `<option value="${i.id}" data-price="${i.unit_price}">${i.name} (${i.unit})</option>`
    ).join('');
    if (val) sel.value = val;
}

function buildRow(idx) {
    const div = document.createElement('div');
    div.className = 'item-row grid grid-cols-12 gap-3 items-end';
    div.innerHTML = `
        <div class="col-span-5">
            <select name="items[${idx}][item_id]" class="item-select w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Sélectionner un article</option>
                ${supplierItems.map(i => `<option value="${i.id}" data-price="${i.unit_price}">${i.name} (${i.unit})</option>`).join('')}
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${idx}][quantity]" min="1" value="1" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="col-span-3">
            <input type="number" name="items[${idx}][unit_price]" min="0" step="0.01" value="0" required class="item-price w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="col-span-2 flex justify-end">
            <button type="button" class="remove-item p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    `;
    div.querySelector('.item-select').addEventListener('change', onItemSelect);
    div.querySelector('[type=number]').addEventListener('input', calcTotal);
    div.querySelector('.item-price').addEventListener('input', calcTotal);
    div.querySelector('.remove-item').addEventListener('click', function() { div.remove(); calcTotal(); });
    return div;
}

document.getElementById('add-item').addEventListener('click', function() {
    itemIndex++;
    document.getElementById('items-container').appendChild(buildRow(itemIndex));
});

document.querySelector('#items-container').addEventListener('change', onItemSelect);
document.querySelector('#items-container').addEventListener('input', calcTotal);
document.querySelector('#items-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-item')) { e.target.closest('.item-row').remove(); calcTotal(); }
});

function onItemSelect(e) {
    if (!e.target.classList.contains('item-select')) return;
    const opt = e.target.options[e.target.selectedIndex];
    const price = opt.dataset.price || 0;
    e.target.closest('.item-row').querySelector('.item-price').value = price;
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('[name*="quantity"]')?.value || 0);
        const price = parseFloat(row.querySelector('.item-price')?.value || 0);
        total += qty * price;
    });
    document.getElementById('total-display').textContent = 'DT ' + total.toFixed(2);
}
</script>
@endpush
@endsection
