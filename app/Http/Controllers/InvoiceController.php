<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Invoice::with(['patient', 'issuedBy']);

        if ($user->isPatient()) {
            $query->where('patient_id', $user->id);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%$search%")
                  ->orWhereHas('patient', fn($sq) => $sq->where('name', 'like', "%$search%"));
            });
        }

        $invoices = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $patients     = User::where('role', 'patient')->where('is_active', true)->orderBy('name')->get();
        $treatments   = Treatment::with('category')->where('is_active', true)->orderBy('name')->get();
        $appointment  = $request->appointment_id
            ? Appointment::with('patient')->find($request->appointment_id)
            : null;

        return view('invoices.create', compact('patients', 'treatments', 'appointment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:users,id',
            'appointment_id'   => 'nullable|exists:appointments,id',
            'due_date'         => 'nullable|date',
            'discount'         => 'nullable|numeric|min:0',
            'tax'              => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'items.*.treatment_id'=> 'nullable|exists:treatments,id',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $discount = $request->discount ?? 0;
            $tax      = $request->tax ?? 0;
            $total    = $subtotal - $discount + $tax;

            $invoice = Invoice::create([
                'patient_id'     => $request->patient_id,
                'appointment_id' => $request->appointment_id,
                'issued_by'      => Auth::id(),
                'status'         => 'issued',
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total'          => max(0, $total),
                'due_date'       => $request->due_date,
                'notes'          => $request->notes,
            ]);

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id'   => $invoice->id,
                    'treatment_id' => $item['treatment_id'] ?? null,
                    'description'  => $item['description'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'subtotal'     => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('invoices.index')
                         ->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'issuedBy', 'appointment', 'items.treatment']);
        return view('invoices.show', compact('invoice'));
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Invoice marked as paid.');
    }

    public function cancel(Invoice $invoice)
    {
        abort_if($invoice->status === 'paid', 403);
        $invoice->update(['status' => 'cancelled']);
        return back()->with('success', 'Invoice cancelled.');
    }

    public function destroy(Invoice $invoice)
    {
        abort_if($invoice->status === 'paid', 403, 'Cannot delete a paid invoice.');
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['patient', 'issuedBy', 'appointment', 'items.treatment']);
        return view('invoices.print', compact('invoice'));
    }
}
