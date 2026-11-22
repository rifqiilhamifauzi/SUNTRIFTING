@extends('layouts.admin', ['title' => 'Detail Supplier: ' . $supplier->name . ' — Suntrifting', 'headerTitle' => 'Detail Mitra Supplier'])

@section('content')
<div class="space-y-6" x-data="{ tab: 'products' }">
    <!-- Header with Quick Action -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h2 class="font-heading font-extrabold text-2xl text-gray-900">{{ $supplier->name }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $supplier->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($supplier->status) }}
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-0.5">Bergabung sejak {{ $supplier->created_at->translatedFormat('d F Y') }}</p>
        </div>

        <div class="flex items-center space-x-2">
            @if($supplier->balance > 0)
                <a href="{{ route('admin.payouts.create', ['supplier_id' => $supplier->id]) }}" 
                   class="bg-accent hover:bg-accent-dark text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    <span>Cairkan Saldo (Rp{{ number_format($supplier->balance, 0, ',', '.') }})</span>
                </a>
            @endif
            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-3 py-2 rounded-xl text-xs transition">
                Edit Data
            </a>
        </div>
    </div>

    <!-- Financial & Consignment Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block uppercase tracking-wider">Saldo Siap Dicairkan</span>
            <div class="font-heading font-extrabold text-2xl text-emerald-700 font-mono mt-1">
                Rp{{ number_format($supplier->balance, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Belum ditransfer ke supplier</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block uppercase tracking-wider">Total Hak Supplier (90%)</span>
            <div class="font-heading font-extrabold text-2xl text-gray-900 font-mono mt-1">
                Rp{{ number_format($supplier->total_earnings, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Dari seluruh penjualan berhasil</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block uppercase tracking-wider">Total Dana Telah Ditransfer</span>
            <div class="font-heading font-extrabold text-2xl text-purple-700 font-mono mt-1">
                Rp{{ number_format($supplier->total_paid, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-gray-400 mt-1">{{ $supplier->payouts()->where('status', 'paid')->count() }} transaksi pencairan</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block uppercase tracking-wider">Stok Produk Konsinyasi</span>
            <div class="font-heading font-extrabold text-2xl text-gray-900 mt-1">
                {{ $supplier->sold_products_count }} <span class="text-sm font-normal text-gray-400">/ {{ $supplier->products->count() }} terjual</span>
            </div>
            <p class="text-[11px] text-emerald-600 mt-1">{{ $supplier->products()->where('status', 'available')->count() }} item masih ready</p>
        </div>
    </div>

    <!-- Supplier Details Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100 mb-4">Informasi Rekening & Alamat</h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
            <div>
                <span class="text-gray-400 block">Bank Transfer:</span>
                <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $supplier->bank_name }}</p>
                <p class="font-mono text-gray-700 font-semibold">{{ $supplier->bank_account_number }}</p>
                <p class="text-gray-500">a.n {{ $supplier->bank_account_holder }}</p>
            </div>

            <div>
                <span class="text-gray-400 block">Kontak Supplier:</span>
                <p class="font-semibold text-gray-900 mt-0.5">{{ $supplier->phone }}</p>
                <p class="text-gray-500">{{ $supplier->email ?: '-' }}</p>
            </div>

            <div>
                <span class="text-gray-400 block">Alamat Workshop / Toko:</span>
                <p class="font-medium text-gray-700 mt-0.5 leading-relaxed">{{ $supplier->address }}</p>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex items-center space-x-2 border-b border-gray-200">
        <button @click="tab = 'products'" 
                :class="tab === 'products' ? 'border-primary text-primary font-bold' : 'border-transparent text-gray-400 hover:text-gray-700'"
                class="py-3 px-4 border-b-2 text-xs transition">
            Produk Dititipkan ({{ $supplier->products->count() }})
        </button>
        <button @click="tab = 'sales'" 
                :class="tab === 'sales' ? 'border-primary text-primary font-bold' : 'border-transparent text-gray-400 hover:text-gray-700'"
                class="py-3 px-4 border-b-2 text-xs transition">
            Riwayat Penjualan ({{ $profitShares->total() }})
        </button>
        <button @click="tab = 'payouts'" 
                :class="tab === 'payouts' ? 'border-primary text-primary font-bold' : 'border-transparent text-gray-400 hover:text-gray-700'"
                class="py-3 px-4 border-b-2 text-xs transition">
            Riwayat Pencairan Dana ({{ $supplier->payouts->count() }})
        </button>
    </div>

    <!-- Tab 1: Products List -->
    <div x-show="tab === 'products'" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Kondisi & Ukuran</th>
                        <th class="py-3 px-4">Harga Kesepakatan</th>
                        <th class="py-3 px-4">Harga Jual</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($supplier->products as $prod)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3 px-4 flex items-center space-x-3">
                                <img src="{{ $prod->image_url }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-gray-50 border border-gray-100 shrink-0">
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 truncate">{{ $prod->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono">{{ $prod->code }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="block font-medium">{{ $prod->condition_label }}</span>
                                <span class="text-gray-400 text-[10px]">{{ $prod->size ?: '-' }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono">Rp{{ number_format($prod->agreed_price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-gray-900">Rp{{ number_format($prod->selling_price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">{!! $prod->status_badge !!}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.products.edit', $prod->id) }}" class="text-accent hover:underline font-semibold">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Belum ada produk yang dititipkan oleh supplier ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab 2: Profit Shares / Sales -->
    <div x-show="tab === 'sales'" x-cloak class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4">No. Order</th>
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Harga Terjual</th>
                        <th class="py-3 px-4">Hak Supplier (90%)</th>
                        <th class="py-3 px-4">Status Pencairan</th>
                        <th class="py-3 px-4">Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($profitShares as $share)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3 px-4 font-mono font-bold text-gray-900">{{ $share->order ? $share->order->order_number : '-' }}</td>
                            <td class="py-3 px-4">{{ $share->product ? $share->product->name : '-' }}</td>
                            <td class="py-3 px-4 font-mono">Rp{{ number_format($share->selling_price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-emerald-700">Rp{{ number_format($share->supplier_amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $share->payout_status === 'paid' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $share->payout_status === 'paid' ? 'Sudah Dicairkan' : 'Belum Dicairkan' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-400 font-mono">{{ $share->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Belum ada barang milik supplier ini yang berhasil terjual.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $profitShares->links() }}
        </div>
    </div>

    <!-- Tab 3: Payouts History -->
    <div x-show="tab === 'payouts'" x-cloak class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4">No. Pencairan</th>
                        <th class="py-3 px-4">Nominal Ditransfer</th>
                        <th class="py-3 px-4">Tujuan Transfer</th>
                        <th class="py-3 px-4">No. Referensi Bank</th>
                        <th class="py-3 px-4">Tanggal Cair</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($supplier->payouts as $payout)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3 px-4 font-mono font-bold text-gray-900">{{ $payout->payout_number }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-purple-700 text-sm">Rp{{ number_format($payout->amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">{{ $payout->bank_name }} - {{ $payout->bank_account_number }}</td>
                            <td class="py-3 px-4 font-mono text-gray-500">{{ $payout->reference_number ?: '-' }}</td>
                            <td class="py-3 px-4 text-gray-400 font-mono">{{ $payout->paid_at ? $payout->paid_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.payouts.show', $payout->id) }}" class="text-accent hover:underline font-semibold">Bukti Transfer</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Belum ada riwayat pencairan dana kepada supplier ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

