<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categoryTree = [
            [
                'name' => 'Pakaian Muslim Wanita',
                'slug' => 'pakaian-muslim-wanita',
                'icon' => 'sparkles',
                'description' => 'Koleksi gamis, abaya, tunik, dan busana muslimah elegan preloved berkualitas.',
                'sort_order' => 1,
                'subcategories' => [
                    'Gamis',
                    'Abaya',
                    'Tunik',
                    'Blouse Muslimah',
                    "Dress Syar'i",
                    'Rok Panjang',
                    'Celana Kulot',
                    'Outer Muslimah',
                    'Setelan Muslimah',
                ],
            ],
            [
                'name' => 'Pakaian Muslim Pria',
                'slug' => 'pakaian-muslim-pria',
                'icon' => 'shirt',
                'description' => 'Baju koko, kurta, jubah, dan busana muslim pria terawat dan berkualitas.',
                'sort_order' => 2,
                'subcategories' => [
                    'Baju Koko',
                    'Kurta',
                    'Jubah',
                    'Gamis Pria',
                    'Kemeja Muslim',
                    'Celana Sirwal',
                    'Sarung Pria',
                    'Setelan Muslim Pria',
                ],
            ],
            [
                'name' => 'Hijab & Kerudung',
                'slug' => 'hijab-kerudung',
                'icon' => 'sun',
                'description' => 'Pashmina, segi empat voal, bergo, khimar, dan inner hijab preloved maupun like-new.',
                'sort_order' => 3,
                'subcategories' => [
                    'Hijab Segi Empat',
                    'Pashmina',
                    'Bergo',
                    'Khimar',
                    'Hijab Instan',
                    'Ciput / Inner Hijab',
                ],
            ],
            [
                'name' => 'Perlengkapan Ibadah',
                'slug' => 'perlengkapan-ibadah',
                'icon' => 'moon',
                'description' => 'Mukena travel, sajadah premium, peci, tasbih, dan perlengkapan salat terawat.',
                'sort_order' => 4,
                'subcategories' => [
                    'Mukena',
                    'Sajadah',
                    'Sarung',
                    'Peci / Kopiah',
                    'Tasbih',
                    'Tas Mukena',
                    'Perlengkapan Salat Lainnya',
                ],
            ],
            [
                'name' => 'Aksesori Muslim',
                'slug' => 'aksesori-muslim',
                'icon' => 'gem',
                'description' => 'Bros hijab, manset tangan, kaus kaki wudhu, dan aksesoris penunjang busana muslim.',
                'sort_order' => 5,
                'subcategories' => [
                    'Bros Hijab',
                    'Pin Hijab',
                    'Manset Tangan',
                    'Kaus Kaki Muslimah',
                    'Aksesori Busana Muslim',
                    'Aksesori Fashion Muslim',
                ],
            ],
            [
                'name' => 'Alas Kaki & Tas',
                'slug' => 'alas-kaki-tas',
                'icon' => 'shopping-bag',
                'description' => 'Sepatu, sandal, tote bag, handbag, dan ransel sopan dan fungsional.',
                'sort_order' => 6,
                'subcategories' => [
                    'Sepatu',
                    'Sandal',
                    'Flat Shoes',
                    'Tote Bag',
                    'Handbag',
                    'Sling Bag',
                    'Backpack',
                ],
            ],
            [
                'name' => 'Fashion Muslim Anak',
                'slug' => 'fashion-muslim-anak',
                'icon' => 'heart',
                'description' => 'Gamis anak, koko cilik, mukena anak, dan pakaian muslim buah hati berkualitas.',
                'sort_order' => 7,
                'subcategories' => [
                    'Gamis Anak',
                    'Baju Koko Anak',
                    'Hijab Anak',
                    'Mukena Anak',
                    'Sarung Anak',
                    'Setelan Muslim Anak',
                ],
            ],
        ];

        foreach ($categoryTree as $parentData) {
            $subcategories = $parentData['subcategories'] ?? [];
            unset($parentData['subcategories']);

            $parent = Category::updateOrCreate(
                ['slug' => $parentData['slug']],
                array_merge($parentData, ['parent_id' => null, 'is_active' => true])
            );

            $subSort = 1;
            foreach ($subcategories as $subName) {
                $subSlug = Str::slug($parent->slug . '-' . $subName);
                Category::updateOrCreate(
                    ['slug' => $subSlug],
                    [
                        'parent_id' => $parent->id,
                        'name' => $subName,
                        'slug' => $subSlug,
                        'icon' => 'circle-dot',
                        'description' => "Kategori {$subName} dalam {$parent->name}.",
                        'is_active' => true,
                        'sort_order' => $subSort++,
                    ]
                );
            }
        }
    }
}
