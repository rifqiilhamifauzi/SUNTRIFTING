@extends('layouts.app', ['title' => $product->name . ' — SUN-THRIFTING'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-muted-gray mb-6 flex-wrap gap-y-1">
        <a href="{{ route('home') }}" class="hover:text-primary transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-primary transition">Katalog</a>
        @if($product->category)
            <span>/</span>
            <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="hover:text-primary transition">{{ $product->category->name }}</a>
        @endif
        @if($product->subcategory)
            <span>/</span>
            <a href="{{ route('catalog.index', ['subcategory' => $product->subcategory->slug]) }}" class="hover:text-primary transition">{{ $product->subcategory->name }}</a>
        @endif
        <span>/</span>
        <span class="text-charcoal font-semibold truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Product Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Photo Gallery (Left) -->
        <div class="lg:col-span-7" x-data="{ currentImage: '{{ $product->image_url }}', showDefectModal: false }">
            <div class="aspect-[4/5] bg-white rounded-3xl overflow-hidden border border-warm-beige shadow-sm relative group">
                <img :src="currentImage" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-300">
                <div class="absolute top-4 left-4">
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full uppercase border shadow-sm {{ $product->condition_badge_class }}">
                        {{ $product->condition_label }}
                    </span>
                </div>

                @if($product->status !== 'available')
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
                        <span class="bg-white text-charcoal font-heading font-extrabold text-sm uppercase px-5 py-2.5 rounded-full shadow-lg">
                            {{ $product->status === 'reserved' ? 'Sedang Dipesan Pembeli Lain' : 'Produk Telah Terjual' }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Thumbnail Carousel (Regular & Defect Photos) -->
            @php
                $allImages = $product->images;
            @endphp
            @if($allImages->count() > 1)
                <div class="flex items-center space-x-3 mt-4 overflow-x-auto pb-2">
                    @foreach($allImages as $img)
                        <button type="button" @click="currentImage = '{{ $img->url }}'" 
                                :class="currentImage === '{{ $img->url }}' ? 'ring-2 ring-primary border-transparent' : 'opacity-70 hover:opacity-100 border-warm-beige'"
                                class="w-20 h-20 rounded-2xl overflow-hidden border bg-white shrink-0 transition relative group">
                            <img src="{{ $img->url }}" alt="Thumbnail" class="w-full h-full object-cover">
                            @if($img->is_defect)
                                <span class="absolute bottom-1 right-1 bg-amber-500 text-white text-[8px] font-bold px-1 rounded">Defect</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Thrift Transparency Assurance Banner -->
            <div class="mt-6 p-4 bg-white rounded-2xl border border-warm-beige/80 text-xs text-charcoal space-y-2 shadow-sm">
                <div class="flex items-center space-x-2 font-bold text-primary">
                    <i data-lucide="shield-check" class="w-4 h-4 text-secondary"></i>
                    <span>Jaminan Transparansi Thrifting SUN-THRIFTING</span>
                </div>
                <p class="text-muted-gray leading-relaxed text-[11px]">
                    Setiap barang di SUN-THRIFTING adalah stok 1-of-1 terkurasi. Foto di atas adalah foto asli dari produk yang akan Anda terima. Jika ada minus atau tanda pemakaian, kami jelaskan secara jujur dan transparan di kolom catatan.
                </p>
            </div>
        </div>

        <!-- Product Information & Action (Right) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Brand, Title & Code -->
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="inline-block bg-cream text-secondary font-bold text-xs uppercase tracking-widest px-3 py-1 rounded-full border border-warm-beige">
                        {{ $product->brand ?: 'Curated Muslim' }}
                    </span>
                    <span class="text-xs text-muted-gray font-medium flex items-center">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1 text-secondary"></i>
                        {{ $product->location }}
                    </span>
                </div>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-charcoal leading-tight">
                    {{ $product->name }}
                </h1>
                <p class="text-xs text-muted-gray mt-1.5 font-mono">Kode SKU: {{ $product->code }}</p>
            </div>

            <!-- Price & Availability Card -->
            <div class="p-5 bg-white rounded-2xl border border-warm-beige shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-muted-gray block uppercase tracking-wider font-semibold">Harga Jual</span>
                    <div class="flex items-baseline space-x-2">
                        <span class="font-heading font-extrabold text-2xl sm:text-3xl text-primary">
                            Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                        </span>
                        @if($product->original_price && $product->original_price > $product->selling_price)
                            <span class="text-xs text-muted-gray line-through">
                                Rp{{ number_format($product->original_price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[11px] text-muted-gray block uppercase tracking-wider font-semibold">Status Ketersediaan</span>
                    <span class="text-xs font-bold {{ $product->status === 'available' ? 'text-secondary' : 'text-muted-gray' }}">
                        {{ $product->status === 'available' ? 'Tersedia (Stok: 1-of-1)' : ucfirst($product->status) }}
                    </span>
                </div>
            </div>

            <!-- Defect / Minus Callout (CRITICAL TRANSPARENCY) -->
            @if($product->defect_description)
                <div class="bg-amber-50/80 border border-amber-200/90 rounded-2xl p-4 text-xs text-amber-900 space-y-1.5 shadow-sm">
                    <div class="flex items-center space-x-2 font-bold text-amber-800">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0"></i>
                        <span>Catatan Minus / Kekurangan Produk (Transparan)</span>
                    </div>
                    <p class="leading-relaxed text-amber-900/90 pl-6">
                        {{ $product->defect_description }}
                    </p>
                </div>
            @endif

            <!-- Condition & Specifications Table -->
            <div class="bg-white rounded-2xl border border-warm-beige p-5 shadow-sm space-y-3">
                <h3 class="font-heading font-bold text-sm text-charcoal pb-2 border-b border-warm-beige flex items-center justify-between">
                    <span>Spesifikasi & Karakteristik</span>
                    <span class="text-[11px] text-secondary font-normal">{{ $product->getGenderLabelAttribute() }}</span>
                </h3>
                
                <div class="grid grid-cols-2 gap-y-2.5 text-xs">
                    <div class="text-muted-gray">Kondisi Barang:</div>
                    <div class="font-semibold text-charcoal flex items-center">
                        <span class="w-2 h-2 rounded-full bg-secondary inline-block mr-1.5"></span>
                        {{ $product->condition_label }}
                    </div>

                    <div class="text-muted-gray">Kategori:</div>
                    <div class="font-semibold text-charcoal">{{ $product->category ? $product->category->name : '-' }}</div>

                    @if($product->subcategory)
                        <div class="text-muted-gray">Subkategori:</div>
                        <div class="font-semibold text-charcoal">{{ $product->subcategory->name }}</div>
                    @endif

                    <div class="text-muted-gray">Ukuran / Size:</div>
                    <div class="font-semibold text-charcoal">{{ $product->size ?: 'All Size' }}</div>

                    <div class="text-muted-gray">Warna:</div>
                    <div class="font-semibold text-charcoal">{{ $product->color ?: '-' }}</div>

                    <div class="text-muted-gray">Material / Bahan:</div>
                    <div class="font-semibold text-charcoal">{{ $product->material ?: '-' }}</div>

                    <div class="text-muted-gray">Berat Pengiriman:</div>
                    <div class="font-semibold text-charcoal">{{ $product->weight_grams }} gram</div>

                    <div class="text-muted-gray">Toko Penjual:</div>
                    <div class="font-semibold text-primary">
                        {{ $product->supplier ? $product->supplier->name : 'Mitra Terverifikasi' }}
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl border border-warm-beige p-5 shadow-sm space-y-2">
                <h3 class="font-heading font-bold text-sm text-charcoal">Deskripsi Produk</h3>
                <p class="text-xs text-charcoal/80 leading-relaxed whitespace-pre-line">
                    {{ $product->description ?: 'Busana muslim preloved pilihan dengan standar kurasi terawat. Silakan periksa ukuran dan spesifikasi sebelum checkout.' }}
                </p>
            </div>

            <!-- Action Buttons: Cart, Wishlist, WhatsApp -->
            <div class="space-y-3 pt-2">
                @if($product->status === 'available')
                    <div class="flex gap-3">
                        <!-- Add to Cart -->
                        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3.5 px-6 rounded-2xl text-xs sm:text-sm transition shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                <span>Tambah ke Keranjang</span>
                            </button>
                        </form>

                        <!-- Wishlist Toggle -->
                        @auth
                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="h-full px-4 rounded-2xl border border-warm-beige bg-white hover:bg-cream text-charcoal transition flex items-center justify-center shadow-sm" title="Wishlist">
                                    <i data-lucide="heart" class="w-5 h-5 {{ $product->isWishlistedBy(auth()->user()) ? 'fill-primary text-primary' : 'text-muted-gray' }}"></i>
                                </button>
                            </form>
                        @endauth
                    </div>

                    <!-- WhatsApp Seller Inquiry -->
                    @php
                        $sellerPhone = $product->supplier ? preg_replace('/[^0-9]/', '', $product->supplier->phone) : '6281234567890';
                        if (str_starts_with($sellerPhone, '0')) {
                            $sellerPhone = '62' . substr($sellerPhone, 1);
                        }
                        $waText = urlencode("Halo Toko " . ($product->supplier ? $product->supplier->name : 'SUN-THRIFTING') . ", saya ingin bertanya mengenai produk: " . $product->name . " (Kode: " . $product->code . ") di SUN-THRIFTING.");
                    @endphp
                    <a href="https://wa.me/{{ $sellerPhone }}?text={{ $waText }}" target="_blank" 
                       class="w-full bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 font-semibold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center space-x-2">
                        <i data-lucide="message-circle" class="w-4 h-4 text-emerald-600"></i>
                        <span>Tanya Penjual via WhatsApp</span>
                    </a>
                @else
                    <button disabled class="w-full bg-gray-200 text-gray-400 font-semibold py-3.5 px-6 rounded-2xl text-xs sm:text-sm cursor-not-allowed flex items-center justify-center space-x-2">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        <span>Produk Sudah Tidak Tersedia</span>
                    </button>
                @endif

                <p class="text-[11px] text-muted-gray text-center">
                    <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1 text-secondary"></i>
                    Stok barang konsinyasi adalah 1-of-1. Sistem menggunakan penguncian database aman saat proses checkout.
                </p>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-20 pt-12 border-t border-warm-beige">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <span class="text-xs font-bold text-secondary tracking-widest uppercase">Rekomendasi</span>
                    <h2 class="font-heading font-bold text-xl sm:text-2xl text-charcoal mt-0.5">Produk Serupa Lainnya</h2>
                </div>
                <a href="{{ route('catalog.index', ['category' => $product->category ? $product->category->slug : null]) }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center">
                    Lihat Koleksi Kategori <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="bg-white rounded-2xl overflow-hidden border border-warm-beige shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                        <a href="{{ route('product.show', $rel->code) }}" class="relative aspect-[4/5] overflow-hidden bg-cream block">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-2 left-2 text-[8px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $rel->condition_badge_class }}">
                                {{ $rel->condition_label }}
                            </span>
                            @if($rel->size)
                                <span class="absolute bottom-2 right-2 bg-charcoal/80 text-white text-[9px] font-semibold px-2 py-0.5 rounded">
                                    {{ $rel->size }}
                                </span>
                            @endif
                        </a>
                        <div class="p-3.5">
                            <span class="text-[9px] font-bold text-secondary uppercase tracking-wider">{{ $rel->brand ?: 'Curated Muslim' }}</span>
                            <h3 class="font-heading font-semibold text-xs sm:text-sm text-charcoal hover:text-primary line-clamp-1 mt-0.5">
                                <a href="{{ route('product.show', $rel->code) }}">{{ $rel->name }}</a>
                            </h3>
                            <div class="mt-2.5 pt-2 border-t border-warm-beige">
                                <span class="font-heading font-bold text-xs sm:text-sm text-primary">Rp{{ number_format($rel->selling_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
