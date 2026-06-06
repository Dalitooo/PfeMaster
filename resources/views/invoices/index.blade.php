@extends('layouts.app')
@section('title', 'Factures')
@section('page-title', 'Factures')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">{{ $invoices->total() }} factures</p>
    @if(in_array(auth()->user()->role, ['super_admin','admin','secretary']))
        <a href="{{ route('invoices.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouvelle facture
        </a>
    @endif
</div>

<form method="GET" class="flex flex-wrap gap-3 mb-4">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-44">
    </div>
    <select name="status" class="px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Tous les statuts</option>
        @foreach(['draft','issued','paid','overdue','cancelled'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Filtrer</button>
    @if(request()->hasAny(['search','status']))<a href="{{ route('invoices.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Effacer</a>@endif
</form>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Facture n°</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden md:table-cell">Patient</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Date</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Échéance</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Total</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Statut</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($invoices as $invoice)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $invoice->invoice_number }}</td>
                    <td class="px-5 py-3.5 hidden md:table-cell">
                        <div class="flex items-center gap-2">
                            <img src="{{ $invoice->patient->getAvatarUrl() }}" class="w-6 h-6 rounded-full">
                            <span class="text-slate-700">{{ $invoice->patient->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-slate-600">{{ $invoice->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-slate-600">{{ $invoice->due_date?->format('M j, Y') ?? '—' }}</td>
                    <td class="px-5 py-3.5 font-bold text-slate-800">DT {{ number_format($invoice->total, 2) }}</td>
                    <td class="px-5 py-3.5"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $invoice->getStatusColorClass() }}">{{ ucfirst($invoice->status) }}</span></td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('invoices.show', $invoice) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @if($invoice->status === 'issued' && in_array(auth()->user()->role, ['super_admin','admin','secretary']))
                                <form method="POST" action="{{ route('invoices.pay', $invoice) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700">Marquer payée</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-slate-400">Aucune facture trouvée.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($invoices->hasPages())<div class="px-5 py-4 border-t">{{ $invoices->links() }}</div>@endif
</div>
@endsection
