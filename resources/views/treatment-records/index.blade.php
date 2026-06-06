@extends('layouts.app')
@section('title', 'Dossiers médicaux')
@section('page-title', 'Dossiers médicaux')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">{{ $records->total() }} dossiers</p>
    @if(in_array(auth()->user()->role, ['super_admin','admin','doctor']))
        <a href="{{ route('treatment-records.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter un dossier
        </a>
    @endif
</div>

<form method="GET" class="flex flex-wrap gap-3 mb-4">
    <select name="status" class="px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Tous les statuts</option>
        @foreach(['planned','in_progress','completed','cancelled'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Filtrer</button>
    @if(request('status'))<a href="{{ route('treatment-records.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-sm font-medium hover:bg-slate-200">Effacer</a>@endif
</form>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Traitement</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden md:table-cell">Patient</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Médecin</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600 hidden lg:table-cell">Date</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Coût</th>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Statut</th>
                @if(in_array(auth()->user()->role, ['super_admin','admin','doctor']))<th class="text-right px-5 py-3 font-semibold text-slate-600">Actions</th>@endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($records as $record)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-slate-800">{{ $record->treatment->name }}</div>
                        <div class="text-xs text-slate-400">{{ $record->treatment->category->name }}</div>
                        @if($record->tooth_number)<div class="text-xs text-blue-600">Dent {{ $record->tooth_number }}</div>@endif
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell">
                        <a href="{{ route('patients.show', $record->patient) }}" class="flex items-center gap-2 hover:text-blue-600">
                            <img src="{{ $record->patient->getAvatarUrl() }}" class="w-7 h-7 rounded-full object-cover">
                            <span class="truncate max-w-[100px]">{{ $record->patient->name }}</span>
                        </a>
                    </td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-slate-600">Dr. {{ $record->doctor->name }}</td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-slate-600">{{ $record->scheduled_date?->format('M j, Y') ?? '—' }}</td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800">DT {{ number_format($record->cost, 2) }}</td>
                    <td class="px-5 py-3.5"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $record->getStatusColorClass() }}">{{ ucwords(str_replace('_',' ',$record->status)) }}</span></td>
                    @if(in_array(auth()->user()->role, ['super_admin','admin','doctor']))
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('treatment-records.show', $record) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('treatment-records.edit', $record) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-slate-400">Aucun dossier trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($records->hasPages())<div class="px-5 py-4 border-t">{{ $records->links() }}</div>@endif
</div>
@endsection
