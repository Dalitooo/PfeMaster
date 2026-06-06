<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use App\Models\User;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function index()
    {
        $cabinets = Cabinet::with(['doctor', 'secretary'])
            ->orderBy('name')
            ->paginate(15);

        return view('cabinets.index', compact('cabinets'));
    }

    public function create()
    {
        $doctors    = User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        $secretaries = User::where('role', 'secretary')->where('is_active', true)->orderBy('name')->get();

        return view('cabinets.create', compact('doctors', 'secretaries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string|max:500',
            'doctor_id'    => 'nullable|exists:users,id',
            'secretary_id' => 'nullable|exists:users,id',
            'is_active'    => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Cabinet::create($validated);

        return redirect()->route('cabinets.index')
                         ->with('success', 'Medical office created successfully.');
    }

    public function edit(Cabinet $cabinet)
    {
        $doctors     = User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        $secretaries = User::where('role', 'secretary')->where('is_active', true)->orderBy('name')->get();

        return view('cabinets.edit', compact('cabinet', 'doctors', 'secretaries'));
    }

    public function update(Request $request, Cabinet $cabinet)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string|max:500',
            'doctor_id'    => 'nullable|exists:users,id',
            'secretary_id' => 'nullable|exists:users,id',
            'is_active'    => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $cabinet->update($validated);

        return redirect()->route('cabinets.index')
                         ->with('success', 'Medical office updated.');
    }

    public function destroy(Cabinet $cabinet)
    {
        $cabinet->delete();
        return redirect()->route('cabinets.index')
                         ->with('success', 'Medical office deleted.');
    }
}
