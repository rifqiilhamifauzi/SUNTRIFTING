@extends('layouts.app', ['title' => 'Wishlist Saya — SUN-THRIFTING'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-charcoal">Wishlist Produk Saya</h1>
        <p class="text-xs sm:text-sm text-muted-gray mt-1">Daftar busana muslim dan perlengkapan ibadah yang Anda simpan.</p>
    </div>

    @if($wishlistItems->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-warm-beige shadow-sm my-8">
            <div class="w-16 h-16 rounded-full bg-cream text-secondary flex items-center justify-center mx-auto mb-4 border border-warm-beige">
                <i data-lucide="heart" class="w-8 h-8"></i>
            </div>
            <h3 class="font-heading font-bold text-lg text-charcoal">Wishlist Anda Masih Kosong</h3>
            <p class="text-xs text-muted-gray mt-1.5 max-w-sm mx-auto leading-relaxed">
                Jelajahi katalog busana muslim dan klik ikon hati pada produk yang Anda sukai untuk menyimpannya di sini.
            </p>
            <div class="mt-6">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-primary hover:bg-primary-dark text-white text-xs font-semibold px-6 py-2.5 rounded-full transition shadow-sm">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span>Mulai Jelajahi Produk</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($wishlistItems as $product)
                <div class="bg-white rounded-2xl overflow-hidden border border-warm-beige shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                    <div>
                        <a href="{{ route('product.show', $product->code) }}" class="relative aspect-[4/5] overflow-hidden bg-cream block">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <span class="absolute top-2.5 left-2.5 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $product->condition_badge_class }}">
                                {{ $product->condition_label }}
                            </span>

                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-2.5 right-2.5 z-10">
                                @csrf
                                <button type="submit" class="w-7 h-7 rounded-full bg-white/90 backdrop-blur-md shadow-sm flex items-center justify-center hover:bg-rose-50 transition text-rose-500" title="Hapus dari Wishlist">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>

                            @if($product->size)
                                <span class="absolute bottom-2.5 right-2.5 bg-charcoal/80 text-white text-[9px] font-semibold px-2 py-0.5 rounded">
                                    {{ $product->size }}
                                </span>
                            @endif
                        </a>

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

                    <div class="p-3.5 pt-0">
                        <div class="pt-2.5 border-t border-warm-beige flex items-center justify-between">
                            <div>
                                <span class="font-heading font-bold text-xs sm:text-sm text-primary">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            </div>

                            @if($product->status === 'available')
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-cream hover:bg-primary hover:text-white text-charcoal flex items-center justify-center transition border border-warm-beige" title="Tambah ke Keranjang">
                                        <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] text-muted-gray font-semibold">{{ ucfirst($product->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $wishlistItems->links() }}
        </div>
    @endif
</div>
@endsection

