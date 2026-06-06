<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Cabinet;
use App\Models\User;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Appointment::with(['patient', 'doctor']);

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        } elseif ($user->isSecretary()) {
            $cabinetIds = Cabinet::where('secretary_id', $user->id)->pluck('id');
            $query->whereIn('cabinet_id', $cabinetIds);
        } elseif ($user->isPatient()) {
            $query->where('patient_id', $user->id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', fn($sq) => $sq->where('name', 'like', "%$search%"))
                  ->orWhereHas('doctor', fn($sq) => $sq->where('name', 'like', "%$search%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($date = $request->get('date')) {
            $query->whereDate('appointment_date', $date);
        }

        $appointments = $query->orderByDesc('appointment_date')->paginate(15)->withQueryString();

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $user     = Auth::user();
        $cabinetQuery = Cabinet::with(['doctor.doctorProfile', 'secretary'])
                        ->where('is_active', true)
                        ->orderBy('name');

        if ($user->isSecretary()) {
            $cabinetQuery->where('secretary_id', $user->id);
        }

        $cabinets = $cabinetQuery->get();

        if ($user->isPatient()) {
            return view('appointments.create', [
                'patients' => collect(),
                'doctors'  => collect(),
                'cabinets' => $cabinets,
                'selfBook' => true,
            ]);
        }

        $patients         = User::where('role', 'patient')->where('is_active', true)->orderBy('name')->get();
        $doctors          = User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        $secretaryCabinet = $user->isSecretary() ? $cabinets->first() : null;

        return view('appointments.create', compact('patients', 'doctors', 'cabinets', 'secretaryCabinet') + [
            'selfBook'    => false,
            'isSecretary' => $user->isSecretary(),
        ]);
    }

    private function authorizeAppointment(Appointment $appointment): void
    {
        $user = Auth::user();
        if ($user->isPatient() && $appointment->patient_id !== $user->id) {
            abort(403);
        }
        if ($user->isDoctor() && $appointment->doctor_id !== $user->id) {
            abort(403);
        }
    }

    private function checkBusinessHours(string $appointmentDate): ?array
    {
        $date    = \Carbon\Carbon::parse($appointmentDate);
        $dow     = $date->dayOfWeek; // 0=Sun … 6=Sat
        $minutes = $date->hour * 60 + $date->minute;

        if ($dow === 0) {
            return ['appointment_date' => 'Les rendez-vous ne sont pas disponibles le dimanche.'];
        }

        if ($dow === 6) {
            if ($minutes < 540 || $minutes >= 780) {
                return ['appointment_date' => 'Le samedi, les rendez-vous sont disponibles de 09h00 à 13h00.'];
            }
        } else {
            if ($minutes < 540 || $minutes >= 1140) {
                return ['appointment_date' => 'Du lundi au vendredi, les rendez-vous sont disponibles de 09h00 à 19h00.'];
            }
        }

        return null;
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'patient_id'       => $user->isPatient() ? 'nullable' : 'required|exists:users,id',
            'cabinet_id'       => 'required|exists:cabinets,id',
            'appointment_date' => 'required|date_format:Y-m-d',
            'appointment_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'type'             => 'required|in:checkup,consultation,procedure,follow_up,emergency',
            'reason'           => 'nullable|string|max:1000',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $datetime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];
        unset($validated['appointment_time']);
        $validated['appointment_date'] = $datetime;

        if (\Carbon\Carbon::parse($datetime)->isPast()) {
            return back()->withErrors(['appointment_date' => 'La date et l\'heure doivent être dans le futur.'])->withInput();
        }

        if ($errors = $this->checkBusinessHours($datetime)) {
            return back()->withErrors($errors)->withInput();
        }

        $cabinet = Cabinet::findOrFail($validated['cabinet_id']);

        if ($user->isSecretary() && $cabinet->secretary_id !== $user->id) {
            abort(403, 'Vous ne pouvez créer des rendez-vous que pour votre cabinet.');
        }

        if ($user->isPatient()) {
            $validated['patient_id'] = $user->id;
        }

        $validated['doctor_id']    = $cabinet->doctor_id;
        $validated['secretary_id'] = $cabinet->secretary_id;
        $validated['status']       = 'pending';

        $appointment = Appointment::create($validated);
        $appointment->load(['patient', 'doctor', 'secretary']);

        $date = $appointment->appointment_date->format('d/m/Y à H:i');

        // Notify doctor
        if ($appointment->doctor && $appointment->doctor->id !== $user->id) {
            $appointment->doctor->notify(new AppointmentNotification(
                $appointment, 'booked',
                "Nouveau rendez-vous avec {$appointment->patient->name} le {$date}"
            ));
        }

        // Notify secretary
        if ($appointment->secretary && $appointment->secretary->id !== $user->id) {
            $appointment->secretary->notify(new AppointmentNotification(
                $appointment, 'booked',
                "Nouveau rendez-vous : {$appointment->patient->name} chez Dr. {$appointment->doctor->name} le {$date}"
            ));
        }

        // Notify patient when someone else books for them
        if (!$user->isPatient()) {
            $appointment->patient->notify(new AppointmentNotification(
                $appointment, 'booked',
                "Votre rendez-vous avec Dr. {$appointment->doctor->name} a été enregistré pour le {$date}"
            ));
        }

        return redirect()->route('appointments.show', $appointment)
                         ->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->load(['patient.patientProfile', 'doctor.doctorProfile', 'treatmentRecords.treatment', 'invoice', 'cnamBulletins', 'ordonnances']);
        $treatments = \App\Models\Treatment::where('is_active', true)->orderBy('name')->get();
        return view('appointments.show', compact('appointment', 'treatments'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $user     = Auth::user();
        $patients = User::where('role', 'patient')->where('is_active', true)->orderBy('name')->get();
        $doctors  = User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        $cabinetQuery = Cabinet::where('is_active', true)->orderBy('name');
        if ($user->isSecretary()) {
            $cabinetQuery->where('secretary_id', $user->id);
        }
        $cabinets = $cabinetQuery->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'cabinets'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $user = Auth::user();
        $validated = $request->validate([
            'patient_id'       => 'required|exists:users,id',
            'doctor_id'        => 'required|exists:users,id',
            'cabinet_id'       => 'nullable|exists:cabinets,id',
            'appointment_date' => 'required|date_format:Y-m-d',
            'appointment_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'type'             => 'required|in:checkup,consultation,procedure,follow_up,emergency',
            'status'           => 'required|in:pending,confirmed,in_progress,completed,cancelled,no_show',
            'reason'           => 'nullable|string|max:1000',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $datetime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];
        unset($validated['appointment_time']);
        $validated['appointment_date'] = $datetime;

        if ($errors = $this->checkBusinessHours($datetime)) {
            return back()->withErrors($errors)->withInput();
        }

        if ($user->isSecretary() && isset($validated['cabinet_id'])) {
            $editCabinet = Cabinet::findOrFail($validated['cabinet_id']);
            if ($editCabinet->secretary_id !== $user->id) {
                abort(403, 'Vous ne pouvez modifier que les rendez-vous de votre cabinet.');
            }
        }

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
                         ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->delete();
        return redirect()->route('appointments.index')
                         ->with('success', 'Appointment deleted.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,in_progress,completed,cancelled,no_show']);
        $appointment->load(['patient', 'doctor', 'secretary']);
        $appointment->update(['status' => $request->status]);

        $date   = $appointment->appointment_date->format('d/m/Y à H:i');
        $actor  = Auth::user();

        $notify = fn($user, $msg) => $user && $user->id !== $actor->id
            ? $user->notify(new AppointmentNotification($appointment, $request->status, $msg))
            : null;

        match ($request->status) {
            'confirmed' => $notify($appointment->patient,
                "Votre rendez-vous du {$date} avec Dr. {$appointment->doctor->name} a été confirmé."),

            'cancelled' => (function () use ($appointment, $date, $notify) {
                $notify($appointment->patient,
                    "Votre rendez-vous du {$date} avec Dr. {$appointment->doctor->name} a été annulé.");
                $notify($appointment->doctor,
                    "Le rendez-vous avec {$appointment->patient->name} le {$date} a été annulé.");
            })(),

            'completed' => $notify($appointment->secretary,
                "Consultation terminée pour {$appointment->patient->name} (Dr. {$appointment->doctor->name}) — pensez à créer la facture."),

            default => null,
        };

        return back()->with('success', 'Statut mis à jour.');
    }

    public function bordereau(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', today()->format('Y-m-d'));

        $query = Appointment::with([
            'patient.patientProfile',
            'treatmentRecords.treatment',
        ])->whereDate('appointment_date', $date)
          ->orderBy('appointment_date');

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        }

        $appointments = $query->get();
        $doctor       = $user->isDoctor() ? $user : null;

        return view('appointments.bordereau', compact('appointments', 'date', 'doctor'));
    }

    public function calendar()
    {
        $user  = Auth::user();
        $query = Appointment::with(['patient', 'doctor']);

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        } elseif ($user->isSecretary()) {
            $cabinetIds = Cabinet::where('secretary_id', $user->id)->pluck('id');
            $query->whereIn('cabinet_id', $cabinetIds);
        } elseif ($user->isPatient()) {
            $query->where('patient_id', $user->id);
        }

        $appointments = $query->get()->map(function ($appt) use ($user) {
            if ($user->isDoctor()) {
                $title = $appt->patient->name;
            } elseif ($user->isPatient()) {
                $title = 'Dr. ' . $appt->doctor->name;
            } else {
                $title = $appt->patient->name . ' — Dr. ' . $appt->doctor->name;
            }

            return [
                'id'    => $appt->id,
                'title' => $title,
                'start' => $appt->appointment_date->toIso8601String(),
                'end'   => $appt->getEndTime()->toIso8601String(),
                'color' => match($appt->status) {
                    'pending'   => '#F59E0B',
                    'confirmed' => '#3B82F6',
                    'completed' => '#10B981',
                    'cancelled' => '#EF4444',
                    default     => '#6B7280',
                },
                'url'   => route('appointments.show', $appt->id),
            ];
        });

        return view('appointments.calendar', compact('appointments'));
    }
}
