@extends('layouts.app')
@section('title', 'Nouvelle facture')
@section('page-title', 'Créer une facture')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>
    <form method="POST" action="{{ route('invoices.store') }}" class="space-y-5">
        @csrf
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un patient</option>
                        @foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $appointment?->patient_id) == $p->id)>{{ $p->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Date d'échéance</label>
                    <input type="date" name="due_date" value="{{ old('due_date', today()->addDays(30)->format('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @if($appointment)
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                @endif
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-800">Lignes de facturation</h3>
                <button type="button" id="add-line" class="flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajouter une ligne
                </button>
            </div>
            <div id="lines-container" class="space-y-3">
                <div class="line-row grid grid-cols-12 gap-3 items-end">
                    <div class="col-span-4">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Traitement (facultatif)</label>
                        <select name="items[0][treatment_id]" class="treatment-select w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Élément personnalisé</option>
                            @foreach($treatments as $t)<option value="{{ $t->id }}" data-price="{{ $t->price }}" data-name="{{ $t->name }}">{{ $t->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Description <span class="text-red-500">*</span></label>
                        <input type="text" name="items[0][description]" required class="line-desc w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Qté</label>
                        <input type="number" name="items[0][quantity]" value="1" min="1" required class="line-qty w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Prix</label>
                        <input type="number" name="items[0][unit_price]" value="0" min="0" step="0.01" required class="line-price w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-1 flex justify-end">
                        <button type="button" class="remove-line p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-100">
                <div class="grid grid-cols-2 gap-4 max-w-xs ml-auto text-sm">
                    <label class="text-slate-500 flex items-center">Remise (DT)</label>
                    <input type="number" name="discount" value="{{ old('discount', 0) }}" min="0" step="0.01" id="discount" class="px-3 py-1.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <label class="text-slate-500 flex items-center">Taxe (DT)</label>
                    <input type="number" name="tax" value="{{ old('tax', 0) }}" min="0" step="0.01" id="tax" class="px-3 py-1.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="text-slate-700 font-semibold">Total</span>
                    <span id="total-display" class="font-bold text-slate-900 py-1.5">DT 0.00</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Créer la facture</button>
            <a href="{{ route('invoices.index') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Annuler</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let lineIdx = 0;
const treatmentsData = @json($treatments->mapWithKeys(fn($t) => [$t->id => ['name' => $t->name, 'price' => $t->price]]));

function calcTotal() {
    let sub = 0;
    document.querySelectorAll('.line-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.line-qty')?.value || 0);
        const price = parseFloat(row.querySelector('.line-price')?.value || 0);
        sub += qty * price;
    });
    const disc = parseFloat(document.getElementById('discount').value || 0);
    const tax = parseFloat(document.getElementById('tax').value || 0);
    document.getElementById('total-display').textContent = 'DT ' + Math.max(0, sub - disc + tax).toFixed(2);
}

document.getElementById('lines-container').addEventListener('change', function(e) {
    if (!e.target.classList.contains('treatment-select')) return;
    const row = e.target.closest('.line-row');
    const tid = e.target.value;
    if (tid && treatmentsData[tid]) {
        row.querySelector('.line-desc').value = treatmentsData[tid].name;
        row.querySelector('.line-price').value = treatmentsData[tid].price;
    }
    calcTotal();
});
document.getElementById('lines-container').addEventListener('input', calcTotal);
document.getElementById('lines-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-line')) { e.target.closest('.line-row').remove(); calcTotal(); }
});
document.getElementById('discount').addEventListener('input', calcTotal);
document.getElementById('tax').addEventListener('input', calcTotal);

document.getElementById('add-line').addEventListener('click', function() {
    lineIdx++;
    const tpl = document.querySelector('.line-row').cloneNode(true);
    tpl.querySelectorAll('input').forEach(i => { i.value = i.type === 'number' ? (i.classList.contains('line-qty') ? 1 : 0) : ''; });
    tpl.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    tpl.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/\[0\]/, `[${lineIdx}]`);
    });
    document.getElementById('lines-container').appendChild(tpl);
});
</script>
@endpush
@endsection
