@extends('layouts.app')
@section('title', 'Cabinets médicaux')
@section('page-title', 'Cabinets médicaux')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500">{{ $cabinets->total() }} cabinet(s) enregistré(s)</p>
        <a href="{{ route('cabinets.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau cabinet
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($cabinets as $cabinet)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col gap-4 hover:shadow-md hover:shadow-slate-100 transition-shadow">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800">{{ $cabinet->name }}</div>
                            @if($cabinet->description)
                                <div class="text-xs text-slate-500 mt-0.5">{{ $cabinet->description }}</div>
                            @endif
                        </div>
                    </div>
                    <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cabinet->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $cabinet->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>

                {{-- Staff --}}
                <div class="space-y-2 border-t border-slate-100 pt-4">
                    <div class="flex items-center gap-3">
                        @if($cabinet->doctor)
                            <img src="{{ $cabinet->doctor->getAvatarUrl() }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                            <div>
                                <div class="text-xs font-medium text-slate-700">{{ $cabinet->doctor->name }}</div>
                                <div class="text-xs text-blue-600">Médecin</div>
                            </div>
                        @else
                            <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="text-xs text-slate-400 italic">Aucun médecin assigné</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        @if($cabinet->secretary)
                            <img src="{{ $cabinet->secretary->getAvatarUrl() }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                            <div>
                                <div class="text-xs font-medium text-slate-700">{{ $cabinet->secretary->name }}</div>
                                <div class="text-xs text-purple-600">Secrétaire</div>
                            </div>
                        @else
                            <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="text-xs text-slate-400 italic">Aucune secrétaire assignée</span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
                    <a href="{{ route('cabinets.edit', $cabinet) }}"
                       class="flex-1 text-center px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-50 text-slate-700 hover:bg-slate-100 transition-colors">
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('cabinets.destroy', $cabinet) }}"
                          onsubmit="return confirm('Supprimer ce cabinet ?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 text-center py-16 text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <p class="font-medium">Aucun cabinet médical pour l'instant</p>
                <a href="{{ route('cabinets.create') }}" class="mt-2 inline-block text-blue-600 text-sm hover:underline">Créer le premier</a>
            </div>
        @endforelse
    </div>

    <div>{{ $cabinets->links() }}</div>
</div>
@endsection
