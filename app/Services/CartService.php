<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Exception;

class CartService
{
    public function getOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, int $productId): CartItem
    {
        $product = Product::findOrFail($productId);

        if ($product->status !== 'available') {
            throw new Exception('Maaf, produk ini sudah tidak tersedia atau sedang dipesan orang lain.');
        }

        $cart = $this->getOrCreateCart($user);

        // Thrift item is unique (stock=1), check if already in user's cart
        $existing = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return $existing;
        }

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
        ]);
    }

    public function removeItem(User $user, int $productId): bool
    {
        $cart = $this->getOrCreateCart($user);

        return (bool) CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->delete();
    }

    public function clearCart(User $user): void
    {
        $cart = $this->getOrCreateCart($user);
        CartItem::where('cart_id', $cart->id)->delete();
    }
}

