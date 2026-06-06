<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin CNAM — {{ $bulletin->patient->name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            .print-page { box-shadow: none !important; margin: 0 !important; }
        }
        body { font-family: Arial, sans-serif; background: #f1f5f9; }
        .cnam-table { border-collapse: collapse; width: 100%; }
        .cnam-table th, .cnam-table td {
            border: 1px solid #1e3a5f;
            padding: 3px 5px;
            font-size: 10px;
        }
        .cnam-table th { background-color: #1e3a5f; color: white; text-align: center; }
        .cnam-table td { min-height: 18px; vertical-align: middle; }
        .section-title {
            background-color: #1e3a5f;
            color: white;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            padding: 4px;
            letter-spacing: 1px;
        }
        .field-line {
            border-bottom: 1px solid #1e3a5f;
            min-height: 18px;
            padding: 2px 4px;
            font-size: 10px;
        }
        .label { font-size: 9px; color: #333; }
        .box { border: 1px solid #1e3a5f; display: inline-block; width: 12px; height: 12px; vertical-align: middle; margin-right: 3px; }
        .tooth-chart { font-size: 9px; text-align: center; border: 1px solid #1e3a5f; padding: 4px; }
    </style>
</head>
<body>

{{-- Print / Back buttons --}}
<div class="no-print flex items-center gap-3 max-w-5xl mx-auto px-4 py-4">
    <button onclick="window.print()"
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-lg shadow-blue-100">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Imprimer
    </button>
    <a href="{{ route('appointments.show', $bulletin->appointment) }}"
       class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour au rendez-vous
    </a>
</div>

{{-- ── CNAM Form ────────────────────────────────────────────────────────────── --}}
<div class="print-page max-w-5xl mx-auto px-4 pb-8 bg-white shadow-xl" style="font-family: Arial, sans-serif;">

    {{-- Two-column layout matching the CNAM form --}}
    <table style="width:100%; border-collapse:collapse; border: 2px solid #1e3a5f;">
        <tr>
            {{-- LEFT: Doctor-filled section --}}
            <td style="width:55%; vertical-align:top; border-right: 2px solid #1e3a5f; padding: 6px;">

                {{-- Consultations & Actes de soins dentaires --}}
                <div class="section-title" style="margin-bottom:4px;">
                    CONSULTATIONS ET ACTES DE SOINS DENTAIRES
                </div>
                <div style="font-size:8px; color:#555; margin-bottom:4px; font-style:italic;">
                    Il est indispensable d'indiquer la dent traitée, de désigner les actes pratiqués en se référant aux codes et cotations de la nomenclature officielle
                </div>

                <table class="cnam-table" style="margin-bottom:6px;">
                    <thead>
                        <tr>
                            <th style="width:15%;">DATE</th>
                            <th style="width:10%;">DENT</th>
                            <th style="width:20%;">CODE ACTE</th>
                            <th style="width:15%;">COTATION</th>
                            <th style="width:20%;">HONORAIRES</th>
                            <th style="width:20%;">CODE Prof. de santé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $acts = $bulletin->dental_acts ?? []; @endphp
                        @for($i = 0; $i < max(7, count($acts)); $i++)
                            <tr>
                                <td style="height:18px;">{{ isset($acts[$i]['date']) ? \Carbon\Carbon::parse($acts[$i]['date'])->format('d/m/Y') : '' }}</td>
                                <td style="text-align:center;">{{ $acts[$i]['dent'] ?? '' }}</td>
                                <td>{{ $acts[$i]['code_acte'] ?? '' }}</td>
                                <td style="text-align:center;">{{ $acts[$i]['cotation'] ?? '' }}</td>
                                <td style="text-align:right;">{{ $acts[$i]['honoraires'] ?? '' }}</td>
                                <td style="text-align:center; font-size:8px;">
                                    @if($i === 0) {{ $bulletin->doctor->doctorProfile?->license_number ?? '' }} @endif
                                </td>
                            </tr>
                        @endfor
                        {{-- Cachet et signature row --}}
                        <tr>
                            <td colspan="5" style="font-size:8px; font-style:italic; color:#555;">CACHET ET SIGNATURE :</td>
                            <td style="height:40px;"></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Tooth chart --}}
                <div class="tooth-chart" style="margin-bottom:6px;">
                    <div style="font-size:8px; font-weight:bold; margin-bottom:3px;">Numérotation des dents</div>
                    <table style="width:100%; border-collapse:collapse; font-size:8px; text-align:center;">
                        <tr>
                            <td style="border-right:1px solid #1e3a5f; padding-right:4px;">
                                <div style="margin-bottom:2px; font-weight:bold;">Adulte</div>
                                <div style="display:flex; justify-content:center; gap:1px; flex-wrap:wrap; max-width:200px; margin:auto;">
                                    @php
                                        $upper_right = [18,17,16,15,14,13,12,11];
                                        $upper_left  = [21,22,23,24,25,26,27,28];
                                        $lower_left  = [31,32,33,34,35,36,37,38];
                                        $lower_right = [48,47,46,45,44,43,42,41];
                                    @endphp
                                    @foreach($upper_right as $t)
                                        <span style="border:1px solid #1e3a5f; width:16px; display:inline-block; text-align:center; margin:1px;">{{ $t }}</span>
                                    @endforeach
                                    <span style="display:inline-block; margin:1px; font-weight:bold;">|</span>
                                    @foreach($upper_left as $t)
                                        <span style="border:1px solid #1e3a5f; width:16px; display:inline-block; text-align:center; margin:1px;">{{ $t }}</span>
                                    @endforeach
                                </div>
                                <div style="text-align:center; margin:2px 0; font-size:8px;">D &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; G</div>
                                <div style="display:flex; justify-content:center; gap:1px; flex-wrap:wrap; max-width:200px; margin:auto;">
                                    @foreach($lower_right as $t)
                                        <span style="border:1px solid #1e3a5f; width:16px; display:inline-block; text-align:center; margin:1px;">{{ $t }}</span>
                                    @endforeach
                                    <span style="display:inline-block; margin:1px; font-weight:bold;">|</span>
                                    @foreach($lower_left as $t)
                                        <span style="border:1px solid #1e3a5f; width:16px; display:inline-block; text-align:center; margin:1px;">{{ $t }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td style="padding-left:4px;">
                                <div style="margin-bottom:2px; font-weight:bold;">Lait (enfant)</div>
                                @php
                                    $milk_upper_right = [55,54,53,52,51];
                                    $milk_upper_left  = [61,62,63,64,65];
                                    $milk_lower_left  = [71,72,73,74,75];
                                    $milk_lower_right = [85,84,83,82,81];
                                @endphp
                                <div style="display:flex; justify-content:center; gap:1px; flex-wrap:wrap; max-width:130px; margin:auto;">
                                    @foreach($milk_upper_right as $t)
                                        <span style="border:1px solid #1e3a5f; width:16px; display:inline-block; text-align:center; margin:1px;">{{ $t }}</span>
                                    @endforeach
                                    <span style="display:inline-block; margin:1px; font-weight:bold;">|</span>
                                    @foreach($milk_upper_left as $t)
                                        <span style="border:1px solid #1e3a5f; width:16px; display:inline-block; text-align:center; margin:1px;">{{ $t }}</span>
                                    @endforeach
                                </div>
                                <div style="text-align:center; margin:2px 0; font-size:8px;">D &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; G</div>
                                <div style="display:flex; justify-content:center; gap:1px; flex-wrap:wrap; max-width:130px; margin:auto;">
                                    @foreach($milk_lower_right as $t)
                                        <span style="border:1px solid #1e3a5f; width:16px; display:inline-block; text-align:center; margin:1px;">{{ $t }}</span>
                                    @endforeach
                                    <span style="display:inline-block; margin:1px; font-weight:bold;">|</span>
                                    @foreach($milk_lower_left as $t)
                                        <span style="border:1px solid #1e3a5f; width:16px; display:inline-block; text-align:center; margin:1px;">{{ $t }}</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Prothèses dentaires --}}
                <div class="section-title" style="margin-bottom:4px;">PROTHESES DENTAIRES</div>
                <table class="cnam-table">
                    <thead>
                        <tr>
                            <th style="width:15%;">DATE</th>
                            <th style="width:12%;">DENTS</th>
                            <th style="width:20%;">CODE ACTE</th>
                            <th style="width:15%;">COTATION</th>
                            <th style="width:18%;">HONORAIRES</th>
                            <th style="width:20%;">CODE Prof. de santé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $protheses = $bulletin->prostheses ?? []; @endphp
                        @for($i = 0; $i < max(5, count($protheses)); $i++)
                            <tr>
                                <td style="height:18px;">{{ isset($protheses[$i]['date']) ? \Carbon\Carbon::parse($protheses[$i]['date'])->format('d/m/Y') : '' }}</td>
                                <td style="text-align:center;">{{ $protheses[$i]['dents'] ?? '' }}</td>
                                <td>{{ $protheses[$i]['code_acte'] ?? '' }}</td>
                                <td style="text-align:center;">{{ $protheses[$i]['cotation'] ?? '' }}</td>
                                <td style="text-align:right;">{{ $protheses[$i]['honoraires'] ?? '' }}</td>
                                <td style="text-align:center; font-size:8px;">
                                    @if($i === 0 && count($protheses) > 0) {{ $bulletin->doctor->doctorProfile?->license_number ?? '' }} @endif
                                </td>
                            </tr>
                        @endfor
                        <tr>
                            <td colspan="5" style="font-size:8px; font-style:italic; color:#555;">CACHET ET SIGNATURE :</td>
                            <td style="height:40px;"></td>
                        </tr>
                    </tbody>
                </table>

            </td>

            {{-- RIGHT: Patient section (pre-filled, patient cannot modify) --}}
            <td style="width:45%; vertical-align:top; padding: 6px;">

                {{-- CNAM Header --}}
                <div style="display:flex; align-items:center; justify-content:space-between; border:1px solid #1e3a5f; padding:4px; margin-bottom:4px;">
                    <div>
                        <div style="font-size:14px; font-weight:900; color:#1e3a5f; letter-spacing:1px;">CNAM</div>
                        <div style="font-size:7px; color:#555;">الصندوق الوطني للتأمين على المرض</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:8px; font-weight:bold;">Réf. Dossier</div>
                        <div style="border:1px solid #1e3a5f; width:80px; height:16px; margin-top:2px;"></div>
                    </div>
                </div>

                {{-- Title --}}
                <div style="text-align:center; border:1px solid #1e3a5f; padding:4px; margin-bottom:4px;">
                    <div style="font-size:9px; font-weight:bold; color:#1e3a5f;">بطاقة استرجاع مصاريف علاج</div>
                    <div style="font-size:9px; font-weight:bold; color:#1e3a5f;">BULLETIN DE REMBOURSEMENT DES FRAIS DE SOINS</div>
                </div>

                {{-- A remplir par l'assuré --}}
                <div style="border:1px solid #1e3a5f; padding:4px; margin-bottom:4px;">
                    <div style="background:#1e3a5f; color:white; font-size:8px; font-weight:bold; padding:2px 4px; margin:-4px -4px 4px -4px; display:flex; justify-content:space-between;">
                        <span>A REMPLIR PAR L'ASSURE SOCIAL</span>
                        <span>يعمر من طرف المضمون الاجتماعي</span>
                    </div>

                    {{-- Identifiant unique --}}
                    @php
                        $cnamId   = $bulletin->patient->patientProfile?->cnam_id ?? '';
                        $cnamType = $bulletin->patient->patientProfile?->cnam_type ?? '';
                        $cnamChars = str_split(str_pad($cnamId, 13, ' '));
                    @endphp
                    <div style="display:flex; align-items:center; gap:4px; margin-bottom:4px; border:1px solid #1e3a5f; padding:3px;">
                        <span style="font-size:8px; font-weight:bold; min-width:90px;">IDENTIFIANT UNIQUE</span>
                        <div style="display:flex; gap:2px;">
                            @foreach($cnamChars as $ch)
                                <div style="border:1px solid #1e3a5f; width:14px; height:14px; font-size:8px; text-align:center; line-height:14px; font-weight:bold;">{{ trim($ch) }}</div>
                            @endforeach
                        </div>
                        <span style="font-size:8px; margin-right:4px;">المعرف الوحيد</span>
                    </div>

                    {{-- CNSS / CNRPS --}}
                    <div style="display:flex; gap:6px; margin-bottom:4px; font-size:8px;">
                        <span>
                            <span class="box" style="{{ $cnamType === 'cnss' ? 'background:#1e3a5f;' : '' }}">
                                @if($cnamType === 'cnss')<span style="color:white; font-size:9px; line-height:12px; display:block; text-align:center;">✓</span>@endif
                            </span>CNSS
                        </span>
                        <span>
                            <span class="box" style="{{ $cnamType === 'cnrps' ? 'background:#1e3a5f;' : '' }}">
                                @if($cnamType === 'cnrps')<span style="color:white; font-size:9px; line-height:12px; display:block; text-align:center;">✓</span>@endif
                            </span>CNRPS
                        </span>
                        <span><span class="box"></span>Convention bilatérale</span>
                    </div>

                    {{-- L'assuré social --}}
                    <div style="border:1px solid #1e3a5f; padding:3px; margin-bottom:3px; background:#f0f4ff;">
                        <div style="font-size:8px; font-weight:bold; color:#1e3a5f; margin-bottom:2px;">L'assuré social — المضمون الاجتماعي</div>
                        <div class="field-line" style="margin-bottom:2px;">
                            <span class="label">Prénom : </span>
                            <strong style="font-size:10px;">{{ $bulletin->patient->name }}</strong>
                        </div>
                        <div class="field-line" style="margin-bottom:2px;">
                            <span class="label">Nom : </span>
                            <span></span>
                        </div>
                        <div class="field-line" style="margin-bottom:2px;">
                            <span class="label">Adresse : </span>
                            {{ $bulletin->patient->address ?? '' }}
                        </div>
                        <div class="field-line">
                            <span class="label">Code postal : </span>
                        </div>
                    </div>

                    {{-- Le malade --}}
                    <div style="border:1px solid #1e3a5f; padding:3px; margin-bottom:3px;">
                        <div style="font-size:8px; font-weight:bold; color:#1e3a5f; margin-bottom:2px;">Le malade — المريض</div>
                        <div style="font-size:8px; display:flex; gap:8px; margin-bottom:3px; flex-wrap:wrap;">
                            <span><span class="box"></span>L'assuré social</span>
                            <span><span class="box"></span>Le conjoint</span>
                            <span><span class="box"></span>L'enfant (*)</span>
                            <span><span class="box"></span>L'ascendant (**)</span>
                        </div>
                        <div class="field-line" style="margin-bottom:2px;">
                            <span class="label">PRENOM : </span>
                        </div>
                        <div class="field-line" style="margin-bottom:2px;">
                            <span class="label">NOM : </span>
                        </div>
                        <div class="field-line" style="margin-bottom:2px;">
                            <span class="label">DATE DE NAISSANCE : </span>
                            @if($bulletin->patient->patientProfile?->date_of_birth)
                                {{ \Carbon\Carbon::parse($bulletin->patient->patientProfile->date_of_birth)->format('d/m/Y') }}
                            @endif
                        </div>
                        <div class="field-line">
                            <span class="label">N° TEL PORTABLE : </span>
                            {{ $bulletin->patient->phone ?? '' }}
                        </div>
                    </div>

                    {{-- Signature --}}
                    <div style="font-size:8px; margin-top:4px;">
                        <div style="font-weight:bold; margin-bottom:2px;">SIGNATURE DE L'ASSURE — إمضاء المضمون الاجتماعي</div>
                        <div style="border:1px solid #1e3a5f; height:35px;"></div>
                    </div>
                </div>

                {{-- Important notice --}}
                <div style="border:1px solid #1e3a5f; padding:4px; font-size:7.5px; background:#fffbe6;">
                    <div style="display:flex; gap:8px;">
                        <div style="flex:1;">
                            <strong>Très important :</strong> Veuillez déposer ce formulaire au centre régional ou local le plus proche de votre domicile dans un délai ne dépassant pas les <u>60 jours</u> de la date des soins.
                        </div>
                        <div style="flex:1; text-align:right; direction:rtl;">
                            <strong>هام جدا :</strong> تسلم هذه البطاقة إلى أقرب مركز جهوي أو محلي لمقر إقامتكم خلال مدة لا تفوق <u>60 يوما</u> من تاريخ العلاج.
                        </div>
                    </div>
                </div>

                {{-- Doctor stamp area --}}
                <div style="margin-top:6px; border:1px dashed #1e3a5f; padding:4px; text-align:center; min-height:60px;">
                    <div style="font-size:8px; color:#1e3a5f; font-weight:bold; margin-bottom:4px;">CACHET DU MÉDECIN</div>
                    <div style="font-size:9px; font-weight:bold;">Dr. {{ $bulletin->doctor->name }}</div>
                    @if($bulletin->doctor->doctorProfile?->specialization)
                        <div style="font-size:8px; color:#555;">{{ $bulletin->doctor->doctorProfile->specialization }}</div>
                    @endif
                    @if($bulletin->doctor->doctorProfile?->license_number)
                        <div style="font-size:8px; color:#555;">N° {{ $bulletin->doctor->doctorProfile->license_number }}</div>
                    @endif
                </div>

            </td>
        </tr>
    </table>

    {{-- Footer note --}}
    <div style="border:1px solid #1e3a5f; border-top:none; padding:5px; font-size:7.5px;">
        <div style="display:flex; gap:12px; justify-content:space-between;">
            <div>
                ☐ Ce bulletin doit être rempli soigneusement et avec la plus grande précision.<br>
                ☐ Ce bulletin ne peut servir que pour un seul malade.<br>
                ☐ Toute fraude ou fausse déclaration est passible des poursuites judiciaires.
            </div>
            <div style="text-align:right; direction:rtl;">
                ☐ يجب تحرير هذه المطبوعة بكل دقة وعناية.<br>
                ☐ لا يمكن استعمال هذه البطاقة إلا لمريض واحد.<br>
                ☐ كل تدليس أو تزوير يعرض صاحبه للتتبعات العدلية.
            </div>
        </div>
    </div>

</div>

</body>
</html>
