<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance — {{ $ordonnance->patient->name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        @page { margin: 1.5cm; }
    </style>
</head>
<body class="bg-white font-sans text-slate-800">

    {{-- Toolbar --}}
    <div class="no-print bg-slate-100 border-b border-slate-200 px-6 py-3 flex items-center justify-between">
        <a href="{{ route('appointments.show', $ordonnance->appointment) }}"
           class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour au rendez-vous
        </a>
        <button onclick="window.print()"
                class="flex items-center gap-2 px-4 py-1.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimer
        </button>
    </div>

    {{-- Document --}}
    <div class="max-w-2xl mx-auto p-10">

        {{-- Header --}}
        <div class="flex items-start justify-between pb-6 mb-6 border-b-2 border-slate-800">
            <div>
                <div class="text-xl font-bold text-blue-600">SmileCare Cabinet Dentaire</div>
                <div class="text-sm text-slate-500 mt-0.5">Soins dentaires professionnels</div>
                <div class="mt-3 text-sm text-slate-700">
                    <div class="font-bold text-base">Dr. {{ $ordonnance->doctor->name }}</div>
                    @if($ordonnance->doctor->doctorProfile?->specialization)
                    <div class="text-slate-500">{{ $ordonnance->doctor->doctorProfile->specialization }}</div>
                    @endif
                    @if($ordonnance->doctor->doctorProfile?->license_number)
                    <div class="text-slate-400 text-xs mt-0.5">N° Ordre : {{ $ordonnance->doctor->doctorProfile->license_number }}</div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <div class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1">Ordonnance médicale</div>
                <div class="text-lg font-bold text-slate-800">
                    {{ $ordonnance->created_at->format('d/m/Y') }}
                </div>
                @if($ordonnance->appointment)
                <div class="text-xs text-slate-400 mt-0.5">
                    RDV du {{ $ordonnance->appointment->appointment_date->format('d/m/Y') }}
                </div>
                @endif
            </div>
        </div>

        {{-- Patient --}}
        <div class="mb-8 bg-slate-50 rounded-xl px-5 py-4">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Patient</div>
            <div class="font-bold text-lg text-slate-800">{{ $ordonnance->patient->name }}</div>
            <div class="flex items-center gap-4 mt-1 text-sm text-slate-500">
                @if($ordonnance->patient->patientProfile?->date_of_birth)
                <span>{{ $ordonnance->patient->patientProfile->age }} ans</span>
                @endif
                @if($ordonnance->patient->patientProfile?->gender)
                <span class="capitalize">{{ $ordonnance->patient->patientProfile->gender }}</span>
                @endif
                @if($ordonnance->patient->phone)
                <span>{{ $ordonnance->patient->phone }}</span>
                @endif
                @if($ordonnance->patient->patientProfile?->allergies)
                <span class="text-red-600 font-medium">⚠ Allergie : {{ $ordonnance->patient->patientProfile->allergies }}</span>
                @endif
            </div>
        </div>

        {{-- Rx symbol --}}
        <div class="flex items-center gap-3 mb-6">
            <span class="text-4xl font-bold text-blue-600 leading-none">℞</span>
            <div class="flex-1 h-px bg-slate-200"></div>
        </div>

        {{-- Medications --}}
        <div class="space-y-5 mb-8">
            @foreach($ordonnance->items as $i => $item)
            <div class="flex gap-4">
                <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                    {{ $i + 1 }}
                </div>
                <div class="flex-1">
                    <div class="font-bold text-slate-800 text-base">{{ $item['medicament'] }}</div>
                    <div class="mt-1 flex flex-wrap gap-x-6 gap-y-0.5 text-sm text-slate-600">
                        @if(!empty($item['dosage']))
                        <span><span class="text-slate-400">Dosage :</span> {{ $item['dosage'] }}</span>
                        @endif
                        @if(!empty($item['frequence']))
                        <span><span class="text-slate-400">Fréquence :</span> {{ $item['frequence'] }}</span>
                        @endif
                        @if(!empty($item['duree']))
                        <span><span class="text-slate-400">Durée :</span> {{ $item['duree'] }}</span>
                        @endif
                    </div>
                    @if(!empty($item['instructions']))
                    <div class="mt-1 text-sm text-slate-500 italic">{{ $item['instructions'] }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($ordonnance->notes)
        <div class="mb-8 border-l-4 border-slate-300 pl-4 text-sm text-slate-600 italic">
            {{ $ordonnance->notes }}
        </div>
        @endif

        {{-- Signature --}}
        <div class="mt-16 flex justify-end">
            <div class="text-center">
                <div class="w-48 h-16 border-b-2 border-slate-400 mb-2"></div>
                <div class="text-sm font-semibold text-slate-700">Dr. {{ $ordonnance->doctor->name }}</div>
                <div class="text-xs text-slate-400">Signature et cachet</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 pt-4 border-t border-slate-200 text-center text-xs text-slate-400">
            SmileCare Cabinet Dentaire · Ordonnance valable 3 mois à compter du {{ $ordonnance->created_at->format('d/m/Y') }}
        </div>
    </div>

</body>
</html>
