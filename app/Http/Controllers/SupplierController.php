<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('items');

        if ($search = $request->get('search')) {
            $query->where('company_name', 'like', "%$search%")
                  ->orWhere('contact_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }

        $suppliers = $query->orderBy('company_name')->paginate(15)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string',
            'city'         => 'nullable|string|max:100',
            'website'      => 'nullable|url|max:255',
            'notes'        => 'nullable|string',
            'create_user'  => 'boolean',
            'user_email'   => 'nullable|required_if:create_user,1|email|unique:users,email',
            'user_password'=> 'nullable|required_if:create_user,1|string|min:8',
        ]);

        $userId = null;
        if ($request->boolean('create_user')) {
            $user = User::create([
                'name'     => $validated['company_name'],
                'email'    => $validated['user_email'],
                'password' => Hash::make($validated['user_password']),
                'role'     => 'supplier',
            ]);
            $userId = $user->id;
        }

        Supplier::create([
            'user_id'      => $userId,
            'company_name' => $validated['company_name'],
            'contact_name' => $validated['contact_name'] ?? null,
            'phone'        => $validated['phone'] ?? null,
            'email'        => $validated['email'] ?? null,
            'address'      => $validated['address'] ?? null,
            'city'         => $validated['city'] ?? null,
            'website'      => $validated['website'] ?? null,
            'notes'        => $validated['notes'] ?? null,
        ]);

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier added successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['items.category', 'orders.orderedBy']);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'address'      => 'nullable|string',
            'city'         => 'nullable|string|max:100',
            'website'      => 'nullable|url|max:255',
            'notes'        => 'nullable|string',
            'is_active'    => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $supplier->update($validated);

        return redirect()->route('suppliers.show', $supplier)
                         ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier deleted.');
    }
}
