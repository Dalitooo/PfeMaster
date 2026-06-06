@extends('layouts.app')
@section('title', 'Fournitures')
@section('page-title', 'Fournitures')

@section('content')
@if($lowStockCount > 0)
<div class="flex items-center gap-3 mb-5 px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
    <svg class="w-5 h-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    <span><strong>{{ $lowStockCount }} article(s)</strong> en stock faible ou épuisé.</span>
    <a href="{{ route('supply-items.index', ['low_stock' => 1]) }}" class="ml-auto font-medium underline hover:text-amber-900">Voir les articles</a>
</div>
@endif

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">{{ $items->total() }} articles</p>
    <div class="flex gap-2">
        @if(!auth()->user()->isDoctor())
        <a href="{{ route('supply-categories.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">Catégories</a>
        <a href="{{ route('supply-items.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter un article
        </a>
        @endif
    </div>
</div>

<form method="GET" class="flex flex-wrap gap-3 mb-4">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher des articles..." class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-44">
    </div>
    <select name="supplier_id" class="px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Tous les fournisseurs</option>
        @foreach($suppliers as $s)<option value="{{ $s->id }}" @selected(request('supplier_id') == $s->id)>{{ $s->company_name }}</option>@endforeach
    </select>
    <label class="flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 text-sm cursor-pointer hover:bg-slate-50">
        <input type="checkbox" name="low_stock" value="1" @checked(request('low_stock'))>
        Stock faible uniquement
    </label>
    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Filtrer</button>
    @if(request()->hasAny(['search','supplier_id','low_stock']))<a href="{{ route('supply-items.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Effacer</a>@endif
</form>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Article</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden md:table-cell">Fournisseur</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Stock</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Prix</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($items as $item)
                <tr class="hover:bg-slate-50 {{ $item->isLowStock() ? 'bg-red-50' : '' }}">
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-slate-800">{{ $item->name }}</div>
                        <div class="text-xs text-slate-400">{{ $item->category->name }}@if($item->sku) · {{ $item->sku }}@endif</div>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-slate-600">{{ $item->supplier->company_name }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $item->isLowStock() ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $item->stock_quantity }} {{ $item->unit }}
                        </span>
                        @if($item->isLowStock())<div class="text-xs text-red-600 mt-0.5">Min : {{ $item->min_stock_level }}</div>@endif
                    </td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800">DT {{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            {{-- Consume form --}}
                            <form method="POST" action="{{ route('supply-items.consume', $item) }}"
                                  class="flex items-center gap-1" id="consume-{{ $item->id }}">
                                @csrf @method('PATCH')
                                <input type="number" name="quantity" min="1" max="{{ $item->stock_quantity ?: 1 }}" value="1"
                                       class="w-14 px-2 py-1 rounded-lg border border-slate-200 text-xs text-center focus:outline-none focus:ring-2 focus:ring-blue-400"
                                       {{ $item->stock_quantity == 0 ? 'disabled' : '' }}>
                                <button type="submit"
                                        class="flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 hover:bg-blue-50 hover:text-blue-700 disabled:opacity-40"
                                        {{ $item->stock_quantity == 0 ? 'disabled' : '' }}
                                        title="Enregistrer une consommation">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Utiliser
                                </button>
                            </form>

                            @if($item->isLowStock())
                            <a href="{{ route('supply-orders.create', ['item_id' => $item->id]) }}"
                               class="flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-100 text-amber-800 hover:bg-amber-200"
                               title="Envoyer une demande au fournisseur">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Demander
                            </a>
                            @endif

                            @if(!auth()->user()->isDoctor())
                            <a href="{{ route('supply-items.edit', $item) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('supply-items.destroy', $item) }}" onsubmit="return confirm('Supprimer cet article ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Aucun article trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($items->hasPages())<div class="px-5 py-4 border-t">{{ $items->links() }}</div>@endif
</div>
@endsection
