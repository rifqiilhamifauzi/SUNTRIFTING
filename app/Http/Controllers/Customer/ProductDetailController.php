<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductDetailController extends Controller
{
    public function show(string $code)
    {
        $product = Product::with(['category', 'supplier', 'images'])
            ->where('code', $code)
            ->firstOrFail();

        $relatedProducts = Product::available()
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->orWhere('brand', $product->brand);
            })
            ->take(4)
            ->get();

        return view('customer.product_detail', compact('product', 'relatedProducts'));
    }
}

