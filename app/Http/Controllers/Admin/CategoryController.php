<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $parents = Category::parents()
            ->with(['subcategories' => fn ($q) => $q->withCount('products')])
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        $allCategories = Category::orderBy('name')->get();

        return view('admin.categories.index', compact('parents', 'allCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $baseSlug = Str::slug($validated['name']);
        if (!empty($validated['parent_id'])) {
            $parent = Category::find($validated['parent_id']);
            $slug = Str::slug($parent->slug . '-' . $validated['name']);
        } else {
            $slug = $baseSlug;
        }

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);

        return back()->with('success', 'Kategori / Subkategori baru berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id', 'different:id'],
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!empty($validated['parent_id']) && $validated['parent_id'] == $id) {
            return back()->with('error', 'Kategori tidak dapat menjadi subkategori bagi dirinya sendiri.');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);

        // Check if category has products directly
        if ($category->products()->exists() || $category->subcategoryProducts()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }

        // Check if subcategories have products
        foreach ($category->subcategories as $sub) {
            if ($sub->products()->exists() || $sub->subcategoryProducts()->exists()) {
                return back()->with('error', "Kategori tidak dapat dihapus karena subkategori {$sub->name} masih memiliki produk terkait.");
            }
        }

        // Delete child subcategories first
        $category->subcategories()->delete();
        $category->delete();

        return back()->with('success', 'Kategori dan subkategori terkait berhasil dihapus.');
    }
}
