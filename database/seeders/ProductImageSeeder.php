<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

/**
 * Mengganti gambar produk yang SUDAH ADA berdasarkan kode produk.
 * Data produk lain tidak disentuh. Produk dengan daftar kosong ([]) dilewati,
 * jadi gambar lamanya tetap dipakai.
 *
 * Cara pakai:
 *   php artisan db:seed --class=ProductImageSeeder
 */
class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // Elemen pertama = foto utama (tampil di katalog), sisanya = galeri di halaman detail.
        $map = [
            'SN-KOKO-001' => ['https://i.pinimg.com/1200x/40/58/60/405860183d946ff58dbd584284285573.jpg'],   // Baju Koko Putih Lengan Panjang Bordir (sudah punya foto)
            'SN-KOKO-002' => ['https://i.pinimg.com/736x/43/05/d4/4305d4789c781c63538a215ee099ebcd.jpg'], // Baju Koko Navy Lengan Pendek
            'SN-GMS-001'  => ['https://i.pinimg.com/736x/ad/a4/e9/ada4e92e1ad16aa1be414d7c357e20ca.jpg'], // Gamis Polos Olive Green
            'SN-GMS-002'  => ['https://i.pinimg.com/1200x/7e/6c/63/7e6c6383791e183141fe6bc5943d601d.jpg'], // JUBAH
            'SN-JKT-001'  => ['https://i.pinimg.com/736x/9c/5d/81/9c5d8173577a35b0248d45f77776f864.jpg'], // Hoodie Hitam Oversize Fleece
            'SN-JKT-002'  => ['https://i.pinimg.com/736x/cc/af/1f/ccaf1f3f325ec645ac18b8b45e637bee.jpg'],   // Jaket Parka Olive Army
            'SN-SRG-001'  => ['https://i.pinimg.com/736x/13/08/c6/1308c637694a9a325af08ff222b5b311.jpg'],   // Sarung Tenun Motif Kotak Hijau
            'SN-SRG-002'  => ['https://i.pinimg.com/1200x/ce/e5/a3/cee5a31c56263f343b9d25f4711e20c8.jpg'],   // Sarung Wadimor Motif Garis Biru
            'SN-KMJ-001'  => ['https://i.pinimg.com/736x/55/ea/9a/55ea9aa04046afc9bea93b822abaac2b.jpg'],   // Kemeja Putih Seragam Santri
            'SN-KMJ-002'  => ['https://i.pinimg.com/736x/6e/57/65/6e576586b6a8681cc146cf6c1beded48.jpg'],   // Kaos Polos Cotton Combed Abu
            'SN-SPT-001'  => ['https://i.pinimg.com/736x/ef/de/e2/efdee292db81dae6c70b3a5cedc663d7.jpg'],   // Sepatu Pantofel Hitam Pria
            'SN-SPT-002'  => ['https://i.pinimg.com/736x/b7/ee/0a/b7ee0af1977880be02668ff5e8a3c789.jpg'],   // Sandal Kulit Santri Cokelat
            'ST-ABY-001'  => ['https://i.pinimg.com/736x/c4/97/27/c497275601ea5b29cda727f29473d21f.jpg'],   // Abaya Dubai Hitam Bordir Emas
            'ST-GMS-002'  => ['https://i.pinimg.com/736x/23/5e/e4/235ee415cdbeec102a214fb8e3cb24b8.jpg'],
        ];

        foreach ($map as $code => $urls) {
            if (empty($urls)) {
                continue;
            }

            $product = Product::where('code', $code)->first();

            if (!$product) {
                $this->command?->warn("Produk {$code} tidak ditemukan, dilewati.");
                continue;
            }

            ProductImage::where('product_id', $product->id)->delete();

            foreach (array_values($urls) as $i => $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $url,
                    'sort_order' => $i + 1,
                    'is_primary' => $i === 0,
                    'is_defect'  => false,
                ]);
            }

            $this->command?->info("Gambar {$code} diperbarui (" . count($urls) . " foto).");
        }
    }
}
