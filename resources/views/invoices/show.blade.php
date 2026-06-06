@extends('layouts.app')
@section('title', 'Facture ' . $invoice->invoice_number)
@section('page-title', 'Facture ' . $invoice->invoice_number)

@section('content')
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('invoices.index') }}" class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour
    </a>
    <div class="flex items-center gap-2">
        <a href="{{ route('invoices.print', $invoice) }}" target="_blank"
           class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimer
        </a>
        @if($invoice->status === 'issued' && in_array(auth()->user()->role, ['super_admin','admin','secretary']))
            <form method="POST" action="{{ route('invoices.pay', $invoice) }}">
                @csrf @method('PATCH')
                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">Marquer comme payée</button>
            </form>
        @endif
        @if(!in_array($invoice->status, ['paid','cancelled']) && in_array(auth()->user()->role, ['super_admin','admin','secretary']))
            <form method="POST" action="{{ route('invoices.cancel', $invoice) }}" onsubmit="return confirm('Annuler cette facture ?')">
                @csrf @method('PATCH')
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-50 text-red-700 text-sm font-medium hover:bg-red-100">Annuler</button>
            </form>
        @endif
    </div>
</div>

<div class="max-w-3xl">
    <div class="bg-white rounded-2xl border border-slate-200 p-8">
        {{-- Header --}}
        <div class="flex justify-between items-start mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="font-bold text-slate-800 text-lg">SmileCare Cabinet Dentaire</span>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-slate-800">{{ $invoice->invoice_number }}</div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->getStatusColorClass() }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8 text-sm">
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Facturé à</div>
                <div class="font-semibold text-slate-800">{{ $invoice->patient->name }}</div>
                <div class="text-slate-500">{{ $invoice->patient->email }}</div>
                @if($invoice->patient->phone)<div class="text-slate-500">{{ $invoice->patient->phone }}</div>@endif
            </div>
            <div class="text-right">
                <div class="space-y-1 text-slate-600">
                    <div><span class="text-slate-400">Date de facture :</span> {{ $invoice->created_at->format('M j, Y') }}</div>
                    @if($invoice->due_date)<div><span class="text-slate-400">Échéance :</span> {{ $invoice->due_date->format('M j, Y') }}</div>@endif
                    @if($invoice->paid_at)<div><span class="text-slate-400">Payée le :</span> {{ $invoice->paid_at->format('M j, Y') }}</div>@endif
                    <div><span class="text-slate-400">Émise par :</span> {{ $invoice->issuedBy->name }}</div>
                </div>
            </div>
        </div>

        <table class="w-full text-sm mb-6">
            <thead><tr class="border-b-2 border-slate-200">
                <th class="text-left py-2.5 font-semibold text-slate-700">Description</th>
                <th class="text-right py-2.5 font-semibold text-slate-700">Qté</th>
                <th class="text-right py-2.5 font-semibold text-slate-700">Prix unitaire</th>
                <th class="text-right py-2.5 font-semibold text-slate-700">Sous-total</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($invoice->items as $item)
                    <tr>
                        <td class="py-3 text-slate-700">{{ $item->description }}</td>
                        <td class="py-3 text-right text-slate-600">{{ $item->quantity }}</td>
                        <td class="py-3 text-right text-slate-600">DT {{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-3 text-right font-medium text-slate-800">DT {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end">
            <div class="w-56 space-y-2 text-sm">
                <div class="flex justify-between text-slate-600"><span>Sous-total</span><span>DT {{ number_format($invoice->subtotal, 2) }}</span></div>
                @if($invoice->discount > 0)<div class="flex justify-between text-emerald-600"><span>Remise</span><span>− DT {{ number_format($invoice->discount, 2) }}</span></div>@endif
                @if($invoice->tax > 0)<div class="flex justify-between text-slate-600"><span>Taxe</span><span>DT {{ number_format($invoice->tax, 2) }}</span></div>@endif
                <div class="flex justify-between font-bold text-slate-900 text-base border-t pt-2">
                    <span>Total</span><span>DT {{ number_format($invoice->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if($invoice->notes)
            <div class="mt-6 pt-5 border-t border-slate-100 text-sm text-slate-500">
                <span class="font-medium text-slate-700">Notes :</span> {{ $invoice->notes }}
            </div>
        @endif
    </div>
</div>
@endsection
