<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supply_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('supply_orders')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('supply_items')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply_order_items');
    }
};
