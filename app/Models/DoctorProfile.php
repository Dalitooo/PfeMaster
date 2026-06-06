<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialization', 'license_number', 'bio',
        'schedule_start', 'schedule_end', 'working_days',
    ];

    protected function casts(): array
    {
        return ['working_days' => 'array'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
