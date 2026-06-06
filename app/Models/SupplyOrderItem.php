<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplyOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'item_id', 'quantity', 'unit_price', 'subtotal'];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'subtotal'   => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(SupplyOrder::class, 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(SupplyItem::class, 'item_id');
    }
}
