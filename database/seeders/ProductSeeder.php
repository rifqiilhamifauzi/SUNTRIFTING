<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $supplierMadinah = Supplier::where('name', 'like', '%Madinah%')->first() ?: Supplier::first();
        $supplierBayan = Supplier::where('name', 'like', '%Bayan%')->first() ?: Supplier::first();
        $supplierHijab = Supplier::where('name', 'like', '%Hijab%')->first() ?: Supplier::first();
        $supplierSakinah = Supplier::where('name', 'like', '%Sakinah%')->first() ?: Supplier::first();

        // Get parent categories
        $catWanita = Category::where('slug', 'pakaian-muslim-wanita')->first();
        $catPria = Category::where('slug', 'pakaian-muslim-pria')->first();
        $catHijab = Category::where('slug', 'hijab-kerudung')->first();
        $catIbadah = Category::where('slug', 'perlengkapan-ibadah')->first();
        $catAksesori = Category::where('slug', 'aksesori-muslim')->first();
        $catAnak = Category::where('slug', 'fashion-muslim-anak')->first();

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

        $storageDir = storage_path('app/public/products');
        if (!File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

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
                'svg_file' => 'abaya-dubai.svg',
                'title_short' => 'ABAYA DUBAI',
                'tag_sub' => 'VINTAGE JETBLACK',
                'color_bg' => '#174C3C',
                'color_accent' => '#D4AF37',
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
                'svg_file' => 'gamis-linen.svg',
                'title_short' => 'GAMIS LINEN',
                'tag_sub' => 'EARTHY OLIVE',
                'color_bg' => '#26745A',
                'color_accent' => '#EAE3D5',
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
                'svg_file' => 'tunik-floral.svg',
                'title_short' => 'TUNIK FLORAL',
                'tag_sub' => 'BRAND NEW TAG',
                'color_bg' => '#858B87',
                'color_accent' => '#F8F6F0',
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
                'svg_file' => 'koko-madinah.svg',
                'title_short' => 'KOKO MADINAH',
                'tag_sub' => 'SAGE GREEN LONG',
                'color_bg' => '#174C3C',
                'color_accent' => '#F8F6F0',
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
                'svg_file' => 'kurta-oxford.svg',
                'title_short' => 'KURTA MODERN',
                'tag_sub' => 'OXFORD KHAKI',
                'color_bg' => '#252A27',
                'color_accent' => '#EAE3D5',
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
                'svg_file' => 'sirwal-chino.svg',
                'title_short' => 'SIRWAL CHINO',
                'tag_sub' => 'CASUAL STRETCH',
                'color_bg' => '#252A27',
                'color_accent' => '#26745A',
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
                'svg_file' => 'pashmina-silk.svg',
                'title_short' => 'PASHMINA SILK',
                'tag_sub' => 'SHIMMER EMERALD',
                'color_bg' => '#26745A',
                'color_accent' => '#F8F6F0',
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
                'svg_file' => 'voal-motif.svg',
                'title_short' => 'VOAL ULTRAFINE',
                'tag_sub' => 'LASERCUT MOTIF',
                'color_bg' => '#EAE3D5',
                'color_accent' => '#174C3C',
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
                'svg_file' => 'mukena-silk.svg',
                'title_short' => 'MUKENA SILK',
                'tag_sub' => 'FRENCH LACE POUCH',
                'color_bg' => '#174C3C',
                'color_accent' => '#F8F6F0',
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
                'svg_file' => 'sajadah-turkey.svg',
                'title_short' => 'SAJADAH TURKEY',
                'tag_sub' => 'VELVET CUSHIONED',
                'color_bg' => '#174C3C',
                'color_accent' => '#D4AF37',
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
                'svg_file' => 'bros-mutiara.svg',
                'title_short' => 'BROS MUTIARA',
                'tag_sub' => 'ZIRCONIA RHODIUM',
                'color_bg' => '#858B87',
                'color_accent' => '#FFFFFF',
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
                'svg_file' => 'gamis-anak.svg',
                'title_short' => 'GAMIS ANAK',
                'tag_sub' => 'KATUN JEPANG SET',
                'color_bg' => '#26745A',
                'color_accent' => '#F8F6F0',
            ],
        ];

        foreach ($items as $data) {
            $svgFile = $data['svg_file'];
            $titleShort = $data['title_short'];
            $tagSub = $data['tag_sub'];
            $colorBg = $data['color_bg'];
            $colorAccent = $data['color_accent'];

            unset($data['svg_file'], $data['title_short'], $data['tag_sub'], $data['color_bg'], $data['color_accent']);

            // Generate clean SVG visual
            $filePath = $storageDir . '/' . $svgFile;
            $svgContent = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 1000" width="100%" height="100%">
  <defs>
    <linearGradient id="grad-{$data['code']}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$colorBg}" />
      <stop offset="100%" stop-color="#12352A" />
    </linearGradient>
    <pattern id="pat-{$data['code']}" width="40" height="40" patternUnits="userSpaceOnUse">
      <circle cx="20" cy="20" r="1.5" fill="{$colorAccent}" opacity="0.15" />
    </pattern>
  </defs>

  <!-- Background -->
  <rect width="800" height="1000" fill="url(#grad-{$data['code']})" />
  <rect width="800" height="1000" fill="url(#pat-{$data['code']})" />

  <!-- Frame / Border -->
  <rect x="40" y="40" width="720" height="920" rx="24" fill="none" stroke="{$colorAccent}" stroke-width="2" opacity="0.3" />
  <rect x="52" y="52" width="696" height="896" rx="16" fill="none" stroke="{$colorAccent}" stroke-width="1" opacity="0.15" />

  <!-- Brand Watermark -->
  <text x="400" y="140" font-family="'Plus Jakarta Sans', sans-serif" font-size="20" font-weight="800" fill="{$colorAccent}" text-anchor="middle" letter-spacing="8" opacity="0.85">SUN-THRIFTING</text>
  <text x="400" y="170" font-family="'Inter', sans-serif" font-size="12" font-weight="600" fill="#FAF9F6" text-anchor="middle" letter-spacing="4" opacity="0.6">CURATED MUSLIM ARCHIVE</text>

  <!-- Center Decorative Emblem -->
  <g transform="translate(400, 480)">
    <circle r="180" fill="none" stroke="{$colorAccent}" stroke-width="2" stroke-dasharray="6,6" opacity="0.35" />
    <circle r="150" fill="rgba(255,255,255,0.04)" stroke="{$colorAccent}" stroke-width="1.5" opacity="0.6" />
    
    <!-- Geometric Islamic Star / Octagram Accent -->
    <rect x="-60" y="-60" width="120" height="120" rx="12" fill="none" stroke="{$colorAccent}" stroke-width="2.5" opacity="0.8" />
    <rect x="-60" y="-60" width="120" height="120" rx="12" fill="none" stroke="{$colorAccent}" stroke-width="2.5" opacity="0.8" transform="rotate(45)" />
    
    <!-- Item Icon / Lettering -->
    <text x="0" y="12" font-family="'Plus Jakarta Sans', sans-serif" font-size="34" font-weight="800" fill="#FFFFFF" text-anchor="middle">ST</text>
  </g>

  <!-- Product Labels -->
  <text x="400" y="740" font-family="'Plus Jakarta Sans', sans-serif" font-size="32" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="2">{$titleShort}</text>
  <text x="400" y="780" font-family="'Inter', sans-serif" font-size="16" font-weight="600" fill="{$colorAccent}" text-anchor="middle" letter-spacing="3">{$tagSub}</text>

  <!-- Bottom Badge: Condition & Price -->
  <rect x="220" y="830" width="360" height="50" rx="25" fill="rgba(255,255,255,0.12)" stroke="{$colorAccent}" stroke-width="1" />
  <text x="400" y="862" font-family="'Inter', sans-serif" font-size="16" font-weight="700" fill="#FFFFFF" text-anchor="middle">Rp {$data['selling_price']} • {$data['condition']}</text>
</svg>
SVG;
            File::put($filePath, $svgContent);

            $product = Product::updateOrCreate(
                ['code' => $data['code']],
                $data
            );

            // Create primary product image
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_primary' => true],
                [
                    'path' => 'products/' . $svgFile,
                    'sort_order' => 1,
                    'is_primary' => true,
                    'is_defect' => false,
                ]
            );
        }
    }
}
