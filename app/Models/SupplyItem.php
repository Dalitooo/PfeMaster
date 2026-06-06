<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'doctor_id', 'category_id', 'name', 'description', 'sku',
        'unit', 'unit_price', 'stock_quantity', 'min_stock_level', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'unit_price'  => 'decimal:2',
            'is_active'   => 'boolean',
        ];
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(SupplyCategory::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(SupplyOrderItem::class, 'item_id');
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }
}
