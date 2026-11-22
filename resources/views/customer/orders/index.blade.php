@extends('layouts.app', ['title' => 'Pesanan Saya — Suntrifting'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-primary">Pesanan Saya</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Lacak status transaksi dan pengiriman barang thrift pesanan Anda.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-4 mb-6 border-b border-gray-200">
        <a href="{{ route('orders.index') }}" 
           class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition {{ empty($status) ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('orders.index', ['status' => 'unpaid']) }}" 
           class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition {{ $status === 'unpaid' ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
            Menunggu Pembayaran
        </a>
        <a href="{{ route('orders.index', ['status' => 'verification']) }}" 
           class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition {{ $status === 'verification' ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
            Verifikasi Pembayaran
        </a>
        <a href="{{ route('orders.index', ['status' => 'processing']) }}" 
           class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition {{ $status === 'processing' ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
            Diproses / Dikirim
        </a>
        <a href="{{ route('orders.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition {{ $status === 'completed' ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
            Selesai
        </a>
        <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition {{ $status === 'cancelled' ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Orders List -->
    @if($orders->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center max-w-md mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-full bg-accent/10 text-accent flex items-center justify-center mx-auto mb-4">
                <i data-lucide="package-search" class="w-8 h-8"></i>
            </div>
            <h3 class="font-heading font-bold text-lg text-primary">Tidak Ada Pesanan Ditemukan</h3>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                Anda belum memiliki riwayat pesanan dengan status ini.
            </p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 mt-6 bg-primary hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-full text-xs transition shadow-sm">
                <span>Cari Barang Thrift</span>
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition">
                    <!-- Order Header -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-gray-100 text-xs">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono font-bold text-gray-900">{{ $order->order_number }}</span>
                            <span class="text-gray-400">&bull;</span>
                            <span class="text-gray-500">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            {!! $order->order_status_badge !!}
                            {!! $order->payment_status_badge !!}
                        </div>
                    </div>

                    <!-- Items Preview -->
                    <div class="py-4 space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $item->product ? $item->product->image_url : 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=200' }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="w-12 h-12 rounded-xl object-cover bg-gray-50 border border-gray-100 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-heading font-semibold text-xs text-primary truncate">{{ $item->product_name }}</h4>
                                    <p class="text-[11px] text-gray-400 font-mono">Kode: {{ $item->product_code }}</p>
                                </div>
                                <span class="font-bold text-xs text-gray-900 font-mono shrink-0">Rp{{ number_format($item->product_price, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Action & Total -->
                    <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                        <div class="text-xs">
                            <span class="text-gray-400">Total Pembayaran:</span>
                            <span class="font-heading font-extrabold text-base text-primary ml-1 font-mono">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('orders.show', $order->order_number) }}" 
                               class="bg-primary hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded-xl text-xs transition flex items-center space-x-1.5 shadow-sm">
                                <span>Detail & Lacak</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

