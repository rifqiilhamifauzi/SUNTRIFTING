@extends('layouts.admin', ['title' => 'Profit Sharing — Suntrifting', 'headerTitle' => 'Pembagian Hasil Konsinyasi'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-gray-900">Buku Besar Pembagian Hasil (Revenue Sharing)</h2>
            <p class="text-xs text-gray-500 mt-0.5">Catatan akuntansi pembagian 90% hak supplier dan 10% komisi admin untuk setiap produk thrift yang terjual.</p>
        </div>

        <a href="{{ route('admin.payouts.create') }}" class="bg-accent hover:bg-accent-dark text-white font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
            <i data-lucide="wallet" class="w-4 h-4"></i>
            <span>Proses Pencairan ke Rekening Supplier</span>
        </a>
    </div>

    <!-- Financial Breakdown Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block uppercase tracking-wider">Total Nilai Penjualan</span>
            <div class="font-heading font-extrabold text-2xl text-gray-900 font-mono mt-1">
                Rp{{ number_format($totalSales, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Total harga jual barang terjual</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-emerald-700 block uppercase tracking-wider">Keuntungan Admin (10%)</span>
            <div class="font-heading font-extrabold text-2xl text-emerald-700 font-mono mt-1">
                Rp{{ number_format($totalAdminProfit, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Pendapatan bersih platform</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-700 block uppercase tracking-wider">Total Hak Supplier (90%)</span>
            <div class="font-heading font-extrabold text-2xl text-gray-900 font-mono mt-1">
                Rp{{ number_format($totalSupplierProfit, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Hak seluruh mitra supplier</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-amber-700 block uppercase tracking-wider">Saldo Belum Dicairkan</span>
            <div class="font-heading font-extrabold text-2xl text-amber-700 font-mono mt-1">
                Rp{{ number_format($unpaidSupplierProfit, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Kewajiban transfer yang tertunda</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form action="{{ route('admin.profit-sharing.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <select name="supplier_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Mitra Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ $supplierId == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="payout_status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Status Pencairan</option>
                    <option value="unpaid" {{ $payoutStatus === 'unpaid' ? 'selected' : '' }}>Belum Dicairkan (Unpaid)</option>
                    <option value="paid" {{ $payoutStatus === 'paid' ? 'selected' : '' }}>Sudah Dicairkan (Paid)</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="bg-primary hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-semibold">Terapkan Filter</button>
                <a href="{{ route('admin.profit-sharing.index') }}" class="text-xs text-gray-500 hover:underline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Profit Sharing Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">No. Order</th>
                        <th class="py-3.5 px-4">Produk</th>
                        <th class="py-3.5 px-4">Supplier</th>
                        <th class="py-3.5 px-4">Harga Jual</th>
                        <th class="py-3.5 px-4">Hak Supplier ({{ $shares->first()?->supplier_commission_pct ?? 90 }}%)</th>
                        <th class="py-3.5 px-4">Keuntungan Admin ({{ $shares->first()?->admin_commission_pct ?? 10 }}%)</th>
                        <th class="py-3.5 px-4 text-center">Status Pencairan</th>
                        <th class="py-3.5 px-4">Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($shares as $share)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                                <a href="{{ route('admin.orders.show', $share->order_id) }}" class="hover:text-accent">
                                    {{ $share->order ? $share->order->order_number : '-' }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-900 block truncate max-w-xs">{{ $share->product ? $share->product->name : '-' }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ $share->product ? $share->product->code : '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800">
                                {{ $share->supplier ? $share->supplier->name : '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-900 text-sm">
                                Rp{{ number_format($share->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-emerald-700 text-sm">
                                Rp{{ number_format($share->supplier_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-semibold text-gray-700">
                                Rp{{ number_format($share->admin_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($share->payout_status === 'paid')
                                    <span class="bg-purple-100 text-purple-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Sudah Ditransfer</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Belum Dicairkan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-gray-400 font-mono">
                                {{ $share->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Belum ada catatan profit sharing. Pembagian hasil otomatis dibuat saat pembayaran pesanan diverifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $shares->links() }}
        </div>
    </div>
</div>
@endsection

