<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Setting;
use App\Models\ShippingRate;
use App\Services\CartService;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $user = Auth::user();
        $cart = $this->cartService->getOrCreateCart($user);
        $cart->load(['items.product.primaryImage']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $addresses = $user->addresses()->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?: $addresses->first();

        $shippingRates = ShippingRate::where('is_active', true)->get();

        $bankBca = [
            'number' => Setting::get('bank_bca_number', '8271-9283-71'),
            'holder' => Setting::get('bank_bca_holder', 'PT Suntrifting Karya Mandiri'),
        ];
        $bankMandiri = [
            'number' => Setting::get('bank_mandiri_number', '137-00-1928371-0'),
            'holder' => Setting::get('bank_mandiri_holder', 'PT Suntrifting Karya Mandiri'),
        ];

        return view('customer.checkout', compact('cart', 'addresses', 'defaultAddress', 'shippingRates', 'bankBca', 'bankMandiri'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'string', 'max:10'],
            'payment_method' => ['required', 'in:manual_bank_transfer,midtrans'],
            'courier' => ['nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
            'save_address' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();

        // Optionally save address
        if ($request->boolean('save_address')) {
            Address::create([
                'user_id' => $user->id,
                'label' => 'Alamat Pengiriman',
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['recipient_phone'],
                'address_line' => $validated['shipping_address'],
                'province' => $validated['province'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'is_default' => $user->addresses()->count() === 0,
            ]);
        }

        try {
            $order = $this->orderService->checkout($user, $validated);

            return redirect()->route('orders.show', $order->order_number)
                ->with('success', 'Pesanan Anda berhasil dibuat! Silakan selesaikan pembayaran.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}

