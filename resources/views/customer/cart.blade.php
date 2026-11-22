@extends('layouts.app', ['title' => 'Keranjang Belanja — Suntrifting'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-primary">Keranjang Belanja</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">
            Periksa item konsinyasi thrift yang telah Anda pilih.
        </p>
    </div>

    @if($cart->items->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-full bg-accent/10 text-accent flex items-center justify-center mx-auto mb-4">
                <i data-lucide="shopping-bag" class="w-8 h-8"></i>
            </div>
            <h3 class="font-heading font-bold text-lg text-primary">Keranjang Anda Masih Kosong</h3>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                Temukan berbagai barang vintage dan pakaian preloved autentik yang sesuai dengan gaya Anda.
            </p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 mt-6 bg-primary hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-full text-xs transition shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Mulai Belanja</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Cart Items List (Left) -->
            <div class="lg:col-span-8 bg-white rounded-3xl border border-gray-100 p-6 shadow-sm divide-y divide-gray-100">
                @foreach($cart->items as $item)
                    <div class="py-5 first:pt-0 last:pb-0 flex items-center space-x-4">
                        <a href="{{ route('product.show', $item->product->code) }}" class="w-20 h-20 rounded-2xl bg-gray-50 overflow-hidden shrink-0 border border-gray-100">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        </a>

                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-bold text-accent uppercase tracking-wider">{{ $item->product->brand ?: 'Vintage' }}</span>
                            <h4 class="font-heading font-bold text-sm text-primary hover:text-accent truncate">
                                <a href="{{ route('product.show', $item->product->code) }}">{{ $item->product->name }}</a>
                            </h4>
                            <div class="flex items-center space-x-3 mt-1 text-xs text-gray-500">
                                <span>Ukuran: <strong>{{ $item->product->size ?: '-' }}</strong></span>
                                <span>&bull;</span>
                                <span>Kondisi: <strong>{{ $item->product->condition_label }}</strong></span>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <div class="font-heading font-bold text-sm text-primary">
                                Rp{{ number_format($item->product->selling_price, 0, ',', '.') }}
                            </div>

                            <form action="{{ route('cart.remove', $item->product_id) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 flex items-center justify-end transition" title="Hapus item">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary (Right) -->
            <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-5 sticky top-24">
                <h3 class="font-heading font-bold text-base text-primary pb-3 border-b border-gray-100">Ringkasan Pesanan</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Total Item Thrift:</span>
                        <span class="font-semibold text-gray-800">{{ $cart->items_count }} item</span>
                    </div>

                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal Produk:</span>
                        <span class="font-semibold text-gray-800 font-mono">Rp{{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-gray-500">
                        <span>Ongkos Kirim:</span>
                        <span class="text-gray-400 italic">Dihitung saat checkout</span>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-between items-center text-sm">
                        <span class="font-heading font-bold text-primary">Total Sementara:</span>
                        <span class="font-heading font-extrabold text-lg text-primary font-mono">
                            Rp{{ number_format($cart->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-3.5 px-6 rounded-2xl text-xs transition shadow-md flex items-center justify-center space-x-2">
                    <span>Lanjut ke Checkout</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>

                <div class="p-3 bg-gray-50 rounded-xl text-[11px] text-gray-500 space-y-1">
                    <p class="flex items-center"><i data-lucide="lock" class="w-3.5 h-3.5 text-accent mr-1.5 shrink-0"></i> Transaksi dijamin aman & terlindungi</p>
                    <p class="flex items-center"><i data-lucide="truck" class="w-3.5 h-3.5 text-accent mr-1.5 shrink-0"></i> Pengiriman reguler se-Indonesia</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

