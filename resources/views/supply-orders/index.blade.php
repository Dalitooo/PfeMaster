@extends('layouts.app')
@section('title', 'Bons de commande')
@section('page-title', 'Bons de commande')

@php
$statusLabels = [
    'draft'     => 'Brouillon',
    'sent'      => 'Envoyé',
    'confirmed' => 'Confirmé',
    'shipped'   => 'Expédié',
    'received'  => 'Reçu',
    'cancelled' => 'Annulé',
];
@endphp

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">{{ $orders->total() }} commandes</p>
    @if(!auth()->user()->isSupplier())
    <a href="{{ route('supply-orders.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nouvelle commande
    </a>
    @endif
</div>

<form method="GET" class="flex gap-3 mb-4">
    <select name="status" class="px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Tous les statuts</option>
        @foreach($statusLabels as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Filtrer</button>
    @if(request('status'))<a href="{{ route('supply-orders.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Effacer</a>@endif
</form>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Bon n°</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden md:table-cell">Fournisseur</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Commandé par</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Date</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Total</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Statut</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($orders as $order)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $order->order_number }}</td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-slate-600">{{ $order->supplier->company_name }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-slate-600">{{ $order->orderedBy->name }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-slate-600">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800">DT {{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-5 py-3.5"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $order->getStatusColorClass() }}">{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}</span></td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('supply-orders.show', $order) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 inline-flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-slate-400">Aucune commande trouvée.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())<div class="px-5 py-4 border-t">{{ $orders->links() }}</div>@endif
</div>
@endsection
