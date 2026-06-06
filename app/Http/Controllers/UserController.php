<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        } else {
            $query->whereIn('role', ['admin', 'doctor', 'secretary', 'supplier']);
        }

        if ($search = $request->get('search')) {
            $query->where(fn($q) => $q->where('name', 'like', "%$search%")
                                      ->orWhere('email', 'like', "%$search%"));
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'password'       => 'required|string|min:8|confirmed',
            'role'           => 'required|in:admin,doctor,secretary,supplier',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        if ($validated['role'] === 'doctor') {
            DoctorProfile::create([
                'user_id'        => $user->id,
                'specialization' => $validated['specialization'] ?? null,
                'license_number' => $validated['license_number'] ?? null,
            ]);
        }

        return redirect()->route('users.index')
                         ->with('success', 'Staff member added successfully.');
    }

    public function edit(User $user)
    {
        $user->load('doctorProfile');
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'role'           => 'required|in:admin,doctor,secretary,supplier',
            'is_active'      => 'boolean',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'password'       => 'nullable|string|min:8|confirmed',
        ]);

        $userData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'address'   => $validated['address'] ?? null,
            'role'      => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        if ($validated['role'] === 'doctor') {
            $user->doctorProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $validated['specialization'] ?? null,
                    'license_number' => $validated['license_number'] ?? null,
                ]
            );
        }

        return redirect()->route('users.index')
                         ->with('success', 'Staff member updated.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Cannot delete yourself.');
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }
}
