<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = $user->wishlistProducts()
            ->with(['category', 'subcategory', 'supplier', 'primaryImage'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('customer.wishlist', compact('wishlistItems'));
    }

    public function toggle(Request $request, int $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
            $message = "Produk {$product->name} dihapus dari wishlist.";
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $added = true;
            $message = "Produk {$product->name} ditambahkan ke wishlist.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'count' => $user->wishlists()->count(),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}

