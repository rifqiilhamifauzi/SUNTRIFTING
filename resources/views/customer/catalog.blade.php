@extends('layouts.app', ['title' => 'Katalog Thrift & Vintage — Suntrifting'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <!-- Header Title & Active Category Breadcrumb -->
    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-primary">
            {{ $selectedCategory ? $selectedCategory->name : 'Semua Katalog Thrift' }}
        </h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">
            Menampilkan {{ $products->total() }} barang preloved & vintage 1-of-1 pilihan.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filter Sidebar -->
        <aside class="lg:col-span-1" x-data="{ openFilter: false }">
            <div class="lg:hidden mb-4">
                <button @click="openFilter = !openFilter" class="w-full flex items-center justify-between bg-white border border-gray-200 px-4 py-2.5 rounded-xl text-xs font-semibold text-primary">
                    <span class="flex items-center"><i data-lucide="filter" class="w-4 h-4 mr-2 text-accent"></i> Filter & Urutkan</span>
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </button>
            </div>

            <div :class="openFilter ? 'block' : 'hidden lg:block'" class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-6 sticky top-24">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="font-heading font-bold text-sm text-primary">Filter Produk</h3>
                    <a href="{{ route('catalog.index') }}" class="text-[11px] font-semibold text-accent hover:underline">Reset</a>
                </div>

                <form action="{{ route('catalog.index') }}" method="GET" class="space-y-6">
                    <!-- Keyword Search -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ $filters['search'] }}" placeholder="Brand, nama, warna..." 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-2.5"></i>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Kategori</label>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                            <label class="flex items-center text-xs text-gray-600 hover:text-primary cursor-pointer">
                                <input type="radio" name="category" value="" {{ empty($filters['category']) ? 'checked' : '' }} class="text-accent focus:ring-accent mr-2">
                                <span>Semua Kategori</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center justify-between text-xs text-gray-600 hover:text-primary cursor-pointer">
                                    <div class="flex items-center">
                                        <input type="radio" name="category" value="{{ $cat->slug }}" {{ $filters['category'] === $cat->slug ? 'checked' : '' }} class="text-accent focus:ring-accent mr-2">
                                        <span>{{ $cat->name }}</span>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-mono">({{ $cat->products_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Condition -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Kondisi Pakaian</label>
                        <div class="space-y-1.5">
                            <label class="flex items-center text-xs text-gray-600 hover:text-primary cursor-pointer">
                                <input type="radio" name="condition" value="" {{ empty($filters['condition']) ? 'checked' : '' }} class="text-accent focus:ring-accent mr-2">
                                <span>Semua Kondisi</span>
                            </label>
                            <label class="flex items-center text-xs text-gray-600 hover:text-primary cursor-pointer">
                                <input type="radio" name="condition" value="like_new" {{ $filters['condition'] === 'like_new' ? 'checked' : '' }} class="text-accent focus:ring-accent mr-2">
                                <span>Seperti Baru (Like New)</span>
                            </label>
                            <label class="flex items-center text-xs text-gray-600 hover:text-primary cursor-pointer">
                                <input type="radio" name="condition" value="good" {{ $filters['condition'] === 'good' ? 'checked' : '' }} class="text-accent focus:ring-accent mr-2">
                                <span>Kondisi Baik (Good)</span>
                            </label>
                            <label class="flex items-center text-xs text-gray-600 hover:text-primary cursor-pointer">
                                <input type="radio" name="condition" value="fair" {{ $filters['condition'] === 'fair' ? 'checked' : '' }} class="text-accent focus:ring-accent mr-2">
                                <span>Cukup Baik (Fair)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Rentang Harga (Rp)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ $filters['min_price'] }}" placeholder="Min" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            <input type="number" name="max_price" value="{{ $filters['max_price'] }}" placeholder="Max" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        </div>
                    </div>

                    <!-- Sorting -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Urutkan</label>
                        <select name="sort" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            <option value="latest" {{ $filters['sort'] === 'latest' ? 'selected' : '' }}>Terbaru Masuk</option>
                            <option value="price_low" {{ $filters['sort'] === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ $filters['sort'] === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="oldest" {{ $filters['sort'] === 'oldest' ? 'selected' : '' }}>Item Terlama</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-2.5 rounded-xl text-xs transition shadow-sm">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </aside>

        <!-- Product Grid & Pagination -->
        <div class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="search-x" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-primary">Tidak Ada Produk yang Ditemukan</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                        Coba gunakan kata kunci lain atau reset filter untuk melihat koleksi vintage kami lainnya.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 mt-6 bg-accent hover:bg-accent-dark text-white font-semibold px-5 py-2.5 rounded-full text-xs transition">
                        <span>Reset Semua Filter</span>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-all group flex flex-col">
                            <a href="{{ route('product.show', $product->code) }}" class="relative aspect-square overflow-hidden bg-gray-50 block">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <span class="absolute top-2.5 left-2.5 bg-white/90 backdrop-blur-md text-primary text-[9px] font-bold px-2 py-0.5 rounded-full uppercase">
                                    {{ $product->condition == 'like_new' ? 'Like New' : ($product->condition == 'good' ? 'Good' : 'Fair') }}
                                </span>
                                @if($product->size)
                                    <span class="absolute bottom-2.5 right-2.5 bg-black/75 text-white text-[9px] font-semibold px-2 py-0.5 rounded">
                                        {{ $product->size }}
                                    </span>
                                @endif
                            </a>

                            <div class="p-3.5 flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="text-[9px] font-bold text-accent uppercase tracking-wider">{{ $product->brand ?: 'Vintage' }}</span>
                                    <h3 class="font-heading font-semibold text-xs sm:text-sm text-primary hover:text-accent line-clamp-1 mt-0.5">
                                        <a href="{{ route('product.show', $product->code) }}">{{ $product->name }}</a>
                                    </h3>
                                    <p class="text-[10px] text-gray-400 mt-0.5 font-mono">Kode: {{ $product->code }}</p>
                                </div>

                                <div class="mt-3 pt-2.5 border-t border-gray-100 flex items-center justify-between">
                                    <span class="font-heading font-bold text-xs sm:text-sm text-primary">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-accent hover:text-white text-gray-700 flex items-center justify-center transition" title="Tambah ke Keranjang">
                                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

