<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('shipping_address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('order_status', [
                'pending_payment',
                'waiting_verification',
                'processing',
                'shipped',
                'completed',
                'cancelled',
                'refunded'
            ])->default('pending_payment')->index();
            $table->enum('payment_status', [
                'unpaid',
                'pending_verification',
                'paid',
                'rejected',
                'refunded'
            ])->default('unpaid')->index();
            $table->string('payment_method')->default('manual_transfer');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->string('product_name');
            $table->string('product_code');
            $table->decimal('product_price', 12, 2);
            $table->decimal('admin_commission_pct', 5, 2);
            $table->decimal('supplier_commission_pct', 5, 2);
            $table->decimal('admin_amount', 12, 2);
            $table->decimal('supplier_amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

