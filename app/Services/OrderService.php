<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Shipment;
use App\Models\ShippingRate;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected CartService $cartService;
    protected ProfitShareService $profitShareService;

    public function __construct(CartService $cartService, ProfitShareService $profitShareService)
    {
        $this->cartService = $cartService;
        $this->profitShareService = $profitShareService;
    }

    /**
     * Checkout process with pessimistic locking to prevent race conditions.
     */
    public function checkout(User $user, array $checkoutData): Order
    {
        return DB::transaction(function () use ($user, $checkoutData) {
            $cart = $this->cartService->getOrCreateCart($user);
            $cartItems = $cart->items()->with('product')->get();

            if ($cartItems->isEmpty()) {
                throw new Exception('Keranjang belanja Anda masih kosong.');
            }

            $productIds = $cartItems->pluck('product_id')->all();

            // PESSIMISTIC LOCK: Lock rows in products table
            $lockedProducts = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Verify each product is still 'available'
            foreach ($productIds as $pId) {
                $prod = $lockedProducts->get($pId);
                if (!$prod || $prod->status !== 'available') {
                    throw new Exception("Maaf, produk '{$prod?->name}' sudah tidak tersedia atau dibeli oleh pengguna lain.");
                }
            }

            // Determine shipping cost
            $shippingCost = 0.00;
            $province = $checkoutData['province'];
            $city = $checkoutData['city'] ?? null;

            $shippingRate = ShippingRate::where('province', $province)
                ->where('is_active', true)
                ->first();

            if ($shippingRate) {
                $shippingCost = (float) $shippingRate->cost;
            } else {
                // Default fallback shipping
                $shippingCost = (float) (ShippingRate::where('is_active', true)->first()?->cost ?? 20000);
            }

            // Calculate subtotal and commission snapshots
            $subtotal = 0.00;
            $itemsData = [];

            // Get global commission settings as fallback
            $defaultAdminPct = (float) Setting::get('admin_commission_pct', 10.00);
            $defaultSupplierPct = (float) Setting::get('supplier_commission_pct', 90.00);

            foreach ($lockedProducts as $product) {
                $price = (float) $product->selling_price;
                $subtotal += $price;

                $adminPct = (float) ($product->admin_commission_pct ?: $defaultAdminPct);
                $supplierPct = (float) ($product->supplier_commission_pct ?: $defaultSupplierPct);

                $adminAmount = round($price * ($adminPct / 100), 2);
                $supplierAmount = round($price - $adminAmount, 2);

                $itemsData[] = [
                    'product_id' => $product->id,
                    'supplier_id' => $product->supplier_id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'product_price' => $price,
                    'admin_commission_pct' => $adminPct,
                    'supplier_commission_pct' => $supplierPct,
                    'admin_amount' => $adminAmount,
                    'supplier_amount' => $supplierAmount,
                ];

                // Mark product as 'reserved' immediately
                $product->update(['status' => 'reserved']);
            }

            $totalAmount = $subtotal + $shippingCost;
            $orderNumber = 'SUN-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'recipient_name' => $checkoutData['recipient_name'],
                'recipient_phone' => $checkoutData['recipient_phone'],
                'shipping_address' => $checkoutData['shipping_address'],
                'city' => $checkoutData['city'],
                'province' => $checkoutData['province'],
                'postal_code' => $checkoutData['postal_code'],
                'notes' => $checkoutData['notes'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'order_status' => 'pending_payment',
                'payment_status' => 'unpaid',
                'payment_method' => $checkoutData['payment_method'] ?? 'manual_bank_transfer',
            ]);

            // Create OrderItems
            foreach ($itemsData as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            // Create Initial Payment Record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $order->payment_method,
                'amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create Initial Shipment Record
            Shipment::create([
                'order_id' => $order->id,
                'courier' => $checkoutData['courier'] ?? 'JNE Regular',
                'shipping_cost' => $shippingCost,
                'status' => 'pending',
            ]);

            // Record status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => null,
                'new_status' => 'pending_payment',
                'notes' => 'Pesanan berhasil dibuat, menunggu pembayaran dari pembeli.',
                'changed_by' => $user->id,
            ]);

            // Clear Cart
            $this->cartService->clearCart($user);

            return $order;
        });
    }

    /**
     * Cancel an order and release reserved products.
     */
    public function cancelOrder(Order $order, string $reason, ?User $actor = null): Order
    {
        return DB::transaction(function () use ($order, $reason, $actor) {
            $oldStatus = $order->order_status;

            // Release products back to 'available'
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->update(['status' => 'available']);
                }
            }

            $order->update([
                'order_status' => 'cancelled',
            ]);

            // Cancel profit share if any
            $this->profitShareService->cancelForOrder($order);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'notes' => 'Pesanan dibatalkan: ' . $reason,
                'changed_by' => $actor?->id,
            ]);

            return $order;
        });
    }
}

