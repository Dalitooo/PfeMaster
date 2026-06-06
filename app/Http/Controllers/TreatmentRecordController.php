<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\TreatmentRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentRecordController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = TreatmentRecord::with(['patient', 'doctor', 'treatment.category']);

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        } elseif ($user->isPatient()) {
            $query->where('patient_id', $user->id);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $records = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('treatment-records.index', compact('records'));
    }

    public function create(Request $request)
    {
        $patients     = User::where('role', 'patient')->where('is_active', true)->orderBy('name')->get();
        $doctors      = User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        $treatments   = Treatment::with('category')->where('is_active', true)->orderBy('name')->get();
        $appointments = $request->patient_id
            ? Appointment::where('patient_id', $request->patient_id)->orderByDesc('appointment_date')->get()
            : collect();

        $selectedPatient = $request->patient_id
            ? User::find($request->patient_id)
            : null;

        return view('treatment-records.create', compact('patients', 'doctors', 'treatments', 'appointments', 'selectedPatient'));
    }

    public function store(Request $request)
    {
        // Multi-treatment mode (from appointment dossier modal)
        if ($request->has('treatments')) {
            $request->validate([
                'patient_id'                   => 'required|exists:users,id',
                'doctor_id'                    => 'required|exists:users,id',
                'appointment_id'               => 'nullable|exists:appointments,id',
                'treatments'                   => 'required|array|min:1',
                'treatments.*.treatment_id'    => 'required|exists:treatments,id',
                'treatments.*.tooth_number'    => 'nullable|string|max:10',
                'treatments.*.cost'            => 'required|numeric|min:0',
                'treatments.*.status'          => 'required|in:planned,in_progress,completed,cancelled',
                'treatments.*.notes'           => 'nullable|string',
                'treatments.*.completed_date'  => 'nullable|date',
            ]);

            foreach ($request->treatments as $t) {
                TreatmentRecord::create([
                    'patient_id'     => $request->patient_id,
                    'doctor_id'      => $request->doctor_id,
                    'appointment_id' => $request->appointment_id,
                    'treatment_id'   => $t['treatment_id'],
                    'tooth_number'   => $t['tooth_number'] ?? null,
                    'cost'           => $t['cost'],
                    'status'         => $t['status'],
                    'notes'          => $t['notes'] ?? null,
                    'completed_date' => $t['completed_date'] ?? null,
                ]);
            }

            if ($request->appointment_id) {
                return redirect()->route('appointments.show', $request->appointment_id)
                                 ->with('success', count($request->treatments) . ' acte(s) enregistré(s).');
            }

            return redirect()->route('treatment-records.index')
                             ->with('success', 'Actes enregistrés.');
        }

        // Single-treatment mode (from treatment-records.create form)
        $validated = $request->validate([
            'patient_id'      => 'required|exists:users,id',
            'doctor_id'       => 'required|exists:users,id',
            'treatment_id'    => 'required|exists:treatments,id',
            'appointment_id'  => 'nullable|exists:appointments,id',
            'tooth_number'    => 'nullable|string|max:10',
            'notes'           => 'nullable|string',
            'status'          => 'required|in:planned,in_progress,completed,cancelled',
            'scheduled_date'  => 'nullable|date',
            'completed_date'  => 'nullable|date',
            'cost'            => 'required|numeric|min:0',
        ]);

        TreatmentRecord::create($validated);

        if ($validated['appointment_id'] ?? null) {
            return redirect()->route('appointments.show', $validated['appointment_id'])
                             ->with('success', 'Acte enregistré avec succès.');
        }

        return redirect()->route('treatment-records.index')
                         ->with('success', 'Treatment record added.');
    }

    public function show(TreatmentRecord $treatmentRecord)
    {
        $treatmentRecord->load(['patient', 'doctor', 'treatment.category', 'appointment']);
        return view('treatment-records.show', compact('treatmentRecord'));
    }

    public function edit(TreatmentRecord $treatmentRecord)
    {
        $patients   = User::where('role', 'patient')->where('is_active', true)->orderBy('name')->get();
        $doctors    = User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        $treatments = Treatment::with('category')->where('is_active', true)->orderBy('name')->get();
        $appointments = Appointment::where('patient_id', $treatmentRecord->patient_id)
                                    ->orderByDesc('appointment_date')->get();

        return view('treatment-records.edit', compact('treatmentRecord', 'patients', 'doctors', 'treatments', 'appointments'));
    }

    public function update(Request $request, TreatmentRecord $treatmentRecord)
    {
        $validated = $request->validate([
            'patient_id'     => 'required|exists:users,id',
            'doctor_id'      => 'required|exists:users,id',
            'treatment_id'   => 'required|exists:treatments,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'tooth_number'   => 'nullable|string|max:10',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:planned,in_progress,completed,cancelled',
            'scheduled_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'cost'           => 'required|numeric|min:0',
        ]);

        $treatmentRecord->update($validated);

        return redirect()->route('treatment-records.show', $treatmentRecord)
                         ->with('success', 'Treatment record updated.');
    }

    public function destroy(TreatmentRecord $treatmentRecord)
    {
        $treatmentRecord->delete();
        return redirect()->route('treatment-records.index')
                         ->with('success', 'Record deleted.');
    }
}
