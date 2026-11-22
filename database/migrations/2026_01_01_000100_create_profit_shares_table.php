<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profit_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->decimal('selling_price', 12, 2);
            $table->decimal('admin_commission_pct', 5, 2);
            $table->decimal('supplier_commission_pct', 5, 2);
            $table->decimal('admin_amount', 12, 2);
            $table->decimal('supplier_amount', 12, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->index();
            $table->enum('payout_status', ['unpaid', 'paid'])->default('unpaid')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profit_shares');
    }
};

