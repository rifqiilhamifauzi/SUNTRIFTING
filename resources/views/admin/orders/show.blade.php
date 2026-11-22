@extends('layouts.admin', ['title' => 'Kelola Pesanan ' . $order->order_number . ' — Suntrifting', 'headerTitle' => 'Kelola Pesanan & Resi Pengiriman'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Pesanan
        </a>
        <div class="flex items-center space-x-2">
            {!! $order->order_status_badge !!}
            {!! $order->payment_status_badge !!}
        </div>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">Nomor Pesanan</span>
            <h2 class="font-heading font-extrabold text-2xl text-gray-900">{{ $order->order_number }}</h2>
            <p class="text-xs text-gray-500 mt-0.5">Waktu Checkout: {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

        <div class="text-right">
            <span class="text-[10px] text-gray-400 uppercase tracking-widest block">Total Tagihan</span>
            <span class="font-heading font-extrabold text-2xl text-primary font-mono">
                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Order Items & Profit Share Breakdown (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Items Table -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="shirt" class="w-4 h-4 mr-2 text-accent"></i>
                    Barang Thrift Dipesan & Snapshot Bagi Hasil
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                            <tr>
                                <th class="py-2.5 px-3">Produk</th>
                                <th class="py-2.5 px-3">Supplier</th>
                                <th class="py-2.5 px-3">Harga Jual</th>
                                <th class="py-2.5 px-3">Hak Supplier ({{ $order->items->first()?->supplier_commission_pct ?? 90 }}%)</th>
                                <th class="py-2.5 px-3">Komisi Admin ({{ $order->items->first()?->admin_commission_pct ?? 10 }}%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 px-3 flex items-center space-x-3">
                                        <img src="{{ $item->product ? $item->product->image_url : 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=200' }}" class="w-10 h-10 rounded-lg object-cover bg-gray-50 shrink-0">
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $item->product_name }}</p>
                                            <p class="text-[10px] text-gray-400 font-mono">{{ $item->product_code }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="font-semibold text-gray-800">{{ $item->supplier ? $item->supplier->name : '-' }}</span>
                                    </td>
                                    <td class="py-3 px-3 font-mono font-bold text-gray-900">
                                        Rp{{ number_format($item->product_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3 font-mono font-semibold text-emerald-700">
                                        Rp{{ number_format($item->supplier_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3 font-mono font-semibold text-gray-700">
                                        Rp{{ number_format($item->admin_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-3 border-t border-gray-100 text-xs flex justify-between">
                    <span class="text-gray-500">Ongkir: Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    <span class="font-bold text-gray-900">Total: Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Customer & Shipping Destination -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-accent"></i>
                    Tujuan Pengiriman
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block">Nama Penerima:</span>
                        <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $order->recipient_name }}</p>
                        <p class="text-gray-600">{{ $order->recipient_phone }}</p>
                    </div>

                    <div>
                        <span class="text-gray-400 block">Akun Pemesan:</span>
                        <p class="font-semibold text-gray-900 mt-0.5">{{ $order->user ? $order->user->name : '-' }}</p>
                        <p class="text-gray-500">{{ $order->user ? $order->user->email : '-' }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="text-gray-400 block">Alamat Lengkap:</span>
                        <p class="font-medium text-gray-800 leading-relaxed mt-0.5">
                            {{ $order->shipping_address }}, {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}
                        </p>
                    </div>

                    @if($order->notes)
                        <div class="sm:col-span-2 p-3 bg-gray-50 rounded-xl text-gray-600">
                            <strong>Catatan Pembeli:</strong> {{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Audit Trail / Timeline -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="history" class="w-4 h-4 mr-2 text-accent"></i>
                    Log Audit & Riwayat Perubahan Status
                </h3>

                <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                    @foreach($order->statusHistories as $h)
                        <div class="relative text-xs">
                            <div class="absolute -left-6 top-1 w-2.5 h-2.5 rounded-full bg-accent ring-4 ring-white"></div>
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-gray-900 uppercase">{{ str_replace('_', ' ', $h->new_status) }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ $h->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-gray-600 mt-0.5">{{ $h->notes }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Management Actions & Tracking Forms (4 cols) -->
        <div class="lg:col-span-4 space-y-6 sticky top-24">
            <!-- Shipping & Resi Form -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="truck" class="w-4 h-4 mr-2 text-accent"></i>
                    Input Nomor Resi Pengiriman
                </h3>

                <form action="{{ route('admin.orders.shipping.update', $order->id) }}" method="POST" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kurir Pengiriman *</label>
                        <input type="text" name="courier" required value="{{ old('courier', $order->shipment?->courier ?? 'JNE Regular') }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Resi *</label>
                        <input type="text" name="tracking_number" required value="{{ old('tracking_number', $order->shipment?->tracking_number) }}" placeholder="Contoh: JNE829102837192" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Pengiriman (Opsional)</label>
                        <input type="text" name="notes" value="{{ old('notes', $order->shipment?->notes) }}" placeholder="Keterangan kurir" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>

                    <button type="submit" class="w-full bg-accent hover:bg-accent-dark text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-sm flex items-center justify-center space-x-1.5">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                        <span>Simpan Resi & Set Shipped</span>
                    </button>
                </form>
            </div>

            <!-- Update Order Status Form -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100 flex items-center">
                    <i data-lucide="edit-3" class="w-4 h-4 mr-2 text-accent"></i>
                    Ubah Status Pesanan
                </h3>

                <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Status *</label>
                        <select name="order_status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            <option value="pending_payment" {{ $order->order_status === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                            <option value="waiting_verification" {{ $order->order_status === 'waiting_verification' ? 'selected' : '' }}>Waiting Verification</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing (Packing)</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped (Dikirim)</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                            <option value="refunded" {{ $order->order_status === 'refunded' ? 'selected' : '' }}>Refunded (Retur)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Perubahan (Opsional)</label>
                        <input type="text" name="notes" placeholder="Alasan perubahan status..." 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-sm">
                        Update Status Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

