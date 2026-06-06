@extends('layouts.app')
@section('title', 'Tableau de bord fournisseur')
@section('page-title', 'Tableau de bord fournisseur')

@section('content')
<div class="mb-6 bg-gradient-to-r from-violet-600 to-violet-700 rounded-2xl p-6 text-white">
    <h2 class="text-xl font-bold">Bienvenue, {{ $user->name }}</h2>
    @if($supplier)
        <p class="text-violet-200 text-sm mt-1">{{ $supplier->company_name }}</p>
    @endif
</div>

@if($supplier)
<div class="bg-white rounded-2xl border border-slate-200">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Bons de commande récents</h2>
    </div>
    <div class="divide-y divide-slate-100">
        @forelse($recentOrders as $order)
            <a href="{{ route('supply-orders.show', $order) }}" class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-slate-800">{{ $order->order_number }}</div>
                    <div class="text-xs text-slate-400">{{ $order->created_at->format('M j, Y') }}</div>
                </div>
                <div class="text-sm font-bold text-slate-800">DT {{ number_format($order->total_amount, 2) }}</div>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $order->getStatusColorClass() }}">
                    {{ ucfirst($order->status) }}
                </span>
            </a>
        @empty
            <div class="px-5 py-8 text-center text-sm text-slate-400">Aucune commande pour l'instant.</div>
        @endforelse
    </div>
</div>
@else
<div class="bg-white rounded-2xl border border-slate-200 p-8 text-center">
    <div class="text-slate-400 text-sm">Votre profil fournisseur n'est pas encore configuré. Veuillez contacter l'administrateur.</div>
</div>
@endif
@endsection
