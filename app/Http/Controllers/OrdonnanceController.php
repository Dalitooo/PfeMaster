<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Ordonnance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdonnanceController extends Controller
{
    public function store(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        if ($user->isDoctor() && $appointment->doctor_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'items'                  => 'required|array|min:1',
            'items.*.medicament'     => 'required|string|max:200',
            'items.*.dosage'         => 'nullable|string|max:100',
            'items.*.frequence'      => 'nullable|string|max:100',
            'items.*.duree'          => 'nullable|string|max:100',
            'items.*.instructions'   => 'nullable|string|max:300',
            'notes'                  => 'nullable|string|max:1000',
        ]);

        $ordonnance = Ordonnance::create([
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'doctor_id'      => $appointment->doctor_id,
            'items'          => array_filter($request->items, fn($i) => !empty($i['medicament'])),
            'notes'          => $request->notes,
        ]);

        return redirect()->route('ordonnances.print', $ordonnance);
    }

    public function print(Ordonnance $ordonnance)
    {
        $ordonnance->load(['patient.patientProfile', 'doctor.doctorProfile', 'appointment']);
        return view('ordonnances.print', compact('ordonnance'));
    }
}
