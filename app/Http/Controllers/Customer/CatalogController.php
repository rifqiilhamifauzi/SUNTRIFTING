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
        $categories = Category::parents()
            ->active()
            ->with(['subcategories' => fn ($q) => $q->active()])
            ->withCount(['products' => fn ($q) => $q->where('status', 'available')])
            ->orderBy('sort_order')
            ->get();

        $filters = [
            'search' => $request->input('q') ?: $request->input('search'),
            'category' => $request->input('category'),
            'subcategory' => $request->input('subcategory'),
            'condition' => $request->input('condition'),
            'gender' => $request->input('gender'),
            'size' => $request->input('size'),
            'color' => $request->input('color'),
            'brand' => $request->input('brand'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'sort' => $request->input('sort', 'latest'),
        ];

        $products = Product::available()
            ->with(['category', 'subcategory', 'supplier', 'primaryImage', 'images'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        $selectedCategory = $filters['category']
            ? Category::where('slug', $filters['category'])->first()
            : null;

        $selectedSubcategory = $filters['subcategory']
            ? Category::where('slug', $filters['subcategory'])->first()
            : null;

        // Distinct attributes for filter sidebar
        $availableBrands = Product::available()->whereNotNull('brand')->where('brand', '!=', '')->distinct()->pluck('brand')->take(15);
        $availableSizes = Product::available()->whereNotNull('size')->where('size', '!=', '')->distinct()->pluck('size')->take(15);

        return view('customer.catalog', compact(
            'products',
            'categories',
            'filters',
            'selectedCategory',
            'selectedSubcategory',
            'availableBrands',
            'availableSizes'
        ));
    }
}
