@extends('layouts.admin', ['title' => 'Verifikasi Pembayaran — SUN-THRIFTING', 'headerTitle' => 'Verifikasi Pembayaran Manual Transfer'])

@section('content')
<div class="space-y-5" x-data="{ rejectModal: false, selectedPaymentId: null, previewModal: false, previewSrc: '' }">
    <!-- Sub-navigation Tabs: Pesanan & Verifikasi Pembayaran -->
    <div class="flex items-center space-x-2 border-b border-gray-200 pb-3">
        <a href="{{ route('admin.orders.index') }}" 
           class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold transition bg-white text-gray-600 hover:bg-gray-100 border border-gray-200">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
            <span>Daftar Pesanan (Sudah Bayar)</span>
            <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                {{ $counts['verified_orders'] ?? 0 }}
            </span>
        </a>
        <a href="{{ route('admin.payments.index') }}" 
           class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold transition bg-primary text-white shadow-sm">
            <i data-lucide="receipt-text" class="w-4 h-4"></i>
            <span>Verifikasi Pembayaran</span>
            @if(($counts['needs_verification'] ?? 0) > 0)
                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-charcoal">
                    {{ $counts['needs_verification'] }} Perlu Cek
                </span>
            @endif
        </a>
    </div>

    <!-- Toolbar: Tabs + Realtime Clock + Search -->
    <div class="bg-white rounded-2xl border border-gray-100 p-3 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Category Filter Pills -->
        <div class="flex items-center space-x-1.5 overflow-x-auto text-xs">
            <!-- 1. Perlu Dicek (Sudah Upload Bukti) -->
            <a href="{{ route('admin.payments.index', ['status' => 'needs_verification', 'search' => $search]) }}" 
               class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition flex items-center space-x-1.5 {{ $status === 'needs_verification' ? 'bg-amber-500 text-charcoal shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                <span>Perlu Dicek</span>
                @if(($counts['needs_verification'] ?? 0) > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $status === 'needs_verification' ? 'bg-charcoal text-white' : 'bg-amber-200 text-amber-900' }}">
                        {{ $counts['needs_verification'] }}
                    </span>
                @endif
            </a>

            <!-- 2. Belum Bayar (Menunggu Transfer) -->
            <a href="{{ route('admin.payments.index', ['status' => 'unpaid', 'search' => $search]) }}" 
               class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition flex items-center space-x-1.5 {{ $status === 'unpaid' ? 'bg-primary text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                <span>Belum Bayar</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $status === 'unpaid' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">
                    {{ $counts['unpaid'] ?? 0 }}
                </span>
            </a>

            <!-- 3. Ditolak -->
            <a href="{{ route('admin.payments.index', ['status' => 'rejected', 'search' => $search]) }}" 
               class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition flex items-center space-x-1.5 {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                <span>Ditolak</span>
                @if(($counts['rejected'] ?? 0) > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $status === 'rejected' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">
                        {{ $counts['rejected'] }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Right: Realtime Clock & Search Box -->
        <div class="flex items-center space-x-3">
            <!-- Realtime Clock Widget -->
            <div class="hidden sm:flex items-center space-x-1.5 px-3 py-1.5 bg-gray-50 rounded-xl border border-gray-100 text-[11px] font-mono text-gray-600 shrink-0" 
                 x-data="{ clock: '' }" 
                 x-init="const updateClock = () => { clock = new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta' }) + ' WIB'; }; updateClock(); setInterval(updateClock, 1000);">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Waktu Realtime: <strong x-text="clock" class="text-gray-900"></strong></span>
            </div>

            <!-- Search Box -->
            <form action="{{ route('admin.payments.index') }}" method="GET" class="flex items-center space-x-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative w-full sm:w-56">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Order / Nama..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-8 pr-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2"></i>
                </div>
                @if($search)
                    <a href="{{ route('admin.payments.index', ['status' => $status]) }}" class="text-xs text-rose-600 hover:underline shrink-0" title="Reset cari">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4">No. Order</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Total Tagihan</th>
                        <th class="py-3 px-4">Rekening Pengirim</th>
                        <th class="py-3 px-4 text-center">Bukti Transfer</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-gray-50/50">
                            <!-- 1. No. Order & Realtime Date/Time -->
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.orders.show', $pay->order->id) }}" class="font-mono font-bold text-gray-900 hover:text-accent">
                                    {{ $pay->order->order_number }}
                                </a>
                                <div class="text-[10px] text-gray-500 font-mono mt-0.5 flex items-center space-x-1">
                                    <i data-lucide="clock" class="w-3 h-3 text-gray-400"></i>
                                    <span>{{ $pay->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</span>
                                </div>
                                <span class="text-[10px] text-accent font-medium mt-0.5 block">
                                    {{ $pay->created_at->timezone('Asia/Jakarta')->diffForHumans() }}
                                </span>
                            </td>

                            <!-- 2. Customer Info (Separated Column) -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900 text-xs">{{ $pay->order->recipient_name }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5 font-mono">{{ $pay->order->recipient_phone }}</div>
                                <div class="text-[10px] text-gray-400">{{ $pay->order->city }}</div>
                            </td>

                            <!-- 3. Total Tagihan -->
                            <td class="py-3.5 px-4">
                                <div class="font-mono font-bold text-gray-900 text-sm">Rp{{ number_format($pay->amount, 0, ',', '.') }}</div>
                            </td>

                            <!-- 4. Rekening Pengirim -->
                            <td class="py-3.5 px-4 text-gray-600">
                                @if($pay->bank_name || $pay->account_name)
                                    <div class="font-semibold text-gray-900">{{ $pay->bank_name ?: '-' }}</div>
                                    <div class="text-[11px] text-gray-600">{{ $pay->account_name ?: '-' }}</div>
                                    <div class="font-mono text-gray-400 text-[10px]">{{ $pay->account_number ?: '-' }}</div>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- 5. Bukti Transfer -->
                            <td class="py-3.5 px-4 text-center">
                                @if($pay->proof_image)
                                    <button type="button" @click="previewSrc = '{{ $pay->proof_url }}'; previewModal = true" 
                                            class="group relative inline-block w-11 h-11 rounded-xl overflow-hidden border border-gray-200 hover:ring-2 hover:ring-accent transition shadow-sm" title="Klik untuk lihat struk transfer">
                                        <img src="{{ $pay->proof_url }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                                            <i data-lucide="zoom-in" class="w-3.5 h-3.5"></i>
                                        </div>
                                    </button>
                                @else
                                    <span class="text-gray-400 text-[11px] bg-gray-50 border border-gray-100 px-2 py-0.5 rounded-md">Belum diunggah</span>
                                @endif
                            </td>

                            <!-- 6. Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                @if($pay->status === 'pending')
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <!-- Tombol Verifikasi -->
                                        <form action="{{ route('admin.payments.verify', $pay->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Verifikasi pembayaran Rp{{ number_format($pay->amount, 0, ',', '.') }} untuk order {{ $pay->order->order_number }}?\n\nPesanan akan otomatis lunas dan BERPINDAH ke Daftar Pesanan Customer.')" 
                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition shadow-sm inline-flex items-center space-x-1">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span>{{ $pay->proof_image ? 'Verifikasi' : 'Verifikasi Manual' }}</span>
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <button type="button" @click="selectedPaymentId = {{ $pay->id }}; rejectModal = true" 
                                                class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold px-2.5 py-1.5 rounded-lg text-xs transition" title="Tolak bukti transfer">
                                            Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-rose-600 text-xs font-semibold">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <div class="w-10 h-10 rounded-full bg-cream text-accent flex items-center justify-center mx-auto">
                                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                                    </div>
                                    <h4 class="font-heading font-bold text-sm text-gray-900">Tidak Ada Pembayaran Perlu Tindakan</h4>
                                    <p class="text-xs text-gray-500">
                                        Semua pesanan yang sudah dibayar telah dipindahkan ke menu Daftar Pesanan.
                                    </p>
                                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center space-x-1.5 text-xs text-accent hover:underline font-semibold pt-1">
                                        <span>Buka Daftar Pesanan Customer</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-3 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- Image Preview Modal -->
    <div x-show="previewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75">
        <div @click.outside="previewModal = false" class="bg-white rounded-2xl max-w-lg w-full p-4 shadow-2xl space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <span class="font-bold text-xs text-gray-900 flex items-center space-x-1.5">
                    <i data-lucide="receipt" class="w-4 h-4 text-accent"></i>
                    <span>Foto Bukti Transfer Bank</span>
                </span>
                <button type="button" @click="previewModal = false" class="text-gray-400 hover:text-gray-700">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
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
                <span class="font-heading font-bold text-sm text-gray-900">Tolak Bukti Pembayaran</span>
                <button type="button" @click="rejectModal = false" class="text-gray-400 hover:text-gray-700">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form :action="'/admin/payments/' + selectedPaymentId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Penolakan Pembayaran *</label>
                    <textarea name="reason" rows="3" required placeholder="Contoh: Mutasi rekening belum masuk / nominal tidak sesuai / foto struk buram..." 
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2">
                    <button type="button" @click="rejectModal = false" class="px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900">Batal</button>
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-4 py-2 rounded-xl text-xs transition">
                        Konfirmasi Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
