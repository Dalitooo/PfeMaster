@extends('layouts.app')
@section('title', $supplier->company_name)
@section('page-title', $supplier->company_name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux fournisseurs
    </a>
    <a href="{{ route('suppliers.edit', $supplier) }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Modifier
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-3">Coordonnées</h3>
            <dl class="space-y-2 text-sm">
                @if($supplier->contact_name)<div class="flex justify-between"><dt class="text-slate-500">Contact</dt><dd class="font-medium">{{ $supplier->contact_name }}</dd></div>@endif
                @if($supplier->phone)<div class="flex justify-between"><dt class="text-slate-500">Téléphone</dt><dd>{{ $supplier->phone }}</dd></div>@endif
                @if($supplier->email)<div class="flex justify-between"><dt class="text-slate-500">E-mail</dt><dd>{{ $supplier->email }}</dd></div>@endif
                @if($supplier->city)<div class="flex justify-between"><dt class="text-slate-500">Ville</dt><dd>{{ $supplier->city }}</dd></div>@endif
                @if($supplier->website)<div><dt class="text-slate-500">Site web</dt><dd><a href="{{ $supplier->website }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $supplier->website }}</a></dd></div>@endif
            </dl>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Fournitures ({{ $supplier->items->count() }})</h3>
                <a href="{{ route('supply-items.create', ['supplier_id' => $supplier->id]) }}" class="text-sm text-blue-600 hover:underline">+ Ajouter un article</a>
            </div>
            <table class="w-full text-sm">
                <thead><tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-5 py-2.5 font-semibold text-slate-600">Article</th>
                    <th class="text-left px-5 py-2.5 font-semibold text-slate-600 hidden md:table-cell">Catégorie</th>
                    <th class="text-left px-5 py-2.5 font-semibold text-slate-600">Stock</th>
                    <th class="text-left px-5 py-2.5 font-semibold text-slate-600">Prix</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($supplier->items as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="font-medium text-slate-800">{{ $item->name }}</div>
                                @if($item->sku)<div class="text-xs text-slate-400">SKU : {{ $item->sku }}</div>@endif
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell text-slate-600">{{ $item->category->name }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $item->isLowStock() ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $item->stock_quantity }} {{ $item->unit }}
                                </span>
                            </td>
                            <td class="px-5 py-3 font-semibold text-slate-800">DT {{ number_format($item->unit_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-sm text-slate-400">Aucun article pour l'instant.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Commandes récentes</h3>
                <a href="{{ route('supply-orders.create', ['supplier_id' => $supplier->id]) }}" class="text-sm text-blue-600 hover:underline">+ Nouvelle commande</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($supplier->orders->take(5) as $order)
                    <a href="{{ route('supply-orders.show', $order) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">{{ $order->order_number }}</div>
                            <div class="text-xs text-slate-400">{{ $order->created_at->format('M j, Y') }}</div>
                        </div>
                        <div class="text-sm font-bold">DT {{ number_format($order->total_amount, 2) }}</div>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $order->getStatusColorClass() }}">{{ ucfirst($order->status) }}</span>
                    </a>
                @empty
                    <div class="px-5 py-6 text-center text-sm text-slate-400">Aucune commande pour l'instant.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
