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
        $supplierBdg = Supplier::where('name', 'like', '%Bandung%')->first();
        $supplierJogja = Supplier::where('name', 'like', '%Jogja%')->first();
        $supplierSenen = Supplier::where('name', 'like', '%Senen%')->first();
        $supplierMlg = Supplier::where('name', 'like', '%Malang%')->first();

        $catJackets = Category::where('slug', 'vintage-jackets-outerwear')->first();
        $catHoodies = Category::where('slug', 'crewnecks-hoodies')->first();
        $catTees = Category::where('slug', 'vintage-tees-shirts')->first();
        $catPants = Category::where('slug', 'pants-denim')->first();
        $catCaps = Category::where('slug', 'caps-accessories')->first();

        // Ensure storage directory exists
        $storageDir = storage_path('app/public/products');
        if (!File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        $productsData = [
            [
                'supplier_id' => $supplierBdg->id,
                'category_id' => $catJackets->id,
                'name' => 'Vintage Carhartt Detroit Jacket J97 Moss Green',
                'code' => 'ST-JKT-001',
                'description' => 'Jaket Carhartt J97 Detroit Jacket warna Moss Green vintage authentic. Bahan heavy canvas cotton dengan blanket lining corduroy collar. Fading natural sangat cakep (distressed look). Kancing dan zipper tembaga original YKK.',
                'condition' => 'good',
                'agreed_price' => 1125000,
                'selling_price' => 1250000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'L (P: 66cm, L: 62cm)',
                'brand' => 'Carhartt',
                'color' => 'Moss Green',
                'weight_grams' => 1100,
                'status' => 'available',
                'image_filename' => 'carhartt-detroit.svg',
                'color_bg' => '#3E4E3B',
                'color_txt' => '#FAF9F6',
            ],
            [
                'supplier_id' => $supplierBdg->id,
                'category_id' => $catJackets->id,
                'name' => 'Levi\'s Type III Denim Trucker Jacket 70505',
                'code' => 'ST-JKT-002',
                'description' => 'Vintage Levi’s Type 3 denim jacket vintage era 80-90an. Made in USA, wash denim medium fade alami dengan whiskering di lengan. Red tab Levi’s terpasang utuh.',
                'condition' => 'like_new',
                'agreed_price' => 585000,
                'selling_price' => 650000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'M (P: 62cm, L: 54cm)',
                'brand' => 'Levi\'s',
                'color' => 'Indigo Blue',
                'weight_grams' => 850,
                'status' => 'available',
                'image_filename' => 'levis-trucker.svg',
                'color_bg' => '#2A4465',
                'color_txt' => '#FAF9F6',
            ],
            [
                'supplier_id' => $supplierSenen->id,
                'category_id' => $catHoodies->id,
                'name' => 'Champion Reverse Weave Crewneck Grey Heather',
                'code' => 'ST-HD-001',
                'description' => 'Champion Reverse Weave classic heavy cotton crewneck. Logo C patch bordir di dada dan lengan kiri. Bahan tebal 12oz tahan susut, ribbing pinggang & pergelangan tangan rapat.',
                'condition' => 'like_new',
                'agreed_price' => 378000,
                'selling_price' => 420000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'XL (P: 72cm, L: 64cm)',
                'brand' => 'Champion',
                'color' => 'Grey Heather',
                'weight_grams' => 700,
                'status' => 'available',
                'image_filename' => 'champion-crewneck.svg',
                'color_bg' => '#8C9095',
                'color_txt' => '#1C1C1C',
            ],
            [
                'supplier_id' => $supplierSenen->id,
                'category_id' => $catHoodies->id,
                'name' => 'Nike Center Swoosh Vintage Hoodie Navy 90s',
                'code' => 'ST-HD-002',
                'description' => 'Koleksi langka hoodie Nike vintage era 90-an dengan bordir Center Swoosh mini di tengah dada. Tag abu-abu (Grey Tag) vintage. Kondisi prima tanpa noda.',
                'condition' => 'good',
                'agreed_price' => 675000,
                'selling_price' => 750000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'L (P: 68cm, L: 60cm)',
                'brand' => 'Nike',
                'color' => 'Navy Blue',
                'weight_grams' => 650,
                'status' => 'available',
                'image_filename' => 'nike-center-swoosh.svg',
                'color_bg' => '#18253D',
                'color_txt' => '#FAF9F6',
            ],
            [
                'supplier_id' => $supplierJogja->id,
                'category_id' => $catTees->id,
                'name' => 'Nirvana In Utero 1993 Vintage Band Tee',
                'code' => 'ST-TEE-001',
                'description' => 'Kaos band vintage Nirvana In Utero album art. Single stitch hem, bahan ultra soft wash cotton dengan crackling natural pada sablonan. Tag Brockum vintage.',
                'condition' => 'good',
                'agreed_price' => 855000,
                'selling_price' => 950000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'L (P: 71cm, L: 56cm)',
                'brand' => 'Nirvana / Brockum',
                'color' => 'Faded Charcoal Black',
                'weight_grams' => 220,
                'status' => 'available',
                'image_filename' => 'nirvana-tee.svg',
                'color_bg' => '#242424',
                'color_txt' => '#EAE5DC',
            ],
            [
                'supplier_id' => $supplierJogja->id,
                'category_id' => $catTees->id,
                'name' => 'Ralph Lauren Classic Oxford Button-Down Striped Shirt',
                'code' => 'ST-TEE-002',
                'description' => 'Kemeja vintage Ralph Lauren Polo bahan oxford cotton tebal motif garis biru putih klasik. Bordir pony warna navy di dada kiri. Cocok untuk casual preppy look.',
                'condition' => 'like_new',
                'agreed_price' => 270000,
                'selling_price' => 300000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'M (P: 74cm, L: 55cm)',
                'brand' => 'Ralph Lauren',
                'color' => 'Blue/White Stripe',
                'weight_grams' => 300,
                'status' => 'available',
                'image_filename' => 'polo-oxford.svg',
                'color_bg' => '#4A6B82',
                'color_txt' => '#FAF9F6',
            ],
            [
                'supplier_id' => $supplierMlg->id,
                'category_id' => $catPants->id,
                'name' => 'Levi\'s 501 Original Straight Fit Vintage 90s Made in USA',
                'code' => 'ST-PNT-001',
                'description' => 'Celana denim ikonik Levi’s 501 Button Fly Made in USA era 1994. Kode kancing 553. Indigo wash fade autentik, belum pernah dipotong (inseam original).',
                'condition' => 'good',
                'agreed_price' => 495000,
                'selling_price' => 550000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'W32 L32 (Lingkar 84cm, P: 104cm)',
                'brand' => 'Levi\'s',
                'color' => 'Classic Stone Wash',
                'weight_grams' => 750,
                'status' => 'available',
                'image_filename' => 'levis-501.svg',
                'color_bg' => '#324E6B',
                'color_txt' => '#FAF9F6',
            ],
            [
                'supplier_id' => $supplierBdg->id,
                'category_id' => $catPants->id,
                'name' => 'Dickies Double Knee Work Pants Khaki',
                'code' => 'ST-PNT-002',
                'description' => 'Celana Dickies Loose Fit Double Knee warna Khaki. Bahan twill kokoh tahan banting khas workwear Amerika. Bagian lutut tebal berlayer. Tag woven original.',
                'condition' => 'like_new',
                'agreed_price' => 315000,
                'selling_price' => 350000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'W30 L30 (Lingkar 80cm, P: 100cm)',
                'brand' => 'Dickies',
                'color' => 'Khaki Tan',
                'weight_grams' => 600,
                'status' => 'available',
                'image_filename' => 'dickies-double-knee.svg',
                'color_bg' => '#A89472',
                'color_txt' => '#1C1C1C',
            ],
            [
                'supplier_id' => $supplierSenen->id,
                'category_id' => $catCaps->id,
                'name' => 'Stussy Vintage S Crown Embroidered Snapback Cap',
                'code' => 'ST-ACC-001',
                'description' => 'Topi vintage Stussy International dengan bordir mahkota S crown iconic. Warna hitam washed dengan strap belakang adjustable. Made in USA.',
                'condition' => 'fair',
                'agreed_price' => 225000,
                'selling_price' => 250000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'Free Size / Adjustable',
                'brand' => 'Stussy',
                'color' => 'Washed Black',
                'weight_grams' => 150,
                'status' => 'available',
                'image_filename' => 'stussy-cap.svg',
                'color_bg' => '#1F1F1F',
                'color_txt' => '#FAF9F6',
            ],
            [
                'supplier_id' => $supplierMlg->id,
                'category_id' => $catJackets->id,
                'name' => 'Patagonia Retro-X Deep Pile Fleece Jacket',
                'code' => 'ST-JKT-003',
                'description' => 'Jaket fleece tebal Patagonia Classic Retro-X warna Natural Beige dengan saku dada warna Navy. Windproof barrier mesh lining hangat dan nyaman.',
                'condition' => 'like_new',
                'agreed_price' => 990000,
                'selling_price' => 1100000,
                'admin_commission_pct' => 10.00,
                'supplier_commission_pct' => 90.00,
                'size' => 'M (P: 67cm, L: 56cm)',
                'brand' => 'Patagonia',
                'color' => 'Natural / Classic Navy',
                'weight_grams' => 780,
                'status' => 'available',
                'image_filename' => 'patagonia-fleece.svg',
                'color_bg' => '#D8CEBE',
                'color_txt' => '#1C1C1C',
            ],
        ];

        foreach ($productsData as $data) {
            $filename = $data['image_filename'];
            $colorBg = $data['color_bg'];
            $colorTxt = $data['color_txt'];
            $brand = $data['brand'];
            $nameShort = substr($data['name'], 0, 32);

            unset($data['image_filename'], $data['color_bg'], $data['color_txt']);

            // Create handsome vector graphic SVG for local offline preview
            $svgContent = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
  <rect width="600" height="600" fill="{$colorBg}" />
  <rect x="30" y="30" width="540" height="540" fill="none" stroke="{$colorTxt}" stroke-width="2" stroke-opacity="0.3" rx="16" />
  <circle cx="300" cy="240" r="100" fill="{$colorTxt}" fill-opacity="0.08" />
  <path d="M 240 280 C 240 210, 360 210, 360 280 C 360 330, 240 330, 240 280 Z" fill="{$colorTxt}" fill-opacity="0.15" />
  <text x="300" y="240" font-family="'Plus Jakarta Sans', sans-serif" font-size="28" font-weight="700" fill="{$colorTxt}" text-anchor="middle" letter-spacing="4">{$brand}</text>
  <text x="300" y="275" font-family="'Inter', sans-serif" font-size="14" fill="{$colorTxt}" fill-opacity="0.7" text-anchor="middle" letter-spacing="2">AUTHENTIC THRIFT</text>
  <rect x="180" y="370" width="240" height="1" fill="{$colorTxt}" fill-opacity="0.2" />
  <text x="300" y="420" font-family="'Plus Jakarta Sans', sans-serif" font-size="18" font-weight="600" fill="{$colorTxt}" text-anchor="middle">{$nameShort}</text>
  <text x="300" y="450" font-family="'Inter', sans-serif" font-size="13" fill="{$colorTxt}" fill-opacity="0.6" text-anchor="middle">SUNVALLEY CONSIGNMENT ARCHIVE</text>
</svg>
SVG;

            $svgPath = $storageDir . '/' . $filename;
            File::put($svgPath, $svgContent);

            $product = Product::updateOrCreate(
                ['code' => $data['code']],
                $data
            );

            ProductImage::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'is_primary' => true,
                ],
                [
                    'path' => 'products/' . $filename,
                    'sort_order' => 1,
                ]
            );
        }
    }
}

