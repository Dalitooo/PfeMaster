<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplyOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'ordered_by', 'supplier_id', 'doctor_id', 'order_number', 'status', 'total_amount',
        'notes', 'ordered_at', 'expected_at', 'received_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'ordered_at'   => 'datetime',
            'expected_at'  => 'date',
            'received_at'  => 'date',
        ];
    }

    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function items()
    {
        return $this->hasMany(SupplyOrderItem::class, 'order_id');
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'draft'     => 'bg-gray-100 text-gray-800',
            'sent'      => 'bg-blue-100 text-blue-800',
            'confirmed' => 'bg-yellow-100 text-yellow-800',
            'shipped'   => 'bg-purple-100 text-purple-800',
            'received'  => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default     => 'bg-gray-100 text-gray-800',
        };
    }
}
