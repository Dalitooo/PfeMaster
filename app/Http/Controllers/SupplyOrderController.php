<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplyItem;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplyOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplyOrder::with(['supplier', 'orderedBy']);

        if (Auth::user()->isDoctor()) {
            $query->where('ordered_by', Auth::id());
        } elseif (Auth::user()->isSupplier()) {
            $supplierCompany = Auth::user()->supplier;
            $query->where('supplier_id', $supplierCompany?->id ?? 0);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $orders = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('supply-orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $suppliers = Supplier::where('is_active', true)
            ->with('items.category')
            ->orderBy('company_name')
            ->get();

        $doctors     = User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        $prefillItem = $request->item_id ? SupplyItem::with('supplier')->find($request->item_id) : null;
        return view('supply-orders.create', compact('suppliers', 'doctors', 'prefillItem'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'doctor_id'   => 'required|exists:users,id',
            'notes'       => 'nullable|string',
            'expected_at' => 'nullable|date|after:today',
            'items'       => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:supply_items,id',
            'items.*.quantity'  => 'required|integer|min:1',
            'items.*.unit_price'=> 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $orderNumber = 'PO-' . str_pad(SupplyOrder::max('id') + 1, 5, '0', STR_PAD_LEFT);

            $total = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);

            $order = SupplyOrder::create([
                'ordered_by'   => Auth::id(),
                'supplier_id'  => $request->supplier_id,
                'doctor_id'    => $request->doctor_id,
                'order_number' => $orderNumber,
                'status'       => 'sent',
                'ordered_at'   => now(),
                'total_amount' => $total,
                'notes'        => $request->notes,
                'expected_at'  => $request->expected_at,
            ]);

            foreach ($request->items as $item) {
                SupplyOrderItem::create([
                    'order_id'   => $order->id,
                    'item_id'    => $item['item_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('supply-orders.index')
                         ->with('success', 'Purchase order created.');
    }

    public function show(SupplyOrder $supplyOrder)
    {
        if (Auth::user()->isSupplier()) {
            $supplierCompany = Auth::user()->supplier;
            abort_if($supplyOrder->supplier_id !== $supplierCompany?->id, 403);
        } elseif (Auth::user()->isDoctor()) {
            abort_if($supplyOrder->ordered_by !== Auth::id(), 403);
        }

        $supplyOrder->load(['supplier', 'orderedBy', 'items.item.category']);
        return view('supply-orders.show', compact('supplyOrder'));
    }

    public function updateStatus(Request $request, SupplyOrder $supplyOrder)
    {
        $request->validate(['status' => 'required|in:draft,sent,confirmed,shipped,received,cancelled']);

        $user      = Auth::user();
        $newStatus = $request->status;
        $current   = $supplyOrder->status;

        if ($user->isSupplier()) {
            $company = $user->supplier;
            abort_if($supplyOrder->supplier_id !== $company?->id, 403);
            $allowed = [
                'draft'     => 'confirmed',
                'sent'      => 'confirmed',
                'confirmed' => 'shipped',
            ];
            abort_unless(($allowed[$current] ?? null) === $newStatus, 403);
        } elseif ($user->isDoctor()) {
            abort_if($supplyOrder->ordered_by !== $user->id, 403);
            abort_unless($current === 'shipped' && $newStatus === 'received', 403);
        }

        $data = ['status' => $newStatus];

        if ($newStatus === 'sent') {
            $data['ordered_at'] = now();
        }

        if ($newStatus === 'received') {
            $data['received_at'] = now()->toDateString();
            foreach ($supplyOrder->items as $orderItem) {
                $orderItem->item()->increment('stock_quantity', $orderItem->quantity);
            }
        }

        $supplyOrder->update($data);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(SupplyOrder $supplyOrder)
    {
        abort_if(!in_array($supplyOrder->status, ['draft', 'cancelled']), 403, 'Cannot delete this order.');
        $supplyOrder->delete();
        return redirect()->route('supply-orders.index')->with('success', 'Order deleted.');
    }
}
