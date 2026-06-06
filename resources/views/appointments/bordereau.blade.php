<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bordereau de journée — {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800">

    {{-- Toolbar (hidden on print) --}}
    <div class="no-print bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
        <a href="{{ route('appointments.index', ['date' => $date]) }}"
           class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour
        </a>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <label class="text-sm text-slate-500">Date :</label>
                <input type="date" name="date" value="{{ $date }}"
                       class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200">
                    Actualiser
                </button>
            </form>
            <button onclick="window.print()"
                    class="flex items-center gap-2 px-4 py-1.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Imprimer
            </button>
        </div>
    </div>

    {{-- Document --}}
    <div class="max-w-4xl mx-auto p-8">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-8 pb-6 border-b-2 border-slate-200">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-blue-600">SmileCare Cabinet Dentaire</div>
                        <div class="text-xs text-slate-400">Soins dentaires professionnels</div>
                    </div>
                </div>
                @if($doctor)
                <div class="mt-3 text-sm text-slate-600">
                    <span class="font-semibold">Dr. {{ $doctor->name }}</span>
                    @if($doctor->doctorProfile?->specialization)
                        · {{ $doctor->doctorProfile->specialization }}
                    @endif
                </div>
                @endif
            </div>
            <div class="text-right">
                <div class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1">Bordereau de journée</div>
                <div class="text-2xl font-bold text-slate-800">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                </div>
                <div class="text-sm text-slate-400 mt-0.5">
                    {{ $appointments->count() }} rendez-vous · {{ $appointments->where('status', 'completed')->count() }} terminés
                </div>
            </div>
        </div>

        @if($appointments->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="font-medium">Aucun rendez-vous ce jour</p>
        </div>
        @else

        {{-- Appointments table --}}
        <table class="w-full text-sm border-collapse mb-8">
            <thead>
                <tr class="bg-slate-800 text-white">
                    <th class="text-left px-3 py-2.5 font-semibold rounded-tl-lg w-8">N°</th>
                    <th class="text-left px-3 py-2.5 font-semibold w-20">Heure</th>
                    <th class="text-left px-3 py-2.5 font-semibold">Patient</th>
                    <th class="text-left px-3 py-2.5 font-semibold w-28">Type</th>
                    <th class="text-left px-3 py-2.5 font-semibold w-24">Statut</th>
                    <th class="text-left px-3 py-2.5 font-semibold">Actes réalisés</th>
                    <th class="text-right px-3 py-2.5 font-semibold rounded-tr-lg w-28">Coût (DT)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $typeLabels = ['checkup'=>'Bilan','consultation'=>'Consultation','procedure'=>'Procédure','follow_up'=>'Suivi','emergency'=>'Urgence'];
                    $statusLabels = ['pending'=>'En attente','confirmed'=>'Confirmé','in_progress'=>'En cours','completed'=>'Terminé','cancelled'=>'Annulé','no_show'=>'Absent'];
                    $statusColors = ['pending'=>'#F59E0B','confirmed'=>'#3B82F6','in_progress'=>'#8B5CF6','completed'=>'#10B981','cancelled'=>'#EF4444','no_show'=>'#6B7280'];
                    $totalCost = 0;
                    $totalActs = 0;
                @endphp

                @foreach($appointments as $i => $appt)
                @php
                    $rowCost = $appt->treatmentRecords->sum('cost');
                    $totalCost += $rowCost;
                    $totalActs += $appt->treatmentRecords->count();
                @endphp
                <tr class="{{ $loop->even ? 'bg-slate-50' : 'bg-white' }} border-b border-slate-100">
                    <td class="px-3 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                    <td class="px-3 py-3">
                        <div class="font-bold text-slate-800">{{ $appt->appointment_date->format('H:i') }}</div>
                        <div class="text-xs text-slate-400">{{ $appt->duration_minutes }} min</div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="font-semibold text-slate-800">{{ $appt->patient->name }}</div>
                        @if($appt->patient->patientProfile?->allergies)
                        <div class="text-xs text-red-500 mt-0.5">⚠ {{ $appt->patient->patientProfile->allergies }}</div>
                        @endif
                        @if($appt->reason)
                        <div class="text-xs text-slate-400 mt-0.5">{{ Str::limit($appt->reason, 40) }}</div>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-slate-600 text-xs">{{ $typeLabels[$appt->type] ?? $appt->type }}</td>
                    <td class="px-3 py-3">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold text-white"
                              style="background-color: {{ $statusColors[$appt->status] ?? '#6B7280' }}">
                            {{ $statusLabels[$appt->status] ?? $appt->status }}
                        </span>
                    </td>
                    <td class="px-3 py-3">
                        @if($appt->treatmentRecords->isNotEmpty())
                            <ul class="space-y-0.5">
                                @foreach($appt->treatmentRecords as $rec)
                                <li class="text-xs text-slate-700">
                                    · {{ $rec->treatment->name }}
                                    @if($rec->tooth_number)<span class="text-slate-400">(dent {{ $rec->tooth_number }})</span>@endif
                                    <span class="text-slate-400">— DT {{ number_format($rec->cost, 3, ',', ' ') }}</span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-xs text-slate-300 italic">Aucun acte enregistré</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-right font-semibold text-slate-800">
                        @if($rowCost > 0)
                            {{ number_format($rowCost, 3, ',', ' ') }}
                        @else
                            <span class="text-slate-300">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

            {{-- Totals --}}
            <tfoot>
                <tr class="bg-slate-800 text-white">
                    <td colspan="5" class="px-3 py-3 font-semibold text-sm rounded-bl-lg">
                        Total journée — {{ $appointments->count() }} consultation(s) · {{ $totalActs }} acte(s)
                    </td>
                    <td class="px-3 py-3 text-right font-bold text-base rounded-br-lg" colspan="2">
                        DT {{ number_format($totalCost, 3, ',', ' ') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Stats summary --}}
        <div class="grid grid-cols-4 gap-4 mb-8">
            @php
                $byStatus = $appointments->groupBy('status');
            @endphp
            @foreach(['completed' => ['Terminés', '#10B981'], 'confirmed' => ['Confirmés', '#3B82F6'], 'cancelled' => ['Annulés', '#EF4444'], 'no_show' => ['Absents', '#6B7280']] as $s => [$label, $color])
            <div class="border border-slate-200 rounded-xl p-3 text-center">
                <div class="text-2xl font-bold" style="color: {{ $color }}">{{ $byStatus->get($s, collect())->count() }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $label }}</div>
            </div>
            @endforeach
        </div>

        @endif

        {{-- Footer --}}
        <div class="border-t border-slate-200 pt-4 flex items-center justify-between text-xs text-slate-400">
            <span>SmileCare Cabinet Dentaire</span>
            <span>Généré le {{ now()->format('d/m/Y à H:i') }}</span>
        </div>
    </div>

</body>
</html>
