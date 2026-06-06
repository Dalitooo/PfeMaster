<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Cabinet;
use App\Models\Invoice;
use App\Models\SupplyItem;
use App\Models\TreatmentRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        return match($user->role) {
            'super_admin', 'admin' => $this->adminDashboard(),
            'doctor'               => $this->doctorDashboard($user),
            'secretary'            => $this->secretaryDashboard(),
            'patient'              => $this->patientDashboard($user),
            'supplier'             => $this->supplierDashboard($user),
            default                => redirect()->route('login'),
        };
    }

    private function adminDashboard()
    {
        $stats = [
            'total_patients'      => User::where('role', 'patient')->count(),
            'total_doctors'       => User::where('role', 'doctor')->count(),
            'today_appointments'  => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments'=> Appointment::where('status', 'pending')->count(),
            'monthly_revenue'     => Invoice::where('status', 'paid')
                                         ->whereMonth('paid_at', now()->month)
                                         ->sum('total'),
            'unpaid_invoices'     => Invoice::whereIn('status', ['issued', 'overdue'])->count(),
            'low_stock_items'     => SupplyItem::whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
            'total_revenue'       => Invoice::where('status', 'paid')->sum('total'),
        ];

        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->orderByDesc('appointment_date')
            ->limit(8)
            ->get();

        $upcomingAppointments = Appointment::with(['patient', 'doctor'])
            ->where('appointment_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentAppointments', 'upcomingAppointments'));
    }

    private function doctorDashboard(User $user)
    {
        $stats = [
            'today_appointments'    => Appointment::where('doctor_id', $user->id)
                                           ->whereDate('appointment_date', today())->count(),
            'total_patients'        => Appointment::where('doctor_id', $user->id)
                                           ->distinct('patient_id')->count('patient_id'),
            'pending_treatments'    => TreatmentRecord::where('doctor_id', $user->id)
                                           ->whereIn('status', ['planned', 'in_progress'])->count(),
            'completed_this_month'  => Appointment::where('doctor_id', $user->id)
                                           ->where('status', 'completed')
                                           ->whereMonth('appointment_date', now()->month)->count(),
        ];

        $todayAppointments = Appointment::with(['patient'])
            ->where('doctor_id', $user->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_date')
            ->get();

        $upcomingAppointments = Appointment::with(['patient'])
            ->where('doctor_id', $user->id)
            ->where('appointment_date', '>', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        $lowStockItems = SupplyItem::with('supplier')
            ->where('doctor_id', $user->id)
            ->whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->orderBy('stock_quantity')
            ->limit(5)
            ->get();

        return view('dashboard.doctor', compact('stats', 'todayAppointments', 'upcomingAppointments', 'user', 'lowStockItems'));
    }

    private function secretaryDashboard()
    {
        $user       = Auth::user();
        $cabinetIds = Cabinet::where('secretary_id', $user->id)->pluck('id');

        $stats = [
            'today_appointments'   => Appointment::whereIn('cabinet_id', $cabinetIds)
                                           ->whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::whereIn('cabinet_id', $cabinetIds)
                                           ->where('status', 'pending')->count(),
            'total_patients'       => User::where('role', 'patient')->count(),
            'pending_invoices'     => Invoice::where('status', 'issued')->count(),
        ];

        $todayAppointments = Appointment::with(['patient', 'doctor'])
            ->whereIn('cabinet_id', $cabinetIds)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_date')
            ->get();

        $pendingAppointments = Appointment::with(['patient', 'doctor'])
            ->whereIn('cabinet_id', $cabinetIds)
            ->where('status', 'pending')
            ->orderBy('appointment_date')
            ->limit(8)
            ->get();

        $appointmentsToInvoice = Appointment::with(['patient', 'doctor', 'treatmentRecords'])
            ->whereIn('cabinet_id', $cabinetIds)
            ->where('status', 'completed')
            ->whereDoesntHave('invoice')
            ->orderByDesc('appointment_date')
            ->limit(10)
            ->get();

        return view('dashboard.secretary', compact('stats', 'todayAppointments', 'pendingAppointments', 'appointmentsToInvoice'));
    }

    private function patientDashboard(User $user)
    {
        $upcomingAppointments = Appointment::with(['doctor'])
            ->where('patient_id', $user->id)
            ->where('appointment_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        $recentTreatments = TreatmentRecord::with(['treatment', 'doctor'])
            ->where('patient_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $totalCost = TreatmentRecord::where('patient_id', $user->id)
            ->where('status', 'completed')
            ->sum('cost');

        $unpaidInvoices = Invoice::where('patient_id', $user->id)
            ->whereIn('status', ['issued', 'overdue'])
            ->orderBy('due_date')
            ->get();

        return view('dashboard.patient', compact('upcomingAppointments', 'recentTreatments', 'totalCost', 'unpaidInvoices', 'user'));
    }

    private function supplierDashboard(User $user)
    {
        $supplier = $user->supplier;
        $recentOrders = $supplier
            ? $supplier->orders()->orderByDesc('created_at')->limit(5)->get()
            : collect();

        return view('dashboard.supplier', compact('user', 'supplier', 'recentOrders'));
    }
}
