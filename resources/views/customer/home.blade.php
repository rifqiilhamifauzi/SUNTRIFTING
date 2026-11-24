@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-[#174C3C] via-[#12392E] to-[#0E2E24] text-[#F8F6F0] py-16 sm:py-24 lg:py-28 overflow-hidden rounded-b-[2rem] sm:rounded-b-[3rem] shadow-sm">
    <!-- Subtle Geometric Motif Overlay -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#EAE3D5_1px,transparent_1px)] [background-size:24px_24px]"></div>
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-secondary/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 -left-20 w-80 h-80 bg-emerald-600/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <!-- Tagline Pill -->
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase text-warm-beige border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Muslim Fashion & Lifestyle Thrift Marketplace</span>
                </div>

                <!-- Headline -->
                <h1 class="font-heading font-extrabold text-3xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.15] text-white">
                    Discover Meaningful Style, <br class="hidden sm:inline">
                    <span class="text-warm-beige underline decoration-secondary underline-offset-8">Shop Sustainably.</span>
                </h1>

                <!-- Subheadline -->
                <p class="text-gray-200 text-sm sm:text-base leading-relaxed max-w-xl font-normal">
                    Temukan fashion muslim dan perlengkapan pilihan dengan harga lebih terjangkau. Berikan kesempatan kedua untuk setiap produk berkualitas, rawat bumi, dan tampil bermakna.
                </p>

                <!-- Action CTAs -->
                <div class="pt-2 flex flex-wrap gap-4">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2.5 bg-secondary hover:bg-emerald-600 text-white font-semibold px-7 py-3.5 rounded-full text-sm transition shadow-lg hover:shadow-secondary/30 hover:-translate-y-0.5">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        <span>Jelajahi Produk</span>
                    </a>
                    <a href="{{ route('about') }}#mitra" class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 text-warm-beige font-semibold px-6 py-3.5 rounded-full text-sm transition backdrop-blur-sm border border-warm-beige/30">
                        <i data-lucide="store" class="w-4 h-4 text-emerald-400"></i>
                        <span>Mulai Berjualan / Titip Jual</span>
                    </a>
                </div>

                <!-- Sustainable Stats Bar -->
                <div class="pt-6 grid grid-cols-3 gap-4 sm:gap-6 border-t border-white/15">
                    <div>
                        <p class="font-heading font-extrabold text-2xl sm:text-3xl text-white">{{ $stats['total_curated'] }}+</p>
                        <p class="text-xs text-warm-beige/80 mt-0.5">Produk Terkurasi</p>
                    </div>
                    <div>
                        <p class="font-heading font-extrabold text-2xl sm:text-3xl text-white">{{ $stats['active_suppliers'] }}</p>
                        <p class="text-xs text-warm-beige/80 mt-0.5">Mitra Butik & Seller</p>
                    </div>
                    <div>
                        <p class="font-heading font-extrabold text-2xl sm:text-3xl text-white">100%</p>
                        <p class="text-xs text-warm-beige/80 mt-0.5">Transparan & Terpercaya</p>
                    </div>
                </div>
            </div>

            <!-- Hero Editorial Visual Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-sm rounded-3xl overflow-hidden shadow-2xl border border-white/20 bg-white/10 backdrop-blur-md p-3 group">
                    <img src="https://i.pinimg.com/1200x/70/f7/fc/70f7fc040e30d09d890290c896db0336.jpg" 
                         alt="Muslimah Fashion Editorial" 
                         class="w-full h-96 object-cover rounded-2xl group-hover:scale-105 transition-transform duration-700">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-emerald-300 uppercase tracking-widest">Koleksi Kurasi</span>
                            <h4 class="font-heading font-bold text-sm text-white">Abaya & Gamis Preloved Pilihan</h4>
                        </div>
                        <span class="bg-secondary text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Hemat s.d 70%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7 Core Categories Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="text-xs font-bold text-secondary tracking-widest uppercase">Eksplorasi Koleksi</span>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-charcoal mt-1">Kategori Pilihan</h2>
            <p class="text-xs text-muted-gray mt-1">Temukan busana muslim dan perlengkapan ibadah sesuai kebutuhan Anda.</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center">
            Semua Kategori <i data-lucide="chevron-right" class="w-4 h-4 ml-0.5"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 sm:gap-4">
        @foreach($categories as $cat)
            <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" 
               class="bg-white rounded-2xl p-4 border border-warm-beige/80 hover:border-secondary shadow-sm hover:shadow-md transition-all group flex flex-col justify-between text-center items-center">
                <div class="w-12 h-12 rounded-2xl bg-cream text-secondary flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white transition-colors border border-warm-beige/60">
                    <i data-lucide="{{ $cat->icon ?: 'tag' }}" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-xs text-charcoal group-hover:text-primary transition line-clamp-2 leading-snug">{{ $cat->name }}</h3>
                    <p class="text-[10px] text-muted-gray mt-1">{{ $cat->products_count }} item</p>
                </div>
            </a>
        @endforeach
    </div>
</section>

<!-- Curated Highlights (Pilihan Editor) -->
@if($curatedPicks->isNotEmpty())
<section class="bg-white py-16 border-y border-warm-beige/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold text-secondary tracking-widest uppercase">Pilihan Editor</span>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-charcoal mt-1">Produk Unggulan Pilihan</h2>
                <p class="text-xs text-muted-gray mt-1">Barang berkualitas dengan kondisi istimewa dan harga terbaik.</p>
            </div>
            <a href="{{ route('catalog.index', ['condition' => 'like_new']) }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center">
                Lihat Semua <i data-lucide="chevron-right" class="w-4 h-4 ml-0.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($curatedPicks as $product)
                <div class="bg-cream/40 rounded-2xl overflow-hidden border border-warm-beige shadow-sm hover:shadow-md transition-all group flex flex-col">
                    <a href="{{ route('product.show', $product->code) }}" class="relative aspect-[4/5] overflow-hidden bg-white block">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider border {{ $product->condition_badge_class }}">
                            {{ $product->condition_label }}
                        </span>

                        <!-- Wishlist Toggle Form -->
                        @auth
                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md shadow-sm flex items-center justify-center hover:bg-white transition text-charcoal" title="Wishlist">
                                    <i data-lucide="heart" class="w-4 h-4 {{ $product->isWishlistedBy(auth()->user()) ? 'fill-primary text-primary' : 'text-muted-gray' }}"></i>
                                </button>
                            </form>
                        @endauth

                        @if($product->size)
                            <span class="absolute bottom-3 right-3 bg-charcoal/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded">
                                {{ $product->size }}
                            </span>
                        @endif
                    </a>

                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[10px] font-bold text-secondary uppercase tracking-wider">
                                <span>{{ $product->brand ?: 'Curated Muslim' }}</span>
                                <span class="text-muted-gray font-normal">{{ $product->location }}</span>
                            </div>
                            <h3 class="font-heading font-semibold text-sm text-charcoal hover:text-primary line-clamp-1 mt-1">
                                <a href="{{ route('product.show', $product->code) }}">{{ $product->name }}</a>
                            </h3>
                            <p class="text-xs text-muted-gray line-clamp-1 mt-0.5">Toko: {{ $product->supplier ? $product->supplier->name : 'Mitra Terverifikasi' }}</p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-warm-beige flex items-center justify-between">
                            <div>
                                <span class="font-heading font-bold text-base text-primary">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                @if($product->original_price && $product->original_price > $product->selling_price)
                                    <span class="text-[11px] text-muted-gray line-through ml-1.5">Rp{{ number_format($product->original_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <a href="{{ route('product.show', $product->code) }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center">
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

<!-- Latest Arrivals Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="text-xs font-bold text-secondary tracking-widest uppercase">Koleksi Terbaru</span>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-charcoal mt-1">Baru Saja Ditambahkan</h2>
            <p class="text-xs text-muted-gray mt-1">Temukan produk thrift muslim teranyar dari berbagai seller mitra.</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center">
            Lihat Semua Produk <i data-lucide="chevron-right" class="w-4 h-4 ml-0.5"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($latestProducts as $product)
            <div class="bg-white rounded-2xl overflow-hidden border border-warm-beige shadow-sm hover:shadow-md transition-all group flex flex-col">
                <a href="{{ route('product.show', $product->code) }}" class="relative aspect-[4/5] overflow-hidden bg-cream block">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-2.5 left-2.5 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $product->condition_badge_class }}">
                        {{ $product->condition_label }}
                    </span>

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

                <div class="p-3.5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-[9px] font-bold text-secondary uppercase tracking-wider">
                            <span>{{ $product->brand ?: 'Curated Muslim' }}</span>
                            <span class="text-muted-gray font-normal">{{ $product->location }}</span>
                        </div>
                        <h3 class="font-heading font-semibold text-xs sm:text-sm text-charcoal hover:text-primary line-clamp-1 mt-0.5">
                            <a href="{{ route('product.show', $product->code) }}">{{ $product->name }}</a>
                        </h3>
                        <p class="text-[11px] text-muted-gray line-clamp-1 mt-0.5">Toko: {{ $product->supplier ? $product->supplier->name : 'Mitra Terverifikasi' }}</p>
                    </div>

                    <div class="mt-3 pt-2.5 border-t border-warm-beige flex items-center justify-between">
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

    <div class="mt-12 text-center">
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-3.5 rounded-full text-sm transition shadow-sm">
            <span>Buka Katalog Lengkap</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>
</section>

<!-- Kenapa SUN-THRIFTING? (4 Pillars) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-warm-beige">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-bold text-secondary tracking-widest uppercase">Nilai & Komitmen Kami</span>
        <h2 class="font-heading font-bold text-2xl sm:text-3xl text-charcoal mt-1">Kenapa Memilih SUN-THRIFTING?</h2>
        <p class="text-xs text-muted-gray mt-2">Menghubungkan gaya hidup muslim yang anggun dengan kepedulian terhadap kelestarian lingkungan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-warm-beige shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                <i data-lucide="sparkles" class="w-6 h-6"></i>
            </div>
            <h4 class="font-heading font-bold text-base text-charcoal">Pilihan Muslim Beragam</h4>
            <p class="text-xs text-muted-gray mt-2 leading-relaxed">
                Mulai dari abaya, gamis, koko kurta, hijab voal, hingga mukena silk dan sajadah Turkey dari brand ternama.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-warm-beige shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                <i data-lucide="tag" class="w-6 h-6"></i>
            </div>
            <h4 class="font-heading font-bold text-base text-charcoal">Harga Lebih Terjangkau</h4>
            <p class="text-xs text-muted-gray mt-2 leading-relaxed">
                Dapatkan produk muslim berkualitas tinggi dengan potongan harga signifikan hingga 70% dibanding harga rilis baru.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-warm-beige shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                <i data-lucide="leaf" class="w-6 h-6"></i>
            </div>
            <h4 class="font-heading font-bold text-base text-charcoal">Sustainable Fashion</h4>
            <p class="text-xs text-muted-gray mt-2 leading-relaxed">
                Mendukung perputaran kembali pakaian layak pakai untuk mengurangi limbah tekstil dan jejak karbon lingkungan.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-warm-beige shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <h4 class="font-heading font-bold text-base text-charcoal">Marketplace Terpercaya</h4>
            <p class="text-xs text-muted-gray mt-2 leading-relaxed">
                Informasi kondisi barang dan catatan kekurangan disajikan secara transparan dengan jaminan transaksi aman.
            </p>
        </div>
    </div>
</section>

<!-- CTA Seller / Titip Jual Banner -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="bg-gradient-to-r from-[#174C3C] to-[#26745A] rounded-3xl p-8 sm:p-12 text-white flex flex-col md:flex-row items-center justify-between gap-8 shadow-md">
        <div class="space-y-3 max-w-xl">
            <span class="inline-block bg-white/20 text-warm-beige text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Konsinyasi & Kemitraan</span>
            <h3 class="font-heading font-extrabold text-2xl sm:text-3xl tracking-tight text-white">Punya Busana Muslim Preloved Berkualitas di Lemari Anda?</h3>
            <p class="text-xs sm:text-sm text-warm-beige/90 leading-relaxed">
                Jadilah mitra seller SUN-THRIFTING. Titipkan busana muslim, gamis, koko, dan perlengkapan ibadah Anda dengan sistem pembagian hasil transparan (90% hak seller).
            </p>
        </div>
        <div class="shrink-0 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('about') }}#mitra" class="bg-white hover:bg-warm-beige text-primary font-bold px-6 py-3.5 rounded-full text-xs transition shadow-sm text-center">
                Pelajari Cara Titip Jual
            </a>
            <a href="https://wa.me/{{ \App\Models\Setting::get('contact_whatsapp', '6281234567890') }}" target="_blank" class="bg-black/20 hover:bg-black/30 text-white font-semibold px-6 py-3.5 rounded-full text-xs transition text-center border border-white/20">
                Hubungi Admin WhatsApp
            </a>
        </div>
    </div>
</section>
@endsection
