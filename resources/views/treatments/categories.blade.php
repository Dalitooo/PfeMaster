@extends('layouts.app')
@section('title', 'Catégories de traitements')
@section('page-title', 'Catégories de traitements')

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800">Toutes les catégories</h2>
                <a href="{{ route('treatments.index') }}" class="text-sm text-blue-600 hover:underline">Voir les traitements</a>
            </div>
            <table class="w-full text-sm">
                <thead><tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Nom</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Traitements</th>
                    @if(in_array(auth()->user()->role, ['super_admin','admin']))<th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>@endif
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $cat->color }}"></div>
                                    <span class="font-medium text-slate-800">{{ $cat->name }}</span>
                                </div>
                                @if($cat->description)<div class="text-xs text-slate-400 mt-0.5 ml-5">{{ $cat->description }}</div>@endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $cat->treatments_count }}</td>
                            @if(in_array(auth()->user()->role, ['super_admin','admin']))
                                <td class="px-5 py-3.5 text-right">
                                    <form method="POST" action="{{ route('treatment-categories.destroy', $cat) }}" onsubmit="return confirm('Supprimer cette catégorie et tous ses traitements ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-slate-400">Aucune catégorie pour l'instant.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(in_array(auth()->user()->role, ['super_admin','admin']))
    <div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Ajouter une catégorie</h3>
            <form method="POST" action="{{ route('treatment-categories.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Couleur</label>
                    <input type="color" name="color" value="#3B82F6" class="h-9 w-full rounded-xl border border-slate-200 cursor-pointer">
                </div>
                <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Ajouter la catégorie</button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
