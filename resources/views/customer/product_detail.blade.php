@extends('layouts.app', ['title' => $product->name . ' — Suntrifting'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary">Beranda</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{ route('catalog.index') }}" class="hover:text-primary">Katalog</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="hover:text-primary">{{ $product->category->name }}</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-700 font-medium truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Product Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Photo Gallery (Left) -->
        <div class="lg:col-span-7" x-data="{ currentImage: '{{ $product->image_url }}' }">
            <div class="aspect-square bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm relative group">
                <img :src="currentImage" alt="{{ $product->name }}" class="w-full h-full object-cover">
                <div class="absolute top-4 left-4">
                    {!! $product->status_badge !!}
                </div>
            </div>

            <!-- Thumbnail Carousel if multiple images -->
            @if($product->images->count() > 1)
                <div class="flex items-center space-x-3 mt-4 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <button type="button" @click="currentImage = '{{ $img->url }}'" 
                                :class="currentImage === '{{ $img->url }}' ? 'ring-2 ring-accent' : 'opacity-70 hover:opacity-100'"
                                class="w-20 h-20 rounded-xl overflow-hidden border border-gray-200 shrink-0 transition">
                            <img src="{{ $img->url }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Information & Action (Right) -->
        <div class="lg:col-span-5 space-y-6">
            <div>
                <span class="inline-block bg-accent/10 text-accent font-bold text-xs uppercase tracking-widest px-3 py-1 rounded-full mb-2">
                    {{ $product->brand ?: 'Vintage Collection' }}
                </span>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-primary leading-tight">
                    {{ $product->name }}
                </h1>
                <p class="text-xs text-gray-400 mt-1 font-mono">Kode Produk: {{ $product->code }}</p>
            </div>

            <!-- Price & Availability -->
            <div class="p-5 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-gray-400 block uppercase tracking-wider font-semibold">Harga Jual</span>
                    <span class="font-heading font-extrabold text-2xl sm:text-3xl text-primary">
                        Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-[11px] text-gray-400 block uppercase tracking-wider font-semibold">Status Stok</span>
                    <span class="text-xs font-bold {{ $product->status === 'available' ? 'text-emerald-600' : 'text-gray-500' }}">
                        {{ $product->status === 'available' ? 'Tersedia (Stok: 1)' : ucfirst($product->status) }}
                    </span>
                </div>
            </div>

            <!-- Condition & Specifications Table -->
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-3">
                <h3 class="font-heading font-bold text-sm text-primary pb-2 border-b border-gray-100">Spesifikasi & Kondisi</h3>
                
                <div class="grid grid-cols-2 gap-y-2.5 text-xs">
                    <div class="text-gray-400">Kondisi Barang:</div>
                    <div class="font-semibold text-gray-800">{{ $product->condition_label }}</div>

                    <div class="text-gray-400">Ukuran / Size:</div>
                    <div class="font-semibold text-gray-800">{{ $product->size ?: 'All Size' }}</div>

                    <div class="text-gray-400">Warna:</div>
                    <div class="font-semibold text-gray-800">{{ $product->color ?: '-' }}</div>

                    <div class="text-gray-400">Berat Pengiriman:</div>
                    <div class="font-semibold text-gray-800">{{ $product->weight_grams }} gram</div>

                    <div class="text-gray-400">Mitra Konsinyasi:</div>
                    <div class="font-semibold text-accent flex items-center">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 mr-1 text-accent"></i>
                        {{ $product->supplier ? $product->supplier->name : 'Suntrifting Vault' }}
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-2">
                <h3 class="font-heading font-bold text-sm text-primary">Deskripsi Detail</h3>
                <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $product->description ?: 'Barang thrift preloved terkurasi dengan kondisi prima. Silakan periksa ukuran dan spesifikasi sebelum checkout.' }}
                </p>
            </div>

            <!-- Add to Cart / Checkout CTA -->
            <div>
                @if($product->status === 'available')
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-4 px-6 rounded-2xl text-sm transition shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            <span>Tambah ke Keranjang Belanja</span>
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-200 text-gray-400 font-semibold py-4 px-6 rounded-2xl text-sm cursor-not-allowed flex items-center justify-center space-x-2">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                        <span>Produk Sudah Tidak Tersedia</span>
                    </button>
                @endif
                <p class="text-[11px] text-gray-400 text-center mt-2">
                    <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1 text-accent"></i>
                    Barang thrift bersifat unik (1-of-1). Segera checkout setelah menambah ke keranjang.
                </p>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-20 pt-12 border-t border-gray-200">
            <h2 class="font-heading font-bold text-xl sm:text-2xl text-primary mb-6">Produk Terkait Lainnya</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-all group flex flex-col">
                        <a href="{{ route('product.show', $rel->code) }}" class="relative aspect-square overflow-hidden bg-gray-50 block">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @if($rel->size)
                                <span class="absolute bottom-2.5 right-2.5 bg-black/75 text-white text-[9px] font-semibold px-2 py-0.5 rounded">
                                    {{ $rel->size }}
                                </span>
                            @endif
                        </a>
                        <div class="p-3.5 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-[9px] font-bold text-accent uppercase tracking-wider">{{ $rel->brand ?: 'Vintage' }}</span>
                                <h3 class="font-heading font-semibold text-xs sm:text-sm text-primary hover:text-accent line-clamp-1 mt-0.5">
                                    <a href="{{ route('product.show', $rel->code) }}">{{ $rel->name }}</a>
                                </h3>
                            </div>
                            <div class="mt-3 pt-2 border-t border-gray-100">
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

