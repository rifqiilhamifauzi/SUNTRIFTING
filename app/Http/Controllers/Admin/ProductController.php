<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $categoryId = $request->input('category_id');
        $supplierId = $request->input('supplier_id');

        $products = Product::with(['category', 'supplier', 'primaryImage'])
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($supplierId, fn ($q) => $q->where('supplier_id', $supplierId))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();
        $suppliers = Supplier::where('status', 'active')->get();

        return view('admin.products.index', compact('products', 'categories', 'suppliers', 'search', 'status', 'categoryId', 'supplierId'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::where('status', 'active')->get();
        $defaultAdminPct = (float) Setting::get('admin_commission_pct', 10.00);
        $defaultSupplierPct = (float) Setting::get('supplier_commission_pct', 90.00);

        return view('admin.products.create', compact('categories', 'suppliers', 'defaultAdminPct', 'defaultSupplierPct'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:products,code'],
            'description' => ['nullable', 'string'],
            'condition' => ['required', 'in:like_new,good,fair'],
            'agreed_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'admin_commission_pct' => ['required', 'numeric', 'between:0,100'],
            'supplier_commission_pct' => ['required', 'numeric', 'between:0,100'],
            'size' => ['nullable', 'string', 'max:50'],
            'brand' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:50'],
            'weight_grams' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,available,reserved,sold,archived'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = 'ST-' . strtoupper(Str::random(6));
        }

        DB::transaction(function () use ($validated, $request) {
            $product = Product::create($validated);

            if ($request->hasFile('images')) {
                $isPrimary = true;
                $sort = 1;
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'sort_order' => $sort++,
                        'is_primary' => $isPrimary,
                    ]);
                    $isPrimary = false;
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk thrift baru berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $product = Product::with(['images', 'category', 'supplier'])->findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::all();

        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:products,code,' . $id],
            'description' => ['nullable', 'string'],
            'condition' => ['required', 'in:like_new,good,fair'],
            'agreed_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'admin_commission_pct' => ['required', 'numeric', 'between:0,100'],
            'supplier_commission_pct' => ['required', 'numeric', 'between:0,100'],
            'size' => ['nullable', 'string', 'max:50'],
            'brand' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:50'],
            'weight_grams' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,available,reserved,sold,archived'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($product, $validated, $request) {
            $product->update($validated);

            if ($request->hasFile('images')) {
                $sort = $product->images()->max('sort_order') + 1;
                $hasPrimary = $product->images()->where('is_primary', true)->exists();

                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'sort_order' => $sort++,
                        'is_primary' => !$hasPrimary,
                    ]);
                    $hasPrimary = true;
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function deleteImage(int $imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        $productId = $image->product_id;

        Storage::disk('public')->delete($image->path);
        $image->delete();

        // Ensure at least one primary image remains
        $primary = ProductImage::where('product_id', $productId)->where('is_primary', true)->first();
        if (!$primary) {
            $first = ProductImage::where('product_id', $productId)->first();
            if ($first) {
                $first->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Foto produk berhasil dihapus.');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        // If product already sold or in order, soft delete / archive
        $hasOrders = $product->status === 'sold' || DB::table('order_items')->where('product_id', $id)->exists();

        if ($hasOrders) {
            $product->update(['status' => 'archived']);
            $product->delete(); // Soft delete

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk telah memiliki riwayat pesanan sehingga diarsipkan dengan aman.');
        }

        $product->forceDelete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}

