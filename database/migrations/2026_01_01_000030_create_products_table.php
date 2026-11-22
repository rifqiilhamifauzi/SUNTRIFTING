<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('condition', ['like_new', 'good', 'fair'])->default('good');
            $table->decimal('agreed_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->decimal('admin_commission_pct', 5, 2)->default(10.00);
            $table->decimal('supplier_commission_pct', 5, 2)->default(90.00);
            $table->string('size')->nullable();
            $table->string('brand')->nullable();
            $table->string('color')->nullable();
            $table->integer('weight_grams')->default(500);
            $table->enum('status', ['draft', 'available', 'reserved', 'sold', 'archived'])->default('available')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

