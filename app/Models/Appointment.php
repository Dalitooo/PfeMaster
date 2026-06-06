<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'secretary_id', 'cabinet_id', 'appointment_date',
        'duration_minutes', 'status', 'type', 'reason', 'notes',
    ];

    protected function casts(): array
    {
        return ['appointment_date' => 'datetime'];
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function secretary()
    {
        return $this->belongsTo(User::class, 'secretary_id');
    }

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function treatmentRecords()
    {
        return $this->hasMany(TreatmentRecord::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function cnamBulletins()
    {
        return $this->hasMany(CnamBulletin::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'pending'     => 'bg-yellow-100 text-yellow-800',
            'confirmed'   => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-purple-100 text-purple-800',
            'completed'   => 'bg-green-100 text-green-800',
            'cancelled'   => 'bg-red-100 text-red-800',
            'no_show'     => 'bg-gray-100 text-gray-800',
            default       => 'bg-gray-100 text-gray-800',
        };
    }

    public function getEndTime()
    {
        return $this->appointment_date->addMinutes($this->duration_minutes);
    }
}
