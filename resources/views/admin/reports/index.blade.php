@extends('layouts.admin', ['title' => 'Laporan Keuangan & Konsinyasi — Suntrifting', 'headerTitle' => 'Laporan Keuangan & Penjualan'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-gray-900">Laporan Penjualan & Keuangan Konsinyasi</h2>
            <p class="text-xs text-gray-500 mt-0.5">Pantau omzet penjualan, pendapatan komisi admin (10%), dan kewajiban hak supplier (90%).</p>
        </div>

        <!-- Export Buttons -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.reports.export.pdf', request()->query()) }}" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>Ekspor PDF</span>
            </a>
            <a href="{{ route('admin.reports.export.csv', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
                <i data-lucide="sheet" class="w-4 h-4"></i>
                <span>Ekspor Excel/CSV</span>
            </a>
        </div>
    </div>

    <!-- Filter Date & Supplier -->
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Filter Supplier (Opsional)</label>
                <select name="supplier_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ $supplierId == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-2.5 rounded-xl text-xs transition">
                    Terapkan Periode
                </button>
            </div>
        </form>
    </div>

    <!-- Metrics Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block uppercase tracking-wider">Omzet Penjualan Periode</span>
            <div class="font-heading font-extrabold text-2xl text-gray-900 font-mono mt-1">
                Rp{{ number_format($metrics['total_sales'], 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">{{ $metrics['successful_orders_count'] }} transaksi lunas</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-emerald-700 block uppercase tracking-wider">Pendapatan Komisi Admin</span>
            <div class="font-heading font-extrabold text-2xl text-emerald-700 font-mono mt-1">
                Rp{{ number_format($metrics['total_admin_profit'], 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">10% komisi platform</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-700 block uppercase tracking-wider">Total Hak Supplier</span>
            <div class="font-heading font-extrabold text-2xl text-gray-900 font-mono mt-1">
                Rp{{ number_format($metrics['total_supplier_profit'], 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">90% hak mitra supplier</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-purple-700 block uppercase tracking-wider">Dana Sudah Dicairkan</span>
            <div class="font-heading font-extrabold text-2xl text-purple-700 font-mono mt-1">
                Rp{{ number_format($metrics['total_supplier_paid'], 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Telah ditransfer ke rekening</p>
        </div>
    </div>

    <!-- Detailed Sales Transactions Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-heading font-bold text-sm text-gray-900">Rincian Transaksi Penjualan & Pembagian Hasil</h3>
            <span class="text-xs text-gray-500 font-mono">{{ $salesRecords->total() }} data ditemukan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4">No. Order</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Barang Thrift</th>
                        <th class="py-3 px-4">Mitra Supplier</th>
                        <th class="py-3 px-4 text-right">Harga Jual</th>
                        <th class="py-3 px-4 text-right">Komisi Admin (10%)</th>
                        <th class="py-3 px-4 text-right">Hak Supplier (90%)</th>
                        <th class="py-3 px-4 text-center">Status Pencairan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($salesRecords as $row)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3 px-4 font-mono font-bold text-gray-900">
                                {{ $row->order ? $row->order->order_number : '-' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-gray-500">
                                {{ $row->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-900 truncate max-w-xs">
                                {{ $row->product ? $row->product->name : '-' }}
                            </td>
                            <td class="py-3 px-4 text-gray-700">
                                {{ $row->supplier ? $row->supplier->name : '-' }}
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-gray-900 text-right">
                                Rp{{ number_format($row->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 font-mono text-emerald-700 text-right font-semibold">
                                Rp{{ number_format($row->admin_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 font-mono text-gray-900 text-right font-bold">
                                Rp{{ number_format($row->supplier_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($row->payout_status === 'paid')
                                    <span class="bg-purple-100 text-purple-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Sudah Cair</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Belum Cair</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Tidak ada transaksi penjualan pada periode yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $salesRecords->links() }}
        </div>
    </div>
</div>
@endsection

