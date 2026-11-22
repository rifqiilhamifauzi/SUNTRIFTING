<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Vintage Jackets & Outerwear',
                'slug' => 'vintage-jackets-outerwear',
                'icon' => 'shirt',
                'description' => 'Jaket vintage, bomber, denim jacket, dan windbreaker pilihan.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Crewnecks & Hoodies',
                'slug' => 'crewnecks-hoodies',
                'icon' => 'sparkles',
                'description' => 'Sweater, hoodie, dan crewneck retro nyaman dari brand ternama.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Vintage Tees & Shirts',
                'slug' => 'vintage-tees-shirts',
                'icon' => 'tag',
                'description' => 'Kaos band, t-shirt vintage 90an, dan kemeja flannel.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Pants & Denim',
                'slug' => 'pants-denim',
                'icon' => 'layers',
                'description' => 'Celana Levi’s vintage, cargo pants, carpenter pants, dan chino.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Caps & Accessories',
                'slug' => 'caps-accessories',
                'icon' => 'crown',
                'description' => 'Topi snapback, tote bag, ikat pinggang kulit, dan aksesori.',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}

