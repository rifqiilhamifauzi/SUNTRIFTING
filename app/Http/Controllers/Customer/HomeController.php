<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn ($q) => $q->where('status', 'available')])
            ->orderBy('sort_order')
            ->get();

        $latestProducts = Product::available()
            ->with(['category', 'primaryImage', 'images'])
            ->latest()
            ->take(8)
            ->get();

        $curatedPicks = Product::available()
            ->with(['category', 'primaryImage', 'images'])
            ->whereIn('condition', ['like_new', 'good'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        $stats = [
            'total_curated' => Product::count(),
            'active_suppliers' => Supplier::where('status', 'active')->count(),
            'happy_customers' => 1250, // social proof
        ];

        return view('customer.home', compact('categories', 'latestProducts', 'curatedPicks', 'stats'));
    }
}

