@extends('layouts.app', ['title' => 'Detail Pesanan ' . $order->order_number . ' — SUN-THRIFTING'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-gray-500 hover:text-primary flex items-center transition">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Pesanan
        </a>
        <div class="flex items-center space-x-2">
            {!! $order->order_status_badge !!}
            {!! $order->payment_status_badge !!}
        </div>
    </div>

    <!-- Order Summary Banner -->
    <div class="bg-primary text-secondary rounded-3xl p-6 sm:p-8 mb-8 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="text-xs font-mono text-accent uppercase tracking-wider block">ID Pesanan</span>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-white">{{ $order->order_number }}</h1>
                <p class="text-xs text-gray-400 mt-1">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-gray-400 block">Total Tagihan</span>
                <span class="font-heading font-extrabold text-2xl sm:text-3xl text-accent font-mono">
                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Column: Items, Shipment & Timeline (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Items Purchased Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-primary pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="shopping-bag" class="w-4 h-4 mr-2 text-accent"></i>
                    Daftar Barang Thrift Dipesan
                </h3>

                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center space-x-4">
                            <img src="{{ $item->product ? $item->product->image_url : 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=200' }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="w-16 h-16 rounded-xl object-cover bg-gray-50 border border-gray-100 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-heading font-semibold text-xs sm:text-sm text-primary truncate">{{ $item->product_name }}</h4>
                                <p class="text-[11px] text-gray-400 font-mono mt-0.5">Kode: {{ $item->product_code }}</p>
                                <p class="text-[11px] text-accent mt-0.5">Konsinyor: {{ $item->supplier ? $item->supplier->name : 'Mitra SUN-THRIFTING' }}</p>
                            </div>
                            <span class="font-bold text-xs sm:text-sm text-gray-900 font-mono shrink-0">
                                Rp{{ number_format($item->product_price, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipment & Tracking Info -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-primary pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="truck" class="w-4 h-4 mr-2 text-accent"></i>
                    Informasi Pengiriman & Resi
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block">Penerima & Kontak:</span>
                        <p class="font-semibold text-gray-800 mt-0.5">{{ $order->recipient_name }} ({{ $order->recipient_phone }})</p>
                    </div>

                    <div>
                        <span class="text-gray-400 block">Kurir Pengiriman:</span>
                        <p class="font-semibold text-gray-800 mt-0.5">{{ $order->shipment ? $order->shipment->courier : 'JNE Regular' }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="text-gray-400 block">Alamat Tujuan:</span>
                        <p class="font-semibold text-gray-800 mt-0.5 leading-relaxed">
                            {{ $order->shipping_address }}, {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}
                        </p>
                    </div>

                    @if($order->shipment && $order->shipment->tracking_number)
                        <div class="sm:col-span-2 p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800">Nomor Resi Pengiriman:</span>
                            <div class="flex items-center justify-between mt-1">
                                <span class="font-mono font-extrabold text-base text-emerald-950">{{ $order->shipment->tracking_number }}</span>
                                <span class="text-xs bg-emerald-600 text-white font-semibold px-2.5 py-0.5 rounded-full">Paket Sedang Dikirim</span>
                            </div>
                            @if($order->order_status === 'shipped')
                                <form action="{{ route('orders.confirm-delivery', $order->order_number) }}" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah pesanan ini sudah sampai dan diterima dengan baik?')" 
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-sm">
                                        Konfirmasi Paket Diterima (Selesaikan Pesanan)
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status History Timeline -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-primary pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="clock" class="w-4 h-4 mr-2 text-accent"></i>
                    Riwayat Aktivitas Pesanan
                </h3>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                    @foreach($order->statusHistories as $history)
                        <div class="relative">
                            <div class="absolute -left-6 top-1 w-2.5 h-2.5 rounded-full bg-accent ring-4 ring-white"></div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 uppercase tracking-wide">{{ str_replace('_', ' ', $history->new_status) }}</span>
                                <span class="text-[10px] text-gray-400 ml-2 font-mono">{{ $history->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                <p class="text-gray-600 mt-1 leading-relaxed">{{ $history->notes }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Payment & Proof Upload (4 cols) -->
        <div class="lg:col-span-4 space-y-6 sticky top-24">
            <!-- Payment Instructions Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-primary pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="credit-card" class="w-4 h-4 mr-2 text-accent"></i>
                    Instruksi Pembayaran
                </h3>

                @if($order->payment_status === 'paid')
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-center text-xs text-emerald-800">
                        <i data-lucide="check-circle" class="w-8 h-8 text-emerald-600 mx-auto mb-2"></i>
                        <span class="font-bold block text-sm">Pembayaran Telah Diverifikasi</span>
                        <p class="mt-1 text-emerald-700">Pesanan Anda telah lunas dan sedang dipersiapkan oleh tim konsinyasi kami.</p>
                    </div>
                @else
                    <div class="space-y-3 text-xs">
                        <p class="text-gray-600">Silakan transfer tepat sebesar total tagihan ke salah satu rekening resmi platform kami:</p>
                        
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 space-y-1">
                            <div class="font-bold text-gray-900">Bank Central Asia (BCA)</div>
                            <div class="font-mono text-sm font-extrabold text-primary">{{ $bankBca['number'] }}</div>
                            <div class="text-[11px] text-gray-500">a.n {{ $bankBca['holder'] }}</div>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 space-y-1">
                            <div class="font-bold text-gray-900">Bank Mandiri</div>
                            <div class="font-mono text-sm font-extrabold text-primary">{{ $bankMandiri['number'] }}</div>
                            <div class="text-[11px] text-gray-500">a.n {{ $bankMandiri['holder'] }}</div>
                        </div>

                        <div class="pt-2 border-t border-gray-100 text-[11px] text-gray-500">
                            <strong>Penting:</strong> Setelah transfer, silakan upload bukti foto struk/screenshot transfer Anda pada formulir di bawah ini.
                        </div>
                    </div>

                    <!-- Upload Proof Form -->
                    <div class="pt-4 border-t border-gray-100">
                        <h4 class="font-heading font-bold text-xs text-primary mb-3">Upload Bukti Transfer</h4>

                        @if($order->latestPayment && $order->latestPayment->proof_image)
                            <div class="mb-4">
                                <span class="text-[11px] text-gray-400 block mb-1">Bukti Transfer Terakhir Diunggah:</span>
                                <div class="relative rounded-2xl overflow-hidden border border-gray-200 max-h-48 bg-gray-50">
                                    <img src="{{ $order->latestPayment->proof_url }}" alt="Bukti Transfer" class="w-full object-contain">
                                </div>
                                <span class="inline-block mt-2 text-[10px] bg-sky-100 text-sky-800 font-semibold px-2 py-0.5 rounded">
                                    Status Bukti: {{ ucfirst($order->latestPayment->status) }}
                                </span>
                            </div>
                        @endif

                        <form action="{{ route('orders.upload-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="space-y-3" x-data="{ preview: null }">
                            @csrf

                            <div>
                                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Pilih Foto Bukti Transfer *</label>
                                <input type="file" name="proof_image" required accept="image/*" 
                                       @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(file); }"
                                       class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-gray-800 cursor-pointer">
                            </div>

                            <!-- Image Preview before saving -->
                            <div x-show="preview" x-cloak class="mt-2">
                                <span class="text-[10px] text-gray-400 block mb-1">Preview Foto:</span>
                                <img :src="preview" class="w-full max-h-40 object-contain rounded-xl border border-gray-200 bg-gray-50">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-700 mb-1">Bank Pengirim *</label>
                                    <input type="text" name="bank_name" placeholder="BCA / Mandiri..." required value="{{ old('bank_name') }}"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nama Pemilik *</label>
                                    <input type="text" name="account_name" placeholder="Nama di rekening" required value="{{ old('account_name', Auth::user()->name) }}"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nomor Rekening Pengirim *</label>
                                <input type="text" name="account_number" placeholder="Nomor rekening Anda" required value="{{ old('account_number') }}"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            </div>

                            <button type="submit" class="w-full bg-accent hover:bg-accent-dark text-white font-semibold py-3 px-4 rounded-xl text-xs transition shadow-sm flex items-center justify-center space-x-1.5">
                                <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                <span>Kirim Bukti Pembayaran</span>
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Cancel Order Action (if unpaid) -->
                @if($order->order_status === 'pending_payment')
                    <div class="pt-4 border-t border-gray-100">
                        <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')" 
                                    class="w-full bg-gray-100 hover:bg-rose-50 hover:text-rose-600 text-gray-600 font-semibold py-2 px-4 rounded-xl text-xs transition">
                                Batalkan Pesanan Ini
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

