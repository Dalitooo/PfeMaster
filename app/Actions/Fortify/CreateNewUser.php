<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\PatientProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * @param  array<string, string>  $input
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password'   => $this->passwordRules(),
            'phone'      => ['nullable', 'string', 'max:20'],
            'cnam_id'    => ['nullable', 'string', 'max:50'],
            'cnam_type'  => ['nullable', 'in:cnss,cnrps'],
        ])->validate();

        $user = User::create([
            'first_name' => $input['first_name'],
            'last_name'  => $input['last_name'],
            'name'       => $input['first_name'] . ' ' . $input['last_name'],
            'email'      => $input['email'],
            'password'   => Hash::make($input['password']),
            'role'       => $input['role'] ?? 'patient',
            'phone'      => $input['phone'] ?? null,
        ]);

        if ($user->role === 'patient') {
            PatientProfile::create([
                'user_id'   => $user->id,
                'cnam_id'   => $input['cnam_id'] ?? null,
                'cnam_type' => $input['cnam_type'] ?? null,
            ]);
        }

        return $user;
    }
}
