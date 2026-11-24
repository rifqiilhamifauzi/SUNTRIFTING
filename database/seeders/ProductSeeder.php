<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $supplierMadinah = Supplier::where('name', 'like', '%Madinah%')->first() ?: Supplier::first();
        $supplierBayan = Supplier::where('name', 'like', '%Bayan%')->first() ?: Supplier::first();
        $supplierHijab = Supplier::where('name', 'like', '%Hijab%')->first() ?: Supplier::first();
        $supplierSakinah = Supplier::where('name', 'like', '%Sakinah%')->first() ?: Supplier::first();

        // Get parent categories
        // Dibuat otomatis kalau belum ada, supaya seeder tidak error
        $makeCategory = fn (string $slug, string $name, string $icon, int $order) => Category::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'icon' => $icon, 'description' => $name, 'sort_order' => $order]
        );

        $catWanita = $makeCategory('pakaian-muslim-wanita', 'Pakaian Muslim Wanita', 'shirt', 10);
        $catPria = $makeCategory('pakaian-muslim-pria', 'Pakaian Muslim Pria', 'shirt', 11);
        $catHijab = $makeCategory('hijab-kerudung', 'Hijab & Kerudung', 'layers', 12);
        $catIbadah = $makeCategory('perlengkapan-ibadah', 'Perlengkapan Ibadah', 'moon', 13);
        $catAksesori = $makeCategory('aksesori-muslim', 'Aksesori Muslim', 'tag', 14);
        $catAnak = $makeCategory('fashion-muslim-anak', 'Fashion Muslim Anak', 'smile', 15);

        // Subcategories
        $subAbaya = Category::where('name', 'Abaya')->first();
        $subGamis = Category::where('name', 'Gamis')->first();
        $subTunik = Category::where('name', 'Tunik')->first();
        $subKoko = Category::where('name', 'Baju Koko')->first();
        $subKurta = Category::where('name', 'Kurta')->first();
        $subSirwal = Category::where('name', 'Celana Sirwal')->first();
        $subPashmina = Category::where('name', 'Pashmina')->first();
        $subSegiEmpat = Category::where('name', 'Hijab Segi Empat')->first();
        $subMukena = Category::where('name', 'Mukena')->first();
        $subSajadah = Category::where('name', 'Sajadah')->first();
        $subBros = Category::where('name', 'Bros Hijab')->first();
        $subGamisAnak = Category::where('name', 'Gamis Anak')->first();

        /*
         |--------------------------------------------------------------------
         | GAMBAR PRODUK (dari link online)
         |--------------------------------------------------------------------
         | Isi 'images' dengan link langsung ke file gambar (.jpg/.png/.webp).
         | Elemen pertama = foto utama (tampil di katalog), sisanya = galeri
         | di halaman detail produk. Link placehold.co di bawah hanya contoh
         | sementara, silakan ganti dengan link gambar Anda.
         */
        $items = [
            [
                'supplier_id' => $supplierMadinah->id,
                'category_id' => $catWanita->id,
                'subcategory_id' => $subAbaya?->id,
                'name' => 'Abaya Dubai Hitam Bordir Emas Vintage Authentic',
                'code' => 'ST-ABY-001',
                'description' => 'Abaya hitam khas Timur Tengah berbahan nida premium jatuh, halus dan dingin. Detail bordir benang emas asli di pergelangan tangan dan bagian dada. Busui friendly dengan resleting jepang tersembunyi. Kondisi preloved sangat terawat, dipakai 2 kali untuk resepsi keluarga.',
                'condition' => 'like_new',
                'agreed_price' => 315000,
                'selling_price' => 350000,
                'original_price' => 750000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'M-L (LD: 105cm, PB: 140cm)',
                'brand' => 'Al-Haramain Dubai',
                'color' => 'Jet Black / Gold',
                'material' => 'Nida Premium Jetblack',
                'gender' => 'women',
                'location' => 'Bandung',
                'is_used' => true,
                'defect_description' => null,
                'weight_grams' => 650,
                'status' => 'available',
                'images' => [
                    'https://i.pinimg.com/736x/c4/97/27/c497275601ea5b29cda727f29473d21f.jpg',
                ],
            ],
            [
                'supplier_id' => $supplierMadinah->id,
                'category_id' => $catWanita->id,
                'subcategory_id' => $subGamis?->id,
                'name' => 'Gamis Linen Crinkle Syar\'i Earthy Olive',
                'code' => 'ST-GMS-002',
                'description' => 'Gamis kasual berbahan linen crinkle premium dengan potongan A-line yang flowy. Terdapat saku kanan-kiri fungsional dan manset kancing wudhu friendly. Bahan adem dan tidak menerawang, sangat cocok untuk pengajian atau santai sehari-hari.',
                'condition' => 'good',
                'agreed_price' => 171000,
                'selling_price' => 190000,
                'original_price' => 380000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'L (LD: 108cm, PB: 138cm)',
                'brand' => 'Vanilla Hijab Archive',
                'color' => 'Olive Sage',
                'material' => 'Linen Crinkle Airflow',
                'gender' => 'women',
                'location' => 'Bandung',
                'is_used' => true,
                'defect_description' => 'Sedikit benang halus terlepas di bagian kelim bawah dalam, sama sekali tidak tampak saat dikenakan.',
                'weight_grams' => 500,
                'status' => 'available',
                'images' => [
                    'https://i.pinimg.com/736x/7c/cf/9b/7ccf9b66530531043ddbafe209ee692f.jpg',
                ],
            ],
            [
                'supplier_id' => $supplierMadinah->id,
                'category_id' => $catWanita->id,
                'subcategory_id' => $subTunik?->id,
                'name' => 'Tunik Busui Asimetris Pastel Floral Silk',
                'code' => 'ST-TNK-003',
                'description' => 'Tunik motif bunga lembut pastel dengan potongan hem asimetris modern. Bahan dior silk lembut dengan kilau anggun. Dilengkapi kancing aktif depan untuk kemudahan ibu menyusui.',
                'condition' => 'brand_new',
                'agreed_price' => 202500,
                'selling_price' => 225000,
                'original_price' => 450000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'All Size fit to XL (LD: 110cm)',
                'brand' => 'Ria Miranda Curated',
                'color' => 'Dusty Rose & Cream',
                'material' => 'Dior Silk High Quality',
                'gender' => 'women',
                'location' => 'Jakarta Selatan',
                'is_used' => false,
                'defect_description' => null,
                'weight_grams' => 380,
                'status' => 'available',
                'images' => [
                    'https://i.pinimg.com/736x/c4/97/27/c497275601ea5b29cda727f29473d21f.jpg',
                ],
            ],
            [
                'supplier_id' => $supplierBayan->id,
                'category_id' => $catPria->id,
                'subcategory_id' => $subKoko?->id,
                'name' => 'Baju Koko Katun Madinah Sage Green Lengan Panjang',
                'code' => 'ST-KOKO-004',
                'description' => 'Koko lengan panjang khas katun Madinah dengan serat kain bertekstur two-tone natural. Kerah shanghai minimalis dengan aksen kancing tertutup rapi. Sangat nyaman untuk salat Jumat maupun kegiatan kajian.',
                'condition' => 'like_new',
                'agreed_price' => 144000,
                'selling_price' => 160000,
                'original_price' => 320000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'XL (Lebar Dada: 58cm, PB: 76cm)',
                'brand' => 'Rabbani Heritage',
                'color' => 'Sage Green',
                'material' => 'Katun Madinah Original',
                'gender' => 'men',
                'location' => 'Yogyakarta',
                'is_used' => true,
                'defect_description' => null,
                'weight_grams' => 350,
                'status' => 'available',
                'images' => [
                    'https://i.pinimg.com/736x/43/05/d4/4305d4789c781c63538a215ee099ebcd.jpg',
                ],
            ],
            [
                'supplier_id' => $supplierBayan->id,
                'category_id' => $catPria->id,
                'subcategory_id' => $subKurta?->id,
                'name' => 'Kurta Modern Minimalis Katun Oxford Khaki',
                'code' => 'ST-KRT-005',
                'description' => 'Kurta panjang sepinggul dengan potongan modern minimalis. Bahan katun oxford premium yang tebal namun tetap adem. Dilengkapi saku samping kanan-kiri yang lega untuk menyimpan dompet dan buku doa.',
                'condition' => 'good',
                'agreed_price' => 121500,
                'selling_price' => 135000,
                'original_price' => 275000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'L (LD: 55cm, PB: 84cm)',
                'brand' => 'Samase Preloved',
                'color' => 'Warm Khaki',
                'material' => 'Oxford Cotton',
                'gender' => 'men',
                'location' => 'Yogyakarta',
                'is_used' => true,
                'defect_description' => 'Warna sedikit pudar wajar pemakaian di bagian dalam kerah belakang, bagian luar mulus bersih.',
                'weight_grams' => 400,
                'status' => 'available',
                'images' => [
                    'https://placehold.co/800x800/252A27/EAE3D5.png?text=KURTA+MODERN',
                ],
            ],
            [
                'supplier_id' => $supplierBayan->id,
                'category_id' => $catPria->id,
                'subcategory_id' => $subSirwal?->id,
                'name' => 'Celana Sirwal Chino Casual Semi-Slim Black',
                'code' => 'ST-SRW-006',
                'description' => 'Celana sirwal model chino di atas mata kaki (laa isbal) dengan potongan semi-slim rapi. Pinggang karet bertali serut fleksibel. Dilengkapi 4 saku fungsional. Sangat cocok dipadukan dengan koko modern atau kemeja santai.',
                'condition' => 'like_new',
                'agreed_price' => 130500,
                'selling_price' => 145000,
                'original_price' => 260000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => '32-34 (Panjang 88cm)',
                'brand' => 'Bunayya Sunnah Clothing',
                'color' => 'Solid Black',
                'material' => 'Twill Chino Stretch',
                'gender' => 'men',
                'location' => 'Yogyakarta',
                'is_used' => true,
                'defect_description' => null,
                'weight_grams' => 450,
                'status' => 'available',
                'images' => [
                    'https://placehold.co/800x800/252A27/26745A.png?text=SIRWAL+CHINO',
                ],
            ],
            [
                'supplier_id' => $supplierHijab->id,
                'category_id' => $catHijab->id,
                'subcategory_id' => $subPashmina?->id,
                'name' => 'Pashmina Silk Shimmer Soft Emerald Glow',
                'code' => 'ST-PSH-007',
                'description' => 'Pashmina silk shimmer mewah dengan efek kilau elegan tanpa terkesan berlebihan. Tekstur lembut, mudah dibentuk tegak di dahi dan tidak licin saat memakai inner. Cocok untuk acara formal maupun daily styling.',
                'condition' => 'brand_new',
                'agreed_price' => 85500,
                'selling_price' => 95000,
                'original_price' => 180000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => '180 x 75 cm',
                'brand' => 'Buttonscarves Archive',
                'color' => 'Soft Emerald',
                'material' => 'Cradle Shimmer Silk',
                'gender' => 'women',
                'location' => 'Jakarta Selatan',
                'is_used' => false,
                'defect_description' => null,
                'weight_grams' => 180,
                'status' => 'available',
                'images' => [
                    'https://placehold.co/800x800/26745A/F8F6F0.png?text=PASHMINA+SILK',
                ],
            ],
            [
                'supplier_id' => $supplierHijab->id,
                'category_id' => $catHijab->id,
                'subcategory_id' => $subSegiEmpat?->id,
                'name' => 'Hijab Segi Empat Voal Ultrafine Motif Khazanah Beige',
                'code' => 'ST-SGT-008',
                'description' => 'Jilbab segi empat voal ultrafine motif ornamen islami geometris minimalis warna earth tone beige. Finishing lasercut rapi di keempat sisinya. Bahan dingin, tegak paripurna di dahi tanpa perlu semprotan hairspray.',
                'condition' => 'like_new',
                'agreed_price' => 112500,
                'selling_price' => 125000,
                'original_price' => 245000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => '115 x 115 cm',
                'brand' => 'Kami Idea Preloved',
                'color' => 'Warm Beige / Muted Gold',
                'material' => 'Voal Ultrafine Tryspan',
                'gender' => 'women',
                'location' => 'Jakarta Selatan',
                'is_used' => true,
                'defect_description' => null,
                'weight_grams' => 150,
                'status' => 'available',
                'images' => [
                    'https://placehold.co/800x800/EAE3D5/174C3C.png?text=VOAL+ULTRAFINE',
                ],
            ],
            [
                'supplier_id' => $supplierSakinah->id,
                'category_id' => $catIbadah->id,
                'subcategory_id' => $subMukena?->id,
                'name' => 'Mukena Silk Renda Prancis Premium Travelling Set',
                'code' => 'ST-MKN-009',
                'description' => 'Mukena bahan armany silk dingin lembut dengan aplikasi renda prancis premium di tepian atasan. Ringan dan ringkas, sudah termasuk pouch travel clutch dan sajadah muka kecil. Kondisi mulus bersih tanpa noda.',
                'condition' => 'like_new',
                'agreed_price' => 261000,
                'selling_price' => 290000,
                'original_price' => 580000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'Jumbo Syar\'i Dewasa',
                'brand' => 'Tazbiya Curated',
                'color' => 'Forest Sage & Cream',
                'material' => 'Armany Silk + French Lace',
                'gender' => 'women',
                'location' => 'Malang',
                'is_used' => true,
                'defect_description' => null,
                'weight_grams' => 550,
                'status' => 'available',
                'images' => [
                    'https://placehold.co/800x800/174C3C/F8F6F0.png?text=MUKENA+SILK',
                ],
            ],
            [
                'supplier_id' => $supplierSakinah->id,
                'category_id' => $catIbadah->id,
                'subcategory_id' => $subSajadah?->id,
                'name' => 'Sajadah Busa Turkey Tebal Ornamen Mihrab Vintage',
                'code' => 'ST-SJD-010',
                'description' => 'Sajadah import Turki dengan lapisan busa empuk 1.5 cm di dalam, sangat nyaman untuk sujud dan pelindung lutut. Bulu beludru padat anti-rontok dengan rumbai benang sutra emas di kedua ujungnya.',
                'condition' => 'good',
                'agreed_price' => 162000,
                'selling_price' => 180000,
                'original_price' => 350000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => '70 x 115 cm (Tebal 15mm)',
                'brand' => 'Istanbul Heritage Carpet',
                'color' => 'Deep Emerald & Gold',
                'material' => 'Velvet Silk Foam Cushioned',
                'gender' => 'unisex',
                'location' => 'Malang',
                'is_used' => true,
                'defect_description' => 'Ada sedikit jejak tekanan lipatan busa di sisi bawah, tidak mempengaruhi kenyamanan sujud sama sekali.',
                'weight_grams' => 950,
                'status' => 'available',
                'images' => [
                    'https://placehold.co/800x800/174C3C/D4AF37.png?text=SAJADAH+TURKEY',
                ],
            ],
            [
                'supplier_id' => $supplierHijab->id,
                'category_id' => $catAksesori->id,
                'subcategory_id' => $subBros?->id,
                'name' => 'Bros Hijab Mutiara Barok Zirconia Anti-Karat',
                'code' => 'ST-BRS-011',
                'description' => 'Bros hijab mewah dengan kombinasi mutiara barok alami dan taburan kristal zirconia berkilau. Pin peniti tajam tidak merusak serat kain jilbab sutra maupun voal. Dilengkapi kotak perhiasan beludru asli.',
                'condition' => 'brand_new',
                'agreed_price' => 76500,
                'selling_price' => 85000,
                'original_price' => 165000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'Diameter 4.5 cm',
                'brand' => 'Khadijah Atelier',
                'color' => 'Silver Pearl',
                'material' => 'Alloy Rhodium + Freshwater Pearl',
                'gender' => 'women',
                'location' => 'Jakarta Selatan',
                'is_used' => false,
                'defect_description' => null,
                'weight_grams' => 120,
                'status' => 'available',
                'images' => [
                    'https://placehold.co/800x800/858B87/FFFFFF.png?text=BROS+MUTIARA',
                ],
            ],
            [
                'supplier_id' => $supplierMadinah->id,
                'category_id' => $catAnak->id,
                'subcategory_id' => $subGamisAnak?->id,
                'name' => 'Gamis Anak Katun Jepang Motif Bunga Ruffle Lilac',
                'code' => 'ST-ANK-012',
                'description' => 'Set gamis anak perempuan berbahan katun jepang tokai original dengan jilbab bergo senada. Lembut, menyerap keringat dan tidak gerah saat dipakai si kecil beraktivitas. Tali pinggang belakang untuk menyesuaikan lingkar tubuh.',
                'condition' => 'like_new',
                'agreed_price' => 99000,
                'selling_price' => 110000,
                'original_price' => 220000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'Size 6 (Usia 5-7 Tahun, LD: 74cm, PB: 90cm)',
                'brand' => 'Cutetrik Kids',
                'color' => 'Lilac Pastel & White',
                'material' => '100% Katun Jepang Tokai',
                'gender' => 'kids',
                'location' => 'Bandung',
                'is_used' => true,
                'defect_description' => null,
                'weight_grams' => 300,
                'status' => 'available',
                'images' => [
                    'https://placehold.co/800x800/26745A/F8F6F0.png?text=GAMIS+ANAK',
                ],
            ],
        ];

        foreach ($items as $data) {
            $images = $data['images'] ?? [];
            unset($data['images']);

            $product = Product::updateOrCreate(
                ['code' => $data['code']],
                $data
            );

            // Hapus gambar lama lalu buat ulang, supaya aman dijalankan berulang
            $product->images()->delete();

            foreach (array_values($images) as $i => $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $url,            // URL penuh (https://...)
                    'sort_order' => $i + 1,
                    'is_primary' => $i === 0,  // gambar pertama = foto utama
                    'is_defect' => false,
                ]);
            }
        }
    }
}