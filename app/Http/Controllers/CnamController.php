<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\CnamBulletin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CnamController extends Controller
{
    private function authorizeForAppointment(Appointment $appointment): void
    {
        $user = Auth::user();
        if ($user->isDoctor() && $appointment->doctor_id !== $user->id) {
            abort(403);
        }
    }

    public function create(Appointment $appointment)
    {
        $this->authorizeForAppointment($appointment);
        $appointment->load(['patient.patientProfile', 'doctor.doctorProfile']);
        return view('cnam.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $this->authorizeForAppointment($appointment);
        $request->validate([
            'dental_acts'              => 'nullable|array',
            'dental_acts.*.date'       => 'nullable|date',
            'dental_acts.*.dent'       => 'nullable|string|max:10',
            'dental_acts.*.code_acte'  => 'nullable|string|max:20',
            'dental_acts.*.cotation'   => 'nullable|string|max:20',
            'dental_acts.*.honoraires' => 'nullable|string|max:20',
            'prostheses'               => 'nullable|array',
            'prostheses.*.date'        => 'nullable|date',
            'prostheses.*.dents'       => 'nullable|string|max:50',
            'prostheses.*.code_acte'   => 'nullable|string|max:20',
            'prostheses.*.cotation'    => 'nullable|string|max:20',
            'prostheses.*.honoraires'  => 'nullable|string|max:20',
        ]);

        $bulletin = CnamBulletin::create([
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'doctor_id'      => $appointment->doctor_id,
            'dental_acts'    => array_filter($request->input('dental_acts', []), fn($r) => !empty($r['code_acte'])),
            'prostheses'     => array_filter($request->input('prostheses', []), fn($r) => !empty($r['code_acte'])),
        ]);

        return redirect()->route('cnam.print', $bulletin);
    }

    public function skip(Appointment $appointment)
    {
        $this->authorizeForAppointment($appointment);
        CnamBulletin::create([
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'doctor_id'      => $appointment->doctor_id,
            'dental_acts'    => [],
            'prostheses'     => [],
        ]);

        return redirect()->route('appointments.show', $appointment)
                         ->with('success', 'Bulletin CNAM marqué comme non requis.');
    }

    public function print(CnamBulletin $bulletin)
    {
        $bulletin->load(['patient.patientProfile', 'doctor.doctorProfile', 'appointment']);
        return view('cnam.print', compact('bulletin'));
    }
}
