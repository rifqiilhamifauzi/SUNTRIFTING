@extends('layouts.admin', ['title' => 'Pencairan Supplier — Suntrifting', 'headerTitle' => 'Manajemen Pencairan Dana Supplier'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-gray-900">Riwayat Pencairan Dana Mitra Supplier</h2>
            <p class="text-xs text-gray-500 mt-0.5">Catatan transfer dana hasil penjualan konsinyasi beserta nomor referensi dan bukti transfer.</p>
        </div>

        <a href="{{ route('admin.payouts.create') }}" class="bg-accent hover:bg-accent-dark text-white font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Buat Pencairan Dana Baru</span>
        </a>
    </div>

    <!-- Payouts Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">No. Pencairan</th>
                        <th class="py-3.5 px-4">Mitra Supplier</th>
                        <th class="py-3.5 px-4">Nominal Ditransfer</th>
                        <th class="py-3.5 px-4">Tujuan Rekening</th>
                        <th class="py-3.5 px-4">Ref. Transfer Bank</th>
                        <th class="py-3.5 px-4">Diproses Oleh</th>
                        <th class="py-3.5 px-4">Tanggal Cair</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payouts as $payout)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                                <a href="{{ route('admin.payouts.show', $payout->id) }}" class="hover:text-accent">
                                    {{ $payout->payout_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800">
                                {{ $payout->supplier ? $payout->supplier->name : '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-purple-700 text-sm">
                                Rp{{ number_format($payout->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-900">{{ $payout->bank_name }}</span>
                                <span class="font-mono text-gray-600 block">{{ $payout->bank_account_number }}</span>
                                <span class="text-[10px] text-gray-400">a.n {{ $payout->bank_account_holder }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-gray-600">
                                {{ $payout->reference_number ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-500">
                                {{ $payout->processor ? $payout->processor->name : 'Admin' }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-400 font-mono">
                                {{ $payout->paid_at ? $payout->paid_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.payouts.show', $payout->id) }}" class="text-accent hover:underline font-semibold">
                                    Detail Bukti &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Belum ada data pencairan dana supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $payouts->links() }}
        </div>
    </div>
</div>
@endsection

