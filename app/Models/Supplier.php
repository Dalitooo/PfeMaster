<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'contact_name', 'phone', 'email',
        'address', 'city', 'website', 'notes', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SupplyItem::class);
    }

    public function orders()
    {
        return $this->hasMany(SupplyOrder::class);
    }
}
