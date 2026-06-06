<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CnamBulletin extends Model
{
    protected $fillable = ['appointment_id', 'patient_id', 'doctor_id', 'dental_acts', 'prostheses'];

    protected $casts = [
        'dental_acts' => 'array',
        'prostheses'  => 'array',
    ];

    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function patient()     { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor()      { return $this->belongsTo(User::class, 'doctor_id'); }
}
