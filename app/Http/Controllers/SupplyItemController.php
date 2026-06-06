<?php

namespace App\Http\Controllers;

use App\Models\SupplyCategory;
use App\Models\SupplyItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyItemController extends Controller
{
    private function scopedQuery()
    {
        $query = SupplyItem::with(['supplier', 'category']);
        if (Auth::user()->isDoctor()) {
            $query->where('doctor_id', Auth::id());
        }
        return $query;
    }

    private function scopedCount()
    {
        $query = SupplyItem::whereColumn('stock_quantity', '<=', 'min_stock_level');
        if (Auth::user()->isDoctor()) {
            $query->where('doctor_id', Auth::id());
        }
        return $query->count();
    }

    public function index(Request $request)
    {
        $query = $this->scopedQuery();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%$search%");
        }

        if ($supplier = $request->get('supplier_id')) {
            $query->where('supplier_id', $supplier);
        }

        if ($request->get('low_stock')) {
            $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
        }

        $items         = $query->orderBy('name')->paginate(15)->withQueryString();
        $suppliers     = Supplier::orderBy('company_name')->get();
        $categories    = SupplyCategory::orderBy('name')->get();
        $lowStockCount = $this->scopedCount();

        return view('supply-items.index', compact('items', 'suppliers', 'categories', 'lowStockCount'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('company_name')->get();
        $categories = SupplyCategory::orderBy('name')->get();
        $doctors   = \App\Models\User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        return view('supply-items.create', compact('suppliers', 'categories', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'category_id'    => 'required|exists:supply_categories,id',
            'doctor_id'      => 'nullable|exists:users,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'sku'            => 'nullable|string|max:100|unique:supply_items,sku',
            'unit'           => 'required|string|max:50',
            'unit_price'     => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level'=> 'required|integer|min:0',
        ]);

        SupplyItem::create($validated);

        return redirect()->route('supply-items.index')->with('success', 'Article ajouté.');
    }

    public function edit(SupplyItem $supplyItem)
    {
        $suppliers  = Supplier::where('is_active', true)->orderBy('company_name')->get();
        $categories = SupplyCategory::orderBy('name')->get();
        $doctors    = \App\Models\User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        return view('supply-items.edit', compact('supplyItem', 'suppliers', 'categories', 'doctors'));
    }

    public function update(Request $request, SupplyItem $supplyItem)
    {
        $validated = $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'category_id'    => 'required|exists:supply_categories,id',
            'doctor_id'      => 'nullable|exists:users,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'sku'            => 'nullable|string|max:100|unique:supply_items,sku,' . $supplyItem->id,
            'unit'           => 'required|string|max:50',
            'unit_price'     => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level'=> 'required|integer|min:0',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $supplyItem->update($validated);

        return redirect()->route('supply-items.index')->with('success', 'Article mis à jour.');
    }

    public function consume(Request $request, SupplyItem $supplyItem)
    {
        // Doctors can only consume items assigned to them
        if (Auth::user()->isDoctor() && $supplyItem->doctor_id !== Auth::id()) {
            abort(403);
        }
        $request->validate(['quantity' => 'required|integer|min:1']);

        $qty      = (int) $request->quantity;
        $newStock = max(0, $supplyItem->stock_quantity - $qty);
        $supplyItem->update(['stock_quantity' => $newStock]);

        if ($supplyItem->isLowStock()) {
            return redirect()
                ->route('supply-orders.create', ['item_id' => $supplyItem->id])
                ->with('warning', "Stock de « {$supplyItem->name} » bas ({$newStock} {$supplyItem->unit}). Envoyez une demande au fournisseur.");
        }

        return back()->with('success', "{$qty} {$supplyItem->unit} de « {$supplyItem->name} » consommé(s). Stock restant : {$newStock}.");
    }

    public function destroy(SupplyItem $supplyItem)
    {
        $supplyItem->delete();
        return redirect()->route('supply-items.index')->with('success', 'Article supprimé.');
    }

    public function categoriesIndex()
    {
        $categories = SupplyCategory::withCount('items')->orderBy('name')->get();
        return view('supply-items.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        SupplyCategory::create($request->only('name', 'description'));
        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function categoryDestroy(SupplyCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }


}
