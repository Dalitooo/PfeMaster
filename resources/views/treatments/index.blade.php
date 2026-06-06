@extends('layouts.app')
@section('title', 'Traitements')
@section('page-title', 'Traitements')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">{{ $treatments->total() }} traitements</p>
    <div class="flex items-center gap-2">
        <a href="{{ route('treatments.categories') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
            Catégories
        </a>
        @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
            <a href="{{ route('treatments.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter un traitement
            </a>
        @endif
    </div>
</div>

{{-- Category pills --}}
<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('treatments.index') }}" class="px-3 py-1.5 rounded-full text-xs font-medium {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Tout</a>
    @foreach($categories as $cat)
        <a href="{{ route('treatments.index', ['category' => $cat->id]) }}"
           class="px-3 py-1.5 rounded-full text-xs font-medium {{ request('category') == $cat->id ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            {{ $cat->name }} ({{ $cat->treatments_count }})
        </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Traitement</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden md:table-cell">Catégorie</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Durée</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Prix</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Statut</th>
                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                    <th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($treatments as $treatment)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-slate-800">{{ $treatment->name }}</div>
                        @if($treatment->description)
                            <div class="text-xs text-slate-400 mt-0.5">{{ Str::limit($treatment->description, 60) }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">{{ $treatment->category->name }}</span>
                    </td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-slate-600">{{ $treatment->duration_minutes }} min</td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800">DT {{ number_format($treatment->price, 2) }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $treatment->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $treatment->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('treatments.edit', $treatment) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('treatments.destroy', $treatment) }}" onsubmit="return confirm('Supprimer ce traitement ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Aucun traitement trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($treatments->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $treatments->links() }}</div>
    @endif
</div>
@endsection
