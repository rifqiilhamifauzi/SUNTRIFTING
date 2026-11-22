@extends('layouts.admin', ['title' => 'Detail Pencairan ' . $payout->payout_number . ' — Suntrifting', 'headerTitle' => 'Detail Transaksi Pencairan Dana'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.payouts.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Pencairan
        </a>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $payout->status === 'paid' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800' }}">
            Status: {{ ucfirst($payout->status) }}
        </span>
    </div>

    <!-- Payout Summary Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-6">
        <div class="flex flex-wrap items-center justify-between pb-4 border-b border-gray-100 gap-4">
            <div>
                <span class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">Nomor Transaksi</span>
                <h2 class="font-heading font-extrabold text-2xl text-gray-900">{{ $payout->payout_number }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">Dicairkan pada: {{ $payout->paid_at ? $payout->paid_at->translatedFormat('d F Y, H:i') : '-' }} WIB</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-gray-400 uppercase tracking-widest block">Nominal Dana Dicairkan</span>
                <span class="font-heading font-extrabold text-2xl text-purple-700 font-mono">
                    Rp{{ number_format($payout->amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div>
                <span class="text-gray-400 block">Mitra Supplier Penerima:</span>
                <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $payout->supplier ? $payout->supplier->name : '-' }}</p>
                <p class="text-gray-500 mt-1">Rekening Tujuan: {{ $payout->bank_name }} - {{ $payout->bank_account_number }}</p>
                <p class="text-gray-500">Atas Nama: {{ $payout->bank_account_holder }}</p>
            </div>

            <div>
                <span class="text-gray-400 block">Informasi Validasi Transfer:</span>
                <p class="text-gray-700 mt-0.5">No. Referensi Bank: <strong class="font-mono text-gray-900">{{ $payout->reference_number ?: '-' }}</strong></p>
                <p class="text-gray-700">Diproses Oleh Admin: <strong>{{ $payout->processor ? $payout->processor->name : 'Admin' }}</strong></p>
                @if($payout->notes)
                    <p class="text-gray-500 mt-1 italic">Catatan: {{ $payout->notes }}</p>
                @endif
            </div>
        </div>

        <!-- Transfer Receipt Proof Image -->
        @if($payout->proof_image)
            <div class="pt-4 border-t border-gray-100">
                <span class="text-xs font-bold text-gray-900 block mb-2">Foto Struk / Bukti Transfer Bank:</span>
                <div class="max-w-md rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 p-2">
                    <img src="{{ $payout->proof_url }}" class="w-full object-contain max-h-96 rounded-xl">
                </div>
            </div>
        @endif

        <!-- Profit Share Items Covered -->
        <div class="pt-4 border-t border-gray-100 space-y-3">
            <h3 class="font-heading font-bold text-xs text-gray-900 uppercase tracking-wider">Item Transaksi Penjualan yang Dicairkan</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-100">
                        <tr>
                            <th class="py-2.5 px-3">Produk</th>
                            <th class="py-2.5 px-3">No. Order Asal</th>
                            <th class="py-2.5 px-3 font-mono text-right">Nominal Hak Dicairkan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($payout->items as $item)
                            <tr>
                                <td class="py-2.5 px-3 font-medium text-gray-900">
                                    {{ $item->profitShare && $item->profitShare->product ? $item->profitShare->product->name : 'Item Konsinyasi' }}
                                </td>
                                <td class="py-2.5 px-3 font-mono text-gray-500">
                                    {{ $item->profitShare && $item->profitShare->order ? $item->profitShare->order->order_number : '-' }}
                                </td>
                                <td class="py-2.5 px-3 font-mono font-bold text-purple-700 text-right">
                                    Rp{{ number_format($item->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-400">Pencairan saldo komprehensif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

