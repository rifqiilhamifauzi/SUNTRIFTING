<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn ($q) => $q->where('status', 'available')])
            ->get();

        $filters = [
            'search' => $request->input('q') ?: $request->input('search'),
            'category' => $request->input('category'),
            'condition' => $request->input('condition'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'sort' => $request->input('sort', 'latest'),
        ];

        $products = Product::available()
            ->with(['category', 'primaryImage', 'images'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        $selectedCategory = $filters['category']
            ? Category::where('slug', $filters['category'])->first()
            : null;

        return view('customer.catalog', compact('products', 'categories', 'filters', 'selectedCategory'));
    }
}

