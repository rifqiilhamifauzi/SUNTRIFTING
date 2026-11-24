@extends('layouts.app', ['title' => ($selectedCategory ? $selectedCategory->name : 'Katalog Busana & Perlengkapan Muslim') . ' — SUN-THRIFTING'])

@section('content')
<style>[x-cloak]{display:none !important;}</style>

@php
    $hasActiveFilters = !empty($filters['search']) || !empty($filters['category']) || !empty($filters['subcategory']) || !empty($filters['condition']) || !empty($filters['gender']) || !empty($filters['brand']) || !empty($filters['min_price']) || !empty($filters['max_price']);

    // Jumlah filter aktif untuk badge di ikon filter (urutan tidak dihitung)
    $activeCount = collect([
        $filters['search'] ?? null,
        $filters['category'] ?? null,
        $filters['subcategory'] ?? null,
        $filters['condition'] ?? null,
        $filters['gender'] ?? null,
        $filters['brand'] ?? null,
        $filters['min_price'] ?? null,
        $filters['max_price'] ?? null,
    ])->filter()->count();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12"
     x-data="{ filterOpen: false }"
     x-effect="document.body.classList.toggle('overflow-hidden', filterOpen)"
     @keydown.escape.window="filterOpen = false">

    <!-- Breadcrumb & Page Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-xs text-muted-gray mb-1.5">
            <a href="{{ route('home') }}" class="hover:text-primary transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-primary transition">Katalog</a>
            @if($selectedCategory)
                <span>/</span>
                <span class="text-primary font-semibold">{{ $selectedCategory->name }}</span>
            @endif
            @if($selectedSubcategory)
                <span>/</span>
                <span class="text-secondary font-semibold">{{ $selectedSubcategory->name }}</span>
            @endif
        </div>
        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-charcoal">
            {{ $selectedSubcategory ? $selectedSubcategory->name : ($selectedCategory ? $selectedCategory->name : 'Jelajahi Produk Muslim Thrift') }}
        </h1>
        <p class="text-xs sm:text-sm text-muted-gray mt-1">
            Menampilkan {{ $products->total() }} barang muslim preloved & baru berkualitas dari berbagai mitra penjual.
        </p>
    </div>

    <!-- Active Filter Badges Bar -->
    @if($hasActiveFilters)
        <div class="flex flex-wrap items-center gap-2 mb-6 p-3 bg-white rounded-xl border border-warm-beige/80 text-xs shadow-sm">
            <span class="font-bold text-muted-gray mr-1">Filter Aktif:</span>
            @if($filters['search'])
                <span class="inline-flex items-center bg-cream text-charcoal px-2.5 py-1 rounded-full border border-warm-beige">
                    Kata Kunci: "{{ $filters['search'] }}"
                    <a href="{{ request()->fullUrlWithQuery(['q' => null, 'search' => null]) }}" class="ml-1 text-muted-gray hover:text-rose-500">&times;</a>
                </span>
            @endif
            @if($selectedCategory)
                <span class="inline-flex items-center bg-cream text-charcoal px-2.5 py-1 rounded-full border border-warm-beige">
                    Kategori: {{ $selectedCategory->name }}
                    <a href="{{ request()->fullUrlWithQuery(['category' => null, 'subcategory' => null]) }}" class="ml-1 text-muted-gray hover:text-rose-500">&times;</a>
                </span>
            @endif
            @if($selectedSubcategory)
                <span class="inline-flex items-center bg-cream text-charcoal px-2.5 py-1 rounded-full border border-warm-beige">
                    Sub: {{ $selectedSubcategory->name }}
                    <a href="{{ request()->fullUrlWithQuery(['subcategory' => null]) }}" class="ml-1 text-muted-gray hover:text-rose-500">&times;</a>
                </span>
            @endif
            @if($filters['condition'])
                <span class="inline-flex items-center bg-cream text-charcoal px-2.5 py-1 rounded-full border border-warm-beige">
                    Kondisi: {{ ucfirst(str_replace('_', ' ', $filters['condition'])) }}
                    <a href="{{ request()->fullUrlWithQuery(['condition' => null]) }}" class="ml-1 text-muted-gray hover:text-rose-500">&times;</a>
                </span>
            @endif
            @if($filters['gender'])
                <span class="inline-flex items-center bg-cream text-charcoal px-2.5 py-1 rounded-full border border-warm-beige">
                    Gender: {{ ucfirst($filters['gender']) }}
                    <a href="{{ request()->fullUrlWithQuery(['gender' => null]) }}" class="ml-1 text-muted-gray hover:text-rose-500">&times;</a>
                </span>
            @endif
            @if($filters['brand'])
                <span class="inline-flex items-center bg-cream text-charcoal px-2.5 py-1 rounded-full border border-warm-beige">
                    Brand: {{ $filters['brand'] }}
                    <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="ml-1 text-muted-gray hover:text-rose-500">&times;</a>
                </span>
            @endif
            <a href="{{ route('catalog.index') }}" class="text-rose-600 hover:underline font-semibold ml-auto text-xs">Reset Semua</a>
        </div>
    @endif

    <!-- Toolbar: jumlah produk + urutan + ikon filter -->
    <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-warm-beige">
        <span class="text-xs text-muted-gray">
            Menampilkan <strong>{{ $products->count() }}</strong> dari <strong>{{ $products->total() }}</strong> produk
        </span>

        <div class="flex items-center gap-2">
            <span class="hidden sm:inline text-xs font-semibold text-charcoal">Urutkan:</span>
            <form action="{{ route('catalog.index') }}" method="GET">
                @foreach(request()->except(['sort', 'page']) as $k => $v)
                    @if(!is_array($v))
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endif
                @endforeach
                <select name="sort" onchange="this.form.submit()" class="bg-white border border-warm-beige rounded-xl px-3 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary shadow-sm">
                    <option value="latest" {{ $filters['sort'] === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_low" {{ $filters['sort'] === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ $filters['sort'] === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="popular" {{ $filters['sort'] === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                </select>
            </form>

            <!-- Tombol ikon filter -->
            <button type="button" @click="filterOpen = true"
                    class="relative shrink-0 w-10 h-10 rounded-xl bg-white hover:bg-primary hover:text-white text-charcoal border border-warm-beige shadow-sm flex items-center justify-center transition"
                    title="Filter Produk" aria-label="Buka filter produk">
                <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                @if($activeCount > 0)
                    <span class="absolute -top-1.5 -right-1.5 min-w-5 h-5 px-1 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white">
                        {{ $activeCount }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    <!-- Product Grid Content (lebar penuh) -->
    <main>
        @if($products->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-12 text-center border border-warm-beige my-8 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-cream text-secondary flex items-center justify-center mx-auto mb-4 border border-warm-beige">
                    <i data-lucide="search-x" class="w-8 h-8"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-charcoal">Tidak Ada Produk yang Sesuai</h3>
                <p class="text-xs text-muted-gray mt-1.5 max-w-sm mx-auto leading-relaxed">
                    Kami tidak menemukan barang yang cocok dengan kriteria filter atau pencarian Anda. Coba atur ulang filter atau kata kunci lainnya.
                </p>
                <div class="mt-6">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-primary hover:bg-primary-dark text-white text-xs font-semibold px-6 py-2.5 rounded-full transition shadow-sm">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset Semua Filter</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Responsive Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl overflow-hidden border border-warm-beige shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                        <div>
                            <!-- Image & Badges -->
                            <a href="{{ route('product.show', $product->code) }}" class="relative aspect-square overflow-hidden bg-cream block">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                <!-- Condition badge -->
                                <span class="absolute top-2.5 left-2.5 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $product->condition_badge_class }}">
                                    {{ $product->condition_label }}
                                </span>

                                <!-- Wishlist Heart Button -->
                                @auth
                                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-2.5 right-2.5 z-10">
                                        @csrf
                                        <button type="submit" class="w-7 h-7 rounded-full bg-white/90 backdrop-blur-md shadow-sm flex items-center justify-center hover:bg-white transition text-charcoal" title="Wishlist">
                                            <i data-lucide="heart" class="w-3.5 h-3.5 {{ $product->isWishlistedBy(auth()->user()) ? 'fill-primary text-primary' : 'text-muted-gray' }}"></i>
                                        </button>
                                    </form>
                                @endauth

                                @if($product->size)
                                    <span class="absolute bottom-2.5 right-2.5 bg-charcoal/80 text-white text-[9px] font-semibold px-2 py-0.5 rounded">
                                        {{ $product->size }}
                                    </span>
                                @endif
                            </a>

                            <!-- Product Info -->
                            <div class="p-3.5">
                                <div class="flex items-center justify-between text-[9px] font-bold text-secondary uppercase tracking-wider">
                                    <span>{{ $product->brand ?: 'Curated Muslim' }}</span>
                                    <span class="text-muted-gray font-normal">{{ $product->location }}</span>
                                </div>
                                <h3 class="font-heading font-semibold text-xs sm:text-sm text-charcoal hover:text-primary line-clamp-1 mt-1">
                                    <a href="{{ route('product.show', $product->code) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-[11px] text-muted-gray line-clamp-1 mt-0.5">Toko: {{ $product->supplier ? $product->supplier->name : 'Mitra Terverifikasi' }}</p>
                            </div>
                        </div>

                        <!-- Bottom Action / Price Bar -->
                        <div class="p-3.5 pt-0">
                            <div class="pt-2.5 border-t border-warm-beige flex items-center justify-between">
                                <div>
                                    <span class="font-heading font-bold text-xs sm:text-sm text-primary">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                    @if($product->original_price && $product->original_price > $product->selling_price)
                                        <span class="text-[10px] text-muted-gray line-through ml-1">Rp{{ number_format($product->original_price, 0, ',', '.') }}</span>
                                    @endif
                                </div>

                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-cream hover:bg-primary hover:text-white text-charcoal flex items-center justify-center transition border border-warm-beige" title="Tambah ke Keranjang">
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
    </main>

    <!-- Modal Filter Produk -->
    <div x-show="filterOpen" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center" role="dialog" aria-modal="true" aria-labelledby="filter-title">
        <!-- Backdrop -->
        <div x-show="filterOpen" x-transition.opacity @click="filterOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Panel -->
        <div x-show="filterOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-6 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-6 sm:scale-95"
             class="relative w-full sm:max-w-lg bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl max-h-[90vh] flex flex-col">

            <form action="{{ route('catalog.index') }}" method="GET" class="flex flex-col min-h-0"
                  x-data="{ cat: {{ json_encode($filters['category'] ?? '') }} }">

                {{-- Pertahankan urutan yang sedang dipilih --}}
                @if(($filters['sort'] ?? 'latest') !== 'latest')
                    <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                @endif

                <!-- Header modal -->
                <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-warm-beige">
                    <h3 id="filter-title" class="font-heading font-bold text-base text-charcoal flex items-center space-x-2">
                        <i data-lucide="sliders" class="w-4 h-4 text-secondary"></i>
                        <span>Filter Produk</span>
                    </h3>
                    <button type="button" @click="filterOpen = false" class="w-8 h-8 rounded-full hover:bg-cream text-muted-gray flex items-center justify-center" aria-label="Tutup">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Isi modal (scrollable) -->
                <div class="px-6 py-5 space-y-6 overflow-y-auto">
                    <!-- Search input -->
                    <div>
                        <label class="block text-xs font-bold text-charcoal mb-2">Pencarian Kata Kunci</label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ $filters['search'] }}" placeholder="Nama, brand, bahan..."
                                   class="w-full bg-cream/50 border border-warm-beige rounded-xl pl-9 pr-3 py-2 text-xs text-charcoal focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-muted-gray absolute left-3 top-2.5"></i>
                        </div>
                    </div>

                    <!-- Category Hierarchy Filter -->
                    <div>
                        <label class="block text-xs font-bold text-charcoal mb-2">Kategori Utama</label>
                        <div class="space-y-1 max-h-56 overflow-y-auto pr-1">
                            <label class="flex items-center text-xs text-charcoal hover:text-primary cursor-pointer py-0.5">
                                <input type="radio" name="category" value="" x-model="cat" class="text-secondary focus:ring-secondary mr-2">
                                <span>Semua Kategori</span>
                            </label>
                            @foreach($categories as $cat)
                                <div class="space-y-1">
                                    <label class="flex items-center text-xs text-charcoal hover:text-primary cursor-pointer py-1">
                                        <input type="radio" name="category" value="{{ $cat->slug }}" x-model="cat" class="text-secondary focus:ring-secondary mr-2">
                                        <span :class="cat === '{{ $cat->slug }}' ? 'font-bold text-primary' : ''">{{ $cat->name }}</span>
                                    </label>

                                    {{-- Subkategori tampil saat kategori induknya dipilih --}}
                                    @if($cat->subcategories->isNotEmpty())
                                        <div x-show="cat === '{{ $cat->slug }}'" x-cloak class="pl-6 space-y-1 border-l-2 border-warm-beige/80 ml-2 py-1">
                                            @foreach($cat->subcategories as $sub)
                                                <label class="flex items-center text-[11px] text-muted-gray hover:text-primary cursor-pointer py-0.5">
                                                    <input type="radio" name="subcategory" value="{{ $sub->slug }}"
                                                           :disabled="cat !== '{{ $cat->slug }}'"
                                                           {{ $filters['subcategory'] === $sub->slug ? 'checked' : '' }}
                                                           class="text-secondary focus:ring-secondary mr-2 scale-90">
                                                    <span class="{{ $filters['subcategory'] === $sub->slug ? 'font-bold text-primary' : '' }}">{{ $sub->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Condition Filter -->
                    <div>
                        <label class="block text-xs font-bold text-charcoal mb-2">Kondisi Barang</label>
                        <div class="space-y-1.5 text-xs text-charcoal">
                            @foreach([
                                '' => 'Semua Kondisi',
                                'brand_new' => 'Brand New with Tag (Baru)',
                                'new_without_tag' => 'New Without Tag',
                                'like_new' => 'Like New (Seperti Baru)',
                                'good' => 'Good Condition (Kondisi Baik)',
                                'fair' => 'Fair Condition (Tanda Pakai)',
                            ] as $val => $label)
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="condition" value="{{ $val }}"
                                           {{ (string) ($filters['condition'] ?? '') === (string) $val ? 'checked' : '' }}
                                           class="text-secondary focus:ring-secondary mr-2">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Gender Target Filter -->
                    <div>
                        <label class="block text-xs font-bold text-charcoal mb-2">Target Pengguna</label>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            @foreach(['' => 'Semua', 'women' => 'Wanita', 'men' => 'Pria', 'kids' => 'Anak-anak'] as $val => $label)
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="gender" value="{{ $val }}"
                                           {{ (string) ($filters['gender'] ?? '') === (string) $val ? 'checked' : '' }}
                                           class="text-secondary focus:ring-secondary mr-1.5">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-bold text-charcoal mb-2">Rentang Harga (Rp)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ $filters['min_price'] }}" placeholder="Min"
                                   class="w-full bg-cream/50 border border-warm-beige rounded-lg px-2.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                            <input type="number" name="max_price" value="{{ $filters['max_price'] }}" placeholder="Max"
                                   class="w-full bg-cream/50 border border-warm-beige rounded-lg px-2.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        </div>
                    </div>
                </div>

                <!-- Footer modal -->
                <div class="flex items-center gap-3 px-6 py-4 border-t border-warm-beige">
                    <a href="{{ route('catalog.index') }}" class="flex-1 text-center border border-warm-beige hover:bg-cream text-charcoal font-semibold py-2.5 rounded-xl text-xs transition">
                        Reset
                    </a>
                    <button type="submit" class="flex-2 bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-xl text-xs transition shadow-sm">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection