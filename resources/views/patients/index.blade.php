@extends('layouts.app')
@section('title', 'Patients')
@section('page-title', 'Patients')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500 mt-0.5">{{ $patients->total() }} patients enregistrés</p>
    </div>
    @can('manage', App\Models\User::class)
    @endcan
    @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'secretary']))
        <a href="{{ route('patients.create') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Enregistrer un patient
        </a>
    @endif
</div>

{{-- Search --}}
<form method="GET" class="mb-4">
    <div class="flex gap-3">
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher des patients..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200">Rechercher</button>
        @if(request('search'))
            <a href="{{ route('patients.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200">Effacer</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Patient</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden md:table-cell">Contact</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Âge / Genre</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Statut</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($patients as $patient)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="{{ $patient->getAvatarUrl() }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                            <div>
                                <div class="font-medium text-slate-800">{{ $patient->name }}</div>
                                <div class="text-xs text-slate-400">{{ $patient->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell text-slate-600">{{ $patient->phone ?? '—' }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-slate-600">
                        @if($patient->patientProfile)
                            {{ $patient->patientProfile->age ? $patient->patientProfile->age . ' ans' : '—' }}
                            @if($patient->patientProfile->gender) · {{ ucfirst($patient->patientProfile->gender) }} @endif
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $patient->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $patient->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('patients.show', $patient) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @if(in_array(auth()->user()->role, ['super_admin', 'admin', 'secretary']))
                                <a href="{{ route('patients.edit', $patient) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Aucun patient trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($patients->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $patients->links() }}</div>
    @endif
</div>
@endsection
