<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProfitShare;
use App\Models\Supplier;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\ProfitShareService;
use App\Services\SupplierPayoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThriftMarketplaceTest extends TestCase
{
    public function test_public_pages_are_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/catalog');
        $response->assertStatus(200);

        $response = $this->get('/offline');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        // 1. Test POST credentials to /admin/login
        $response = $this->post('/admin/login', [
            'email' => 'admin@suntrifting.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect('/admin');
        $this->assertAuthenticated();

        // 2. Access dashboard
        $dashResponse = $this->get('/admin');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Ringkasan & Metrik Bisnis Konsinyasi');
    }

    public function test_customer_can_add_to_cart_and_checkout(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::where('status', 'available')->first();

        $cartService = app(CartService::class);
        $orderService = app(OrderService::class);

        // Add to cart
        $cartItem = $cartService->addItem($customer, $product->id);
        $this->assertEquals($product->id, $cartItem->product_id);

        // Checkout
        $order = $orderService->checkout($customer, [
            'recipient_name' => 'Budi Customer',
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Kebon Jeruk No. 10',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Barat',
            'postal_code' => '11530',
            'payment_method' => 'manual_bank_transfer',
        ]);

        $this->assertNotNull($order);
        $this->assertEquals('pending_payment', $order->order_status);
        $this->assertEquals('unpaid', $order->payment_status);

        // Product must be reserved
        $product->refresh();
        $this->assertEquals('reserved', $product->status);
    }

    public function test_payment_verification_triggers_accurate_profit_sharing(): void
    {
        Storage::fake('public');

        $customer = User::where('role', 'customer')->first();
        $admin = User::where('role', 'admin')->first();

        $supplier = Supplier::first();
        $category = Category::first();

        // Create a unique thrift product: Rp200.000 with 10% admin / 90% supplier
        $product = Product::create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'name' => 'Test Thrift Vintage Fleece',
            'code' => 'TEST-PROD-' . uniqid(),
            'condition' => 'like_new',
            'agreed_price' => 180000,
            'selling_price' => 200000,
            'admin_commission_pct' => 10.00,
            'supplier_commission_pct' => 90.00,
            'status' => 'available',
        ]);

        $cartService = app(CartService::class);
        $orderService = app(OrderService::class);
        $paymentService = app(PaymentService::class);

        // Add to cart & checkout
        $cartService->addItem($customer, $product->id);
        $order = $orderService->checkout($customer, [
            'recipient_name' => 'Test Pembeli',
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Sudirman 1',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'postal_code' => '12190',
            'payment_method' => 'manual_bank_transfer',
        ]);

        // Customer uploads payment proof
        $file = UploadedFile::fake()->image('transfer.jpg');
        $payment = $paymentService->uploadManualProof($order, $file, [
            'bank_name' => 'BCA',
            'account_name' => 'Test Pembeli',
            'account_number' => '1234567890',
        ], $customer);

        $order->refresh();
        $this->assertEquals('waiting_verification', $order->order_status);
        $this->assertEquals('pending_verification', $order->payment_status);

        // Admin verifies payment
        $paymentService->verifyPayment($payment, $admin, 'Valid transfer received');

        $order->refresh();
        $this->assertEquals('processing', $order->order_status);
        $this->assertEquals('paid', $order->payment_status);

        // Product marked as sold
        $product->refresh();
        $this->assertEquals('sold', $product->status);

        // Profit share generated
        $share = ProfitShare::where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->first();

        $this->assertNotNull($share);
        $this->assertEquals(200000, (float)$share->selling_price);
        $this->assertEquals(20000, (float)$share->admin_amount); // 10%
        $this->assertEquals(180000, (float)$share->supplier_amount); // 90%
        $this->assertEquals('completed', $share->status);
        $this->assertEquals('unpaid', $share->payout_status);
    }

    public function test_pwa_manifest_is_valid_json(): void
    {
        $manifestPath = public_path('manifest.webmanifest');
        $this->assertFileExists($manifestPath);

        $json = json_decode(file_get_contents($manifestPath), true);
        $this->assertIsArray($json);
        $this->assertEquals('standalone', $json['display']);
        $this->assertNotEmpty($json['icons']);
    }

    public function test_supplier_payout_rejects_amount_greater_than_balance(): void
    {
        $supplier = Supplier::first();
        $admin = User::where('role', 'admin')->first();
        $payoutService = app(SupplierPayoutService::class);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/melebihi saldo/');

        // Attempting to withdraw 999,999,999 which exceeds any supplier balance
        $payoutService->createPayout($supplier, 999999999, [
            'payment_method' => 'bank_transfer',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Supplier',
        ], $admin);
    }

    public function test_cannot_add_sold_product_to_cart(): void
    {
        $customer = User::where('role', 'customer')->first();
        $soldProduct = Product::where('status', 'sold')->first();

        if (!$soldProduct) {
            $soldProduct = Product::create([
                'supplier_id' => Supplier::first()->id,
                'category_id' => Category::first()->id,
                'name' => 'Sold Out Thrift Tee',
                'code' => 'SOLD-' . uniqid(),
                'condition' => 'good',
                'agreed_price' => 50000,
                'selling_price' => 75000,
                'admin_commission_pct' => 10,
                'supplier_commission_pct' => 90,
                'status' => 'sold',
            ]);
        }

        $cartService = app(CartService::class);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/tidak tersedia/');

        $cartService->addItem($customer, $soldProduct->id);
    }

    public function test_admin_can_export_financial_reports_csv_and_pdf(): void
    {
        $admin = User::where('role', 'admin')->first();

        // CSV export
        $csvResponse = $this->actingAs($admin)->get('/admin/reports/export/csv');
        $csvResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', $csvResponse->headers->get('content-type'));

        // PDF export
        $pdfResponse = $this->actingAs($admin)->get('/admin/reports/export/pdf');
        $pdfResponse->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $pdfResponse->headers->get('content-type'));
    }

    public function test_category_parent_and_subcategory_relationship(): void
    {
        $parent = Category::whereNull('parent_id')->has('subcategories')->first();
        $this->assertNotNull($parent, 'Parent category should exist');
        $this->assertNotEmpty($parent->subcategories, 'Parent should have subcategories');

        $sub = $parent->subcategories->first();
        $this->assertEquals($parent->id, $sub->parent_id);
        $this->assertEquals($parent->name, $sub->parent->name);
    }

    public function test_customer_can_toggle_wishlist_and_view_wishlist_page(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::where('status', 'available')->first();

        // 1. Toggle to add (via JSON)
        $response = $this->actingAs($customer)->postJson('/wishlist/toggle/' . $product->id);
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'added' => true]);
        $this->assertTrue($product->isWishlistedBy($customer));

        // 2. View wishlist page
        $pageResponse = $this->actingAs($customer)->get('/wishlist');
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee($product->name);

        // 3. Toggle to remove
        $removeResponse = $this->actingAs($customer)->postJson('/wishlist/toggle/' . $product->id);
        $removeResponse->assertStatus(200);
        $removeResponse->assertJson(['success' => true, 'added' => false]);
        $this->assertFalse($product->isWishlistedBy($customer));
    }

    public function test_catalog_supports_parent_category_condition_and_gender_filtering(): void
    {
        $parentCategory = Category::whereNull('parent_id')->first();

        // Filter by category
        $response = $this->get('/catalog?category=' . $parentCategory->slug);
        $response->assertStatus(200);

        // Filter by condition
        $condResponse = $this->get('/catalog?condition=brand_new');
        $condResponse->assertStatus(200);

        // Filter by gender
        $genderResponse = $this->get('/catalog?gender=women');
        $genderResponse->assertStatus(200);
    }

    public function test_about_page_is_accessible(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('SUN-THRIFTING');
        $response->assertSee('Titip Jual');
    }

    public function test_admin_orders_index_only_contains_verified_paid_orders(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('Pesanan Terverifikasi');

        // Check orders passed to view all have payment_status 'paid'
        $ordersInView = $response->viewData('orders');
        foreach ($ordersInView as $ord) {
            $this->assertEquals('paid', $ord->payment_status);
        }
    }

    public function test_admin_payments_index_supports_categorized_status_filters(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Needs verification
        $resNeeds = $this->actingAs($admin)->get('/admin/payments?status=needs_verification');
        $resNeeds->assertStatus(200);
        $resNeeds->assertSee('Perlu Dicek');

        // 2. Unpaid
        $resUnpaid = $this->actingAs($admin)->get('/admin/payments?status=unpaid');
        $resUnpaid->assertStatus(200);
        $resUnpaid->assertSee('Belum Bayar');

        // 3. Rejected
        $resRejected = $this->actingAs($admin)->get('/admin/payments?status=rejected');
        $resRejected->assertStatus(200);
        $resRejected->assertSee('Ditolak');
    }

    public function test_verifying_payment_moves_order_to_customer_orders_list(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Find or create a pending payment
        $payment = Payment::where('status', 'pending')->first();
        if (!$payment) {
            $customer = User::where('role', 'customer')->first();
            $product = Product::where('status', 'available')->first();
            $cartService = app(\App\Services\CartService::class);
            $orderService = app(\App\Services\OrderService::class);

            $cartService->addItem($customer, $product->id);
            $order = $orderService->checkout($customer, [
                'recipient_name' => 'Test Mover',
                'recipient_phone' => '081234567890',
                'shipping_address' => 'Jl. Test No. 1',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta',
                'postal_code' => '10110',
                'payment_method' => 'manual_bank_transfer',
            ]);
            $payment = $order->latestPayment;
        }

        $order = $payment->order;

        // Before verification: order is NOT in /admin/orders
        $resBefore = $this->actingAs($admin)->get('/admin/orders');
        $ordersBefore = $resBefore->viewData('orders');
        $this->assertFalse($ordersBefore->contains('id', $order->id));

        // Admin verifies payment
        $resVerify = $this->actingAs($admin)->post('/admin/payments/' . $payment->id . '/verify');
        $resVerify->assertRedirect('/admin/orders');

        // After verification: order IS in /admin/orders
        $resAfter = $this->actingAs($admin)->get('/admin/orders');
        $ordersAfter = $resAfter->viewData('orders');
        $this->assertTrue($ordersAfter->contains('id', $order->id));
    }
}
