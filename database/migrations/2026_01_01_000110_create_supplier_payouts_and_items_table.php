<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('payout_number')->unique();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('bank_transfer');
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_holder');
            $table->string('reference_number')->nullable();
            $table->string('proof_image')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('payout_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_payout_id')->constrained('supplier_payouts')->cascadeOnDelete();
            $table->foreignId('profit_share_id')->constrained('profit_shares');
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_items');
        Schema::dropIfExists('supplier_payouts');
    }
};

