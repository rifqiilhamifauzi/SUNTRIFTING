@extends('layouts.admin', ['title' => 'Verifikasi Pembayaran — Suntrifting', 'headerTitle' => 'Verifikasi Pembayaran Manual Transfer'])

@section('content')
<div class="space-y-6" x-data="{ rejectModal: false, selectedPaymentId: null, previewModal: false, previewSrc: '' }">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-gray-900">Verifikasi Bukti Transfer Bank</h2>
            <p class="text-xs text-gray-500 mt-0.5">Cek mutasi bank dan validasi bukti transfer customer untuk mengaktifkan pembagian hasil konsinyasi.</p>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'pending' ? 'bg-amber-500 text-black shadow-sm font-bold' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Menunggu Verifikasi (Pending)
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'verified']) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'verified' ? 'bg-emerald-600 text-white shadow-sm font-bold' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Sudah Terverifikasi (Paid)
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm font-bold' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Ditolak
            </a>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">No. Order</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Nominal Ditransfer</th>
                        <th class="py-3.5 px-4">Rekening Pengirim</th>
                        <th class="py-3.5 px-4 text-center">Foto Bukti</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.orders.show', $pay->order->id) }}" class="font-mono font-bold text-gray-900 hover:text-accent">
                                    {{ $pay->order->order_number }}
                                </a>
                                <span class="text-[10px] text-gray-400 block font-mono">{{ $pay->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800">
                                {{ $pay->order->recipient_name }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-900 text-sm">
                                Rp{{ number_format($pay->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-900">{{ $pay->bank_name ?: '-' }}</span>
                                <span class="text-gray-500 block">{{ $pay->account_name ?: '-' }}</span>
                                <span class="font-mono text-gray-400 text-[10px]">{{ $pay->account_number ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($pay->proof_image)
                                    <button type="button" @click="previewSrc = '{{ $pay->proof_url }}'; previewModal = true" 
                                            class="inline-block w-12 h-12 rounded-xl overflow-hidden border border-gray-200 hover:ring-2 hover:ring-accent transition shadow-sm">
                                        <img src="{{ $pay->proof_url }}" class="w-full h-full object-cover">
                                    </button>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">Belum diunggah</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($pay->status === 'verified')
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Terverifikasi</span>
                                @elseif($pay->status === 'rejected')
                                    <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Ditolak</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Menunggu Cek</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                @if($pay->status === 'pending')
                                    <form action="{{ route('admin.payments.verify', $pay->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah uang pembayaran Rp{{ number_format($pay->amount, 0, \', \', \'.\') }} sudah valid masuk di rekening bank Anda?')" 
                                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition shadow-sm">
                                            Terima / Verifikasi
                                        </button>
                                    </form>

                                    <button type="button" @click="selectedPaymentId = {{ $pay->id }}; rejectModal = true" 
                                            class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold px-3 py-1.5 rounded-lg text-xs transition">
                                        Tolak
                                    </button>
                                @else
                                    <span class="text-gray-400 text-[11px]">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Tidak ada data pembayaran dengan status ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div x-show="previewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75">
        <div @click.outside="previewModal = false" class="bg-white rounded-2xl max-w-lg w-full p-4 shadow-2xl space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <span class="font-bold text-xs text-gray-900">Foto Bukti Transfer Bank</span>
                <button type="button" @click="previewModal = false" class="text-gray-400 hover:text-gray-700"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
            <div class="rounded-xl overflow-hidden bg-gray-100 max-h-[70vh] flex items-center justify-center">
                <img :src="previewSrc" class="max-h-[70vh] object-contain">
            </div>
        </div>
    </div>

    <!-- Reject Reason Modal -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div @click.outside="rejectModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <span class="font-heading font-bold text-sm text-gray-900">Tolak Bukti Transfer</span>
                <button type="button" @click="rejectModal = false" class="text-gray-400 hover:text-gray-700"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>

            <form :action="'/admin/payments/' + selectedPaymentId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Penolakan Pembayaran *</label>
                    <textarea name="reason" rows="3" required placeholder="Contoh: Mutasi rekening belum masuk / nominal tidak sesuai / foto struk buram..." 
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2">
                    <button type="button" @click="rejectModal = false" class="px-3 py-1.5 text-xs text-gray-600">Batal</button>
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-4 py-2 rounded-xl text-xs transition">
                        Konfirmasi Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

