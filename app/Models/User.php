<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'password', 'role', 'phone', 'address', 'is_active', 'profile_photo_path',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function treatmentRecordsAsPatient()
    {
        return $this->hasMany(TreatmentRecord::class, 'patient_id');
    }

    public function treatmentRecordsAsDoctor()
    {
        return $this->hasMany(TreatmentRecord::class, 'doctor_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id');
    }

    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isDoctor(): bool { return $this->role === 'doctor'; }
    public function isSecretary(): bool { return $this->role === 'secretary'; }
    public function isPatient(): bool { return $this->role === 'patient'; }
    public function isSupplier(): bool { return $this->role === 'supplier'; }

    public function canManageStaff(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'admin'       => 'Administrator',
            'doctor'      => 'Doctor',
            'secretary'   => 'Secretary',
            'patient'     => 'Patient',
            'supplier'    => 'Supplier',
            default       => ucfirst($this->role),
        };
    }

    public function getNameAttribute(): string
    {
        $first = $this->attributes['first_name'] ?? null;
        $last  = $this->attributes['last_name']  ?? null;
        if ($first || $last) {
            return trim(($first ?? '') . ' ' . ($last ?? ''));
        }
        return $this->attributes['name'] ?? '';
    }

    public function getAvatarUrl(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563EB&color=fff';
    }
}
