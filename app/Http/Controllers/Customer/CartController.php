<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart(Auth::user());
        $cart->load(['items.product.primaryImage', 'items.product.category']);

        return view('customer.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        try {
            $this->cartService->addItem(Auth::user(), (int) $request->product_id);

            return redirect()->route('cart.index')
                ->with('success', 'Produk berhasil ditambahkan ke keranjang belanja.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request, int $productId)
    {
        $this->cartService->removeItem(Auth::user(), $productId);

        return redirect()->route('cart.index')
            ->with('success', 'Produk dihapus dari keranjang.');
    }
}

