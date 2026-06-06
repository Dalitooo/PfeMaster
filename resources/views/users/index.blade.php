@extends('layouts.app')
@section('title', 'Gestion du personnel')
@section('page-title', 'Gestion du personnel')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">{{ $users->total() }} membre(s) du personnel</p>
    <a href="{{ route('users.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Ajouter un membre
    </a>
</div>

@php
$roleLabels = ['admin' => 'Administrateur', 'doctor' => 'Médecin', 'secretary' => 'Secrétaire', 'supplier' => 'Fournisseur'];
@endphp

<form method="GET" class="flex flex-wrap gap-3 mb-4">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-44">
    </div>
    <select name="role" class="px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Tous les rôles</option>
        @foreach($roleLabels as $value => $label)
            <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Filtrer</button>
    @if(request()->hasAny(['search','role']))<a href="{{ route('users.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Effacer</a>@endif
</form>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Membre</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Rôle</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden md:table-cell">Contact</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Statut</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($users as $user)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->getAvatarUrl() }}" class="w-9 h-9 rounded-full object-cover">
                            <div>
                                <div class="font-medium text-slate-800">{{ $user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-800' : '' }}
                            {{ $user->role === 'doctor' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role === 'secretary' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $user->role === 'supplier' ? 'bg-amber-100 text-amber-800' : '' }}">
                            {{ $user->getRoleLabel() }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-slate-600">{{ $user->phone ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('users.edit', $user) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('users.toggle', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 {{ $user->is_active ? 'hover:text-red-600 hover:bg-red-50' : 'hover:text-emerald-600 hover:bg-emerald-50' }}"
                                        title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                    @if($user->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Aucun membre du personnel trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())<div class="px-5 py-4 border-t">{{ $users->links() }}</div>@endif
</div>
@endsection
