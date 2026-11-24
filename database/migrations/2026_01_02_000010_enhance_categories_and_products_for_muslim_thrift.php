<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'parent_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'subcategory_id')) {
                    $table->foreignId('subcategory_id')->nullable()->after('category_id')->constrained('categories')->nullOnDelete();
                }
                if (!Schema::hasColumn('products', 'original_price')) {
                    $table->decimal('original_price', 12, 2)->nullable()->after('selling_price');
                }
                if (!Schema::hasColumn('products', 'material')) {
                    $table->string('material')->nullable()->after('color');
                }
                if (!Schema::hasColumn('products', 'gender')) {
                    $table->string('gender', 20)->default('women')->after('material');
                }
                if (!Schema::hasColumn('products', 'location')) {
                    $table->string('location')->default('Jakarta')->after('gender');
                }
                if (!Schema::hasColumn('products', 'is_used')) {
                    $table->boolean('is_used')->default(true)->after('location');
                }
                if (!Schema::hasColumn('products', 'defect_description')) {
                    $table->text('defect_description')->nullable()->after('is_used');
                }
                if (!Schema::hasColumn('products', 'specific_attributes')) {
                    $table->json('specific_attributes')->nullable()->after('defect_description');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $columns = ['subcategory_id', 'original_price', 'material', 'gender', 'location', 'is_used', 'defect_description', 'specific_attributes'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('products', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'parent_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            });
        }
    }
};

