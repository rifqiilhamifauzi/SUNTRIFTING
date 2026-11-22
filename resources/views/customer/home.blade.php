@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative bg-[#1C1C1C] text-[#FAF9F6] py-20 lg:py-28 overflow-hidden rounded-b-[2.5rem]">
    <!-- Background Texture & Accents -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#84977B_1px,transparent_1px)] [background-size:20px_20px]"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-accent/20 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase text-accent">
                    <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                    <span>Curated Preloved & Consignment Store</span>
                </div>

                <h1 class="font-heading font-extrabold text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.1] text-white">
                    Gaya Vintage Autentik, <br class="hidden sm:inline">
                    <span class="text-accent underline decoration-white/20 underline-offset-8">Kualitas Terpilih.</span>
                </h1>

                <p class="text-gray-300 text-sm sm:text-base leading-relaxed max-w-xl font-normal">
                    Marketplace konsinyasi thrift streetwear & vintage 1-of-1 dari supplier pilihan di seluruh Indonesia. Setiap barang dicek, diukur, dan diverifikasi keasliannya.
                </p>

                <div class="pt-2 flex flex-wrap gap-4">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3.5 rounded-full text-sm transition shadow-lg hover:shadow-accent/25 hover:-translate-y-0.5">
                        <span>Jelajahi Koleksi</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="{{ route('catalog.index', ['condition' => 'like_new']) }}" class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3.5 rounded-full text-sm transition backdrop-blur-sm">
                        <i data-lucide="sparkles" class="w-4 h-4 text-accent"></i>
                        <span>Koleksi Like New</span>
                    </a>
                </div>

                <!-- Stats Badges -->
                <div class="pt-6 grid grid-cols-3 gap-6 border-t border-white/10">
                    <div>
                        <p class="font-heading font-bold text-2xl text-white">{{ $stats['total_curated'] }}+</p>
                        <p class="text-xs text-gray-400">Item Terkurasi</p>
                    </div>
                    <div>
                        <p class="font-heading font-bold text-2xl text-white">{{ $stats['active_suppliers'] }}</p>
                        <p class="text-xs text-gray-400">Mitra Supplier</p>
                    </div>
                    <div>
                        <p class="font-heading font-bold text-2xl text-white">100%</p>
                        <p class="text-xs text-gray-400">Stok Unik (1-of-1)</p>
                    </div>
                </div>
            </div>

            <!-- Hero Visual Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-sm rounded-3xl overflow-hidden shadow-2xl border border-white/10 bg-white/5 backdrop-blur-md p-3 group">
                    <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?w=800&auto=format&fit=crop&q=80" 
                         alt="Vintage Thrift Hero" 
                         class="w-full h-96 object-cover rounded-2xl group-hover:scale-105 transition-transform duration-700">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-semibold text-accent uppercase tracking-widest">Featured Archive</span>
                            <h4 class="font-heading font-bold text-sm text-white">Carhartt Detroit Vintage J97</h4>
                        </div>
                        <span class="bg-accent text-white text-xs font-bold px-3 py-1.5 rounded-full">Rp1.250.000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="text-xs font-bold text-accent tracking-widest uppercase">Eksplorasi</span>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-primary mt-1">Kategori Pilihan</h2>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-accent hover:text-accent-dark flex items-center">
            Semua Kategori <i data-lucide="chevron-right" class="w-4 h-4 ml-0.5"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($categories as $cat)
            <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" 
               class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-accent/40 shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center mb-3 group-hover:bg-accent group-hover:text-white transition-colors">
                    <i data-lucide="{{ $cat->icon ?: 'tag' }}" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-sm text-primary group-hover:text-accent transition">{{ $cat->name }}</h3>
                    <p class="text-[11px] text-gray-500 mt-1">{{ $cat->products_count }} item tersedia</p>
                </div>
            </a>
        @endforeach
    </div>
</section>

<!-- Curated Highlights -->
@if($curatedPicks->isNotEmpty())
<section class="bg-beige/40 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold text-accent tracking-widest uppercase">Pilihan Editor</span>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-primary mt-1">Curated Rare Finds</h2>
            </div>
            <a href="{{ route('catalog.index', ['sort' => 'price_high']) }}" class="text-xs font-semibold text-accent hover:text-accent-dark flex items-center">
                Lihat Semua <i data-lucide="chevron-right" class="w-4 h-4 ml-0.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($curatedPicks as $product)
                <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-lg transition-all group flex flex-col">
                    <a href="{{ route('product.show', $product->code) }}" class="relative aspect-square overflow-hidden bg-gray-100 block">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-primary text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                            {{ $product->condition_label }}
                        </span>
                        @if($product->size)
                            <span class="absolute bottom-3 right-3 bg-black/75 text-white text-[10px] font-semibold px-2 py-0.5 rounded">
                                {{ $product->size }}
                            </span>
                        @endif
                    </a>

                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-accent uppercase tracking-wider">{{ $product->brand ?: 'Vintage' }}</span>
                            <h3 class="font-heading font-semibold text-sm text-primary hover:text-accent line-clamp-1 mt-0.5">
                                <a href="{{ route('product.show', $product->code) }}">{{ $product->name }}</a>
                            </h3>
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="font-heading font-bold text-base text-primary">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            <a href="{{ route('product.show', $product->code) }}" class="text-xs font-semibold text-accent hover:text-accent-dark flex items-center">
                                Detail <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Arrivals -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="text-xs font-bold text-accent tracking-widest uppercase">Koleksi Terbaru</span>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-primary mt-1">Baru Saja Masuk</h2>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-accent hover:text-accent-dark flex items-center">
            Lihat Semua Produk <i data-lucide="chevron-right" class="w-4 h-4 ml-0.5"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($latestProducts as $product)
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

    <div class="mt-12 text-center">
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-primary hover:bg-gray-800 text-white font-semibold px-8 py-3.5 rounded-full text-sm transition shadow-sm">
            <span>Buka Katalog Lengkap</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>
</section>

<!-- Consignment Value Proposition -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-gray-200">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="font-heading font-bold text-base text-primary">100% Curated & Authenticated</h4>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Setiap potong pakaian thrift diperiksa kondisinya, dicuci steril, diukur presisi, dan dijamin keasliannya.
                </p>
            </div>
        </div>

        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                <i data-lucide="repeat" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="font-heading font-bold text-base text-primary">Sistem Konsinyasi Adil (90:10)</h4>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Mewadahi penjual & supplier barang vintage dengan pembagian hasil otomatis dan transparan.
                </p>
            </div>
        </div>

        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                <i data-lucide="sparkles" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="font-heading font-bold text-base text-primary">PWA Fast & Offline Ready</h4>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Dapat di-install ke smartphone dan desktop Anda layaknya aplikasi native dengan akses katalog offline.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

