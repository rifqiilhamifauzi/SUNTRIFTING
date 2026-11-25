@extends('layouts.admin', ['title' => 'Daftar Pesanan Terverifikasi — SUN-THRIFTING', 'headerTitle' => 'Manajemen Pesanan Customer'])

@section('content')
<div class="space-y-6">
    <!-- Sub-navigation Tabs: Pesanan & Verifikasi Pembayaran -->
    <div class="flex items-center space-x-2 border-b border-gray-200 pb-3">
        <a href="{{ route('admin.orders.index') }}" 
           class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold transition bg-primary text-white shadow-sm">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
            <span>Daftar Pesanan (Sudah Bayar)</span>
            <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white">
                {{ $counts['all_verified'] ?? 0 }}
            </span>
        </a>
        <a href="{{ route('admin.payments.index') }}" 
           class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold transition bg-white text-gray-600 hover:bg-gray-100 border border-gray-200">
            <i data-lucide="receipt-text" class="w-4 h-4"></i>
            <span>Verifikasi Pembayaran</span>
            @if(($counts['needs_verification'] ?? 0) > 0)
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-charcoal">
                    {{ $counts['needs_verification'] }} Perlu Cek
                </span>
            @elseif(($counts['unpaid'] ?? 0) > 0)
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                    {{ $counts['unpaid'] }} Belum Bayar
                </span>
            @endif
        </a>
    </div>

    <!-- Header & Info -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <h2 class="font-heading font-bold text-xl text-gray-900">Pesanan Terverifikasi (Lunas)</h2>
                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full inline-flex items-center">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-1 text-emerald-600"></i> Terverifikasi Saja
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-0.5">Daftar transaksi yang pembayarannya telah terverifikasi lunas. Siap untuk dipacking, diinput nomor resi, dan dikirim ke customer.</p>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-cream border border-warm-beige text-charcoal hover:bg-warm-beige/30 transition">
                <i data-lucide="receipt-text" class="w-3.5 h-3.5 text-accent"></i>
                <span>Cek Pembayaran Menunggu</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="relative sm:col-span-6 lg:col-span-7">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Order SUN-..., Nama Penerima, No. HP..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-2.5"></i>
            </div>

            <div class="sm:col-span-4 lg:col-span-3">
                <select name="order_status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Status Pesanan</option>
                    <option value="processing" {{ $orderStatus === 'processing' ? 'selected' : '' }}>Diproses (Perlu Dikemas)</option>
                    <option value="shipped" {{ $orderStatus === 'shipped' ? 'selected' : '' }}>Dikirim (Ada Resi)</option>
                    <option value="completed" {{ $orderStatus === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="cancelled" {{ $orderStatus === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-2 flex items-center space-x-2">
                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-3 py-2 rounded-xl text-xs font-semibold transition">
                    Filter
                </button>
                @if($search || $orderStatus)
                    <a href="{{ route('admin.orders.index') }}" class="p-2 text-gray-400 hover:text-gray-700 rounded-xl hover:bg-gray-100" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">No. Order</th>
                        <th class="py-3.5 px-4">Customer & Penerima</th>
                        <th class="py-3.5 px-4">Barang Dipesan</th>
                        <th class="py-3.5 px-4">Total Tagihan</th>
                        <th class="py-3.5 px-4">Status Pesanan</th>
                        <th class="py-3.5 px-4">Status Bayar</th>
                        <th class="py-3.5 px-4">Tanggal Order</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-gray-900 hover:text-accent">
                                    {{ $order->order_number }}
                                </a>
                                @if($order->shipment && $order->shipment->tracking_number)
                                    <span class="text-[10px] text-gray-400 block font-mono">Resi: {{ $order->shipment->tracking_number }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-900 block">{{ $order->recipient_name }}</span>
                                <span class="text-gray-400 text-[10px]">{{ $order->recipient_phone }} &bull; {{ $order->city }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-gray-800">{{ $order->items->count() }} item</span>
                                <span class="text-[10px] text-gray-400 block truncate max-w-xs">
                                    {{ $order->items->pluck('product_name')->join(', ') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-900 text-sm">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                {!! $order->order_status_badge !!}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full inline-flex items-center">
                                    <i data-lucide="check" class="w-3 h-3 mr-1 text-emerald-600"></i> Lunas (Terverifikasi)
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-gray-600">
                                <div>{{ $order->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</div>
                                <span class="text-[10px] text-accent block font-sans">{{ $order->created_at->timezone('Asia/Jakarta')->diffForHumans() }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center space-x-1 text-accent hover:text-primary font-semibold">
                                    <span>Kelola & Resi</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-cream text-accent flex items-center justify-center mx-auto">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <h4 class="font-heading font-bold text-sm text-gray-900">Belum Ada Pesanan Terverifikasi</h4>
                                    <p class="text-xs text-gray-500">
                                        Halaman ini hanya menampilkan pesanan yang telah lunas diverifikasi. Pesanan baru yang menunggu pembayaran atau verifikasi struk transfer dapat dilihat di menu verifikasi.
                                    </p>
                                    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-primary text-white rounded-xl text-xs font-semibold hover:bg-primary-dark transition shadow-sm">
                                        <i data-lucide="receipt-text" class="w-4 h-4"></i>
                                        <span>Buka Verifikasi Pembayaran</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
