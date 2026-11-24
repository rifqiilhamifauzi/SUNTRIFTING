<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'product_id']);
            });
        }

        if (Schema::hasTable('product_images') && !Schema::hasColumn('product_images', 'is_defect')) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->boolean('is_defect')->default(false)->after('is_primary');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_images') && Schema::hasColumn('product_images', 'is_defect')) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->dropColumn('is_defect');
            });
        }

        Schema::dropIfExists('wishlists');
    }
};

