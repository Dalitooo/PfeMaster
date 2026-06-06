<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'appointment_id', 'issued_by', 'invoice_number',
        'status', 'subtotal', 'discount', 'tax', 'total', 'due_date', 'paid_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax'      => 'decimal:2',
            'total'    => 'decimal:2',
            'due_date' => 'date',
            'paid_at'  => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'draft'     => 'bg-gray-100 text-gray-800',
            'issued'    => 'bg-blue-100 text-blue-800',
            'paid'      => 'bg-green-100 text-green-800',
            'overdue'   => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default     => 'bg-gray-100 text-gray-800',
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $last = Invoice::max('invoice_number');
                $next = $last ? ((int) substr($last, 4)) + 1 : 1;
                do {
                    $number = 'INV-' . str_pad($next++, 5, '0', STR_PAD_LEFT);
                } while (Invoice::where('invoice_number', $number)->exists());
                $invoice->invoice_number = $number;
            }
        });
    }
}
