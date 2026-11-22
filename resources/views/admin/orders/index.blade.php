@extends('layouts.admin', ['title' => 'Manajemen Pesanan — Suntrifting', 'headerTitle' => 'Manajemen Pesanan Customer'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-gray-900">Daftar Transaksi Pesanan</h2>
            <p class="text-xs text-gray-500 mt-0.5">Pantau alur pesanan dari checkout, pembayaran, packing, hingga pengiriman barang.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative lg:col-span-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Order SUN-..., Nama Penerima, HP..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-2.5"></i>
            </div>

            <div>
                <select name="order_status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Status Order</option>
                    <option value="pending_payment" {{ $orderStatus === 'pending_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="waiting_verification" {{ $orderStatus === 'waiting_verification' ? 'selected' : '' }}>Verifikasi Pembayaran</option>
                    <option value="processing" {{ $orderStatus === 'processing' ? 'selected' : '' }}>Diproses (Packing)</option>
                    <option value="shipped" {{ $orderStatus === 'shipped' ? 'selected' : '' }}>Dikirim (Shipped)</option>
                    <option value="completed" {{ $orderStatus === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="cancelled" {{ $orderStatus === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <select name="payment_status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Status Bayar</option>
                    <option value="unpaid" {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="pending_verification" {{ $paymentStatus === 'pending_verification' ? 'selected' : '' }}>Menunggu Cek Admin</option>
                    <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Lunas (Verified)</option>
                    <option value="rejected" {{ $paymentStatus === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-xl text-xs font-semibold">Filter</button>
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
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-900">{{ $order->order_number }}</td>
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
                            <td class="py-3.5 px-4">{!! $order->order_status_badge !!}</td>
                            <td class="py-3.5 px-4">{!! $order->payment_status_badge !!}</td>
                            <td class="py-3.5 px-4 text-gray-400 font-mono">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-accent hover:underline font-semibold">
                                    Kelola & Resi &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Belum ada data pesanan yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

