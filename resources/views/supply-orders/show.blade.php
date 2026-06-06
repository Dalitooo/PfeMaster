@extends('layouts.app')
@section('title', 'Bon de commande')
@section('page-title', 'Bon de commande ' . $supplyOrder->order_number)

@php
$statusLabels = [
    'draft'     => 'Brouillon',
    'sent'      => 'Envoyé',
    'confirmed' => 'Confirmé',
    'shipped'   => 'Expédié',
    'received'  => 'Reçu',
    'cancelled' => 'Annulé',
];
$transitionLabels = [
    'sent'      => 'Marquer comme envoyé',
    'confirmed' => 'Confirmer la commande',
    'shipped'   => 'Marquer comme expédié',
    'received'  => 'Marquer comme reçu',
    'cancelled' => 'Annuler la commande',
];
@endphp

@section('content')
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('supply-orders.index') }}" class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux commandes
    </a>
    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $supplyOrder->getStatusColorClass() }}">
        {{ $statusLabels[$supplyOrder->status] ?? ucfirst($supplyOrder->status) }}
    </span>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="grid md:grid-cols-3 gap-5 text-sm mb-5">
                <div>
                    <div class="text-slate-500 mb-1">Fournisseur</div>
                    <div class="font-semibold text-slate-800">{{ $supplyOrder->supplier->company_name }}</div>
                </div>
                <div>
                    <div class="text-slate-500 mb-1">Commandé par</div>
                    <div class="font-semibold text-slate-800">{{ $supplyOrder->orderedBy->name }}</div>
                </div>
                <div>
                    <div class="text-slate-500 mb-1">Date de commande</div>
                    <div class="font-semibold text-slate-800">{{ $supplyOrder->created_at->format('d/m/Y') }}</div>
                </div>
                @if($supplyOrder->expected_at)
                <div>
                    <div class="text-slate-500 mb-1">Livraison prévue</div>
                    <div class="font-semibold text-slate-800">{{ $supplyOrder->expected_at->format('d/m/Y') }}</div>
                </div>
                @endif
                @if($supplyOrder->received_at)
                <div>
                    <div class="text-slate-500 mb-1">Reçu le</div>
                    <div class="font-semibold text-slate-800">{{ $supplyOrder->received_at->format('d/m/Y') }}</div>
                </div>
                @endif
            </div>
            @if($supplyOrder->notes)
                <div class="text-sm text-slate-600 bg-slate-50 rounded-xl px-4 py-3">{{ $supplyOrder->notes }}</div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Articles commandés</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-5 py-2.5 font-semibold text-slate-600">Article</th>
                        <th class="text-right px-5 py-2.5 font-semibold text-slate-600">Qté</th>
                        <th class="text-right px-5 py-2.5 font-semibold text-slate-600">Prix unitaire</th>
                        <th class="text-right px-5 py-2.5 font-semibold text-slate-600">Sous-total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($supplyOrder->items as $item)
                        <tr>
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-slate-800">{{ $item->item->name }}</div>
                                <div class="text-xs text-slate-400">{{ $item->item->category->name }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-right text-slate-700">{{ $item->quantity }} {{ $item->item->unit }}</td>
                            <td class="px-5 py-3.5 text-right text-slate-700">DT {{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold text-slate-800">DT {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="3" class="px-5 py-3 text-right font-semibold text-slate-700">Total</td>
                        <td class="px-5 py-3 text-right font-bold text-slate-900">DT {{ number_format($supplyOrder->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @php
        $user = auth()->user();
        if ($user->isSupplier()) {
            $allowedNext = ['draft' => ['confirmed'], 'sent' => ['confirmed'], 'confirmed' => ['shipped']];
        } elseif ($user->isDoctor()) {
            $allowedNext = ['shipped' => ['received']];
        } else {
            $allowedNext = [
                'sent'      => ['confirmed', 'cancelled'],
                'confirmed' => ['shipped', 'cancelled'],
                'shipped'   => ['received', 'cancelled'],
            ];
        }
        $next = $allowedNext[$supplyOrder->status] ?? [];
    @endphp

    @if(count($next))
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-3">Changer le statut</h3>
            <div class="space-y-2">
                @foreach($next as $s)
                    <form method="POST" action="{{ route('supply-orders.status', $supplyOrder) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $s }}">
                        <button type="submit" class="w-full px-4 py-2 rounded-xl text-sm font-medium border border-slate-200 hover:bg-slate-50
                            {{ $s === 'received'  ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                            {{ $s === 'cancelled' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                            {{ $transitionLabels[$s] ?? ucfirst($s) }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
