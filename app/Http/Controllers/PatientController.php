<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\PatientProfile;
use App\Models\TreatmentRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'patient')->with('patientProfile');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $patients = $query->orderBy('last_name')->orderBy('first_name')->paginate(15)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'              => 'required|string|max:255',
            'last_name'               => 'required|string|max:255',
            'email'                   => 'required|email|unique:users,email',
            'phone'                   => 'nullable|string|max:20',
            'address'                 => 'nullable|string|max:500',
            'password'                => 'required|string|min:8|confirmed',
            'date_of_birth'           => 'nullable|date',
            'gender'                  => 'nullable|in:male,female,other',
            'blood_type'              => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies'               => 'nullable|string',
            'medical_history'         => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'insurance_provider'      => 'nullable|string|max:255',
            'insurance_number'        => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'name'       => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'address'    => $validated['address'] ?? null,
            'password'   => Hash::make($validated['password']),
            'role'       => 'patient',
        ]);

        PatientProfile::create([
            'user_id'                 => $user->id,
            'date_of_birth'           => $validated['date_of_birth'] ?? null,
            'gender'                  => $validated['gender'] ?? null,
            'blood_type'              => $validated['blood_type'] ?? null,
            'allergies'               => $validated['allergies'] ?? null,
            'medical_history'         => $validated['medical_history'] ?? null,
            'emergency_contact_name'  => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
            'insurance_provider'      => $validated['insurance_provider'] ?? null,
            'insurance_number'        => $validated['insurance_number'] ?? null,
        ]);

        return redirect()->route('patients.show', $user)
                         ->with('success', 'Patient registered successfully.');
    }

    public function show(User $patient)
    {
        abort_if($patient->role !== 'patient', 404);

        $patient->load('patientProfile');

        $appointments = Appointment::with(['doctor'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('appointment_date')
            ->paginate(10, ['*'], 'appointments_page');

        $treatmentRecords = TreatmentRecord::with(['treatment.category', 'doctor'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'treatments_page');

        $invoices = Invoice::with(['issuedBy'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'invoices_page');

        return view('patients.show', compact('patient', 'appointments', 'treatmentRecords', 'invoices'));
    }

    public function edit(User $patient)
    {
        abort_if($patient->role !== 'patient', 404);
        $patient->load('patientProfile');
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, User $patient)
    {
        abort_if($patient->role !== 'patient', 404);

        $validated = $request->validate([
            'first_name'              => 'required|string|max:255',
            'last_name'               => 'required|string|max:255',
            'email'                   => 'required|email|unique:users,email,' . $patient->id,
            'phone'                   => 'nullable|string|max:20',
            'address'                 => 'nullable|string|max:500',
            'date_of_birth'           => 'nullable|date',
            'gender'                  => 'nullable|in:male,female,other',
            'blood_type'              => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies'               => 'nullable|string',
            'medical_history'         => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'insurance_provider'      => 'nullable|string|max:255',
            'insurance_number'        => 'nullable|string|max:100',
            'is_active'               => 'boolean',
        ]);

        $patient->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'name'       => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'address'    => $validated['address'] ?? null,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        $patient->patientProfile()->updateOrCreate(
            ['user_id' => $patient->id],
            [
                'date_of_birth'           => $validated['date_of_birth'] ?? null,
                'gender'                  => $validated['gender'] ?? null,
                'blood_type'              => $validated['blood_type'] ?? null,
                'allergies'               => $validated['allergies'] ?? null,
                'medical_history'         => $validated['medical_history'] ?? null,
                'emergency_contact_name'  => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'insurance_provider'      => $validated['insurance_provider'] ?? null,
                'insurance_number'        => $validated['insurance_number'] ?? null,
            ]
        );

        return redirect()->route('patients.show', $patient)
                         ->with('success', 'Patient updated successfully.');
    }

    public function destroy(User $patient)
    {
        abort_if($patient->role !== 'patient', 404);
        $patient->delete();
        return redirect()->route('patients.index')
                         ->with('success', 'Patient deleted successfully.');
    }
}
