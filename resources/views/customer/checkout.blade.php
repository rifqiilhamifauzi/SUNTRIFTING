@extends('layouts.app', ['title' => 'Checkout — SUN-THRIFTING'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12" 
     x-data="{
        subtotal: {{ (float) $cart->subtotal }},
        shippingRates: {{ json_encode($shippingRates->pluck('cost', 'province')->all()) }},
        selectedProvince: '{{ $defaultAddress ? $defaultAddress->province : ($shippingRates->first()?->province ?? 'DKI Jakarta') }}',
        shippingCost: 0,
        init() {
            this.updateShipping();
        },
        updateShipping() {
            this.shippingCost = this.shippingRates[this.selectedProvince] ? parseFloat(this.shippingRates[this.selectedProvince]) : 20000;
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(num);
        }
     }">

    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-primary">Checkout Pesanan</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Lengkapi alamat pengiriman dan pilih metode pembayaran Anda.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf

        <!-- Shipping & Payment Form (Left 8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Delivery Address Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-accent/10 text-accent flex items-center justify-center">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-sm text-primary">Informasi Penerima & Pengiriman</h3>
                        <p class="text-[11px] text-gray-400">Pastikan nomor telepon dan alamat sudah akurat</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Penerima *</label>
                        <input type="text" name="recipient_name" required 
                               value="{{ old('recipient_name', $defaultAddress?->recipient_name ?? Auth::user()->name) }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor WhatsApp / Telepon *</label>
                        <input type="text" name="recipient_phone" required 
                               value="{{ old('recipient_phone', $defaultAddress?->phone ?? Auth::user()->phone) }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Lengkap (Jalan, RT/RW, No. Rumah, Patokan) *</label>
                    <textarea name="shipping_address" rows="2" required 
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">{{ old('shipping_address', $defaultAddress?->address_line) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Provinsi (Kalkulasi Ongkir) *</label>
                        <select name="province" required x-model="selectedProvince" @change="updateShipping()"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            @foreach($shippingRates as $rate)
                                <option value="{{ $rate->province }}">{{ $rate->province }} (Rp{{ number_format($rate->cost, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kota / Kabupaten *</label>
                        <input type="text" name="city" required 
                               value="{{ old('city', $defaultAddress?->city ?? 'Jakarta') }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Pos *</label>
                        <input type="text" name="postal_code" required 
                               value="{{ old('postal_code', $defaultAddress?->postal_code ?? '10110') }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan untuk Pengirim (Opsional)</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Titipkan ke pos satpam jika tidak ada orang" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">
                </div>

                @if(!$defaultAddress)
                    <div class="pt-2">
                        <label class="flex items-center text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" name="save_address" value="1" checked class="rounded border-gray-300 text-accent focus:ring-accent mr-2">
                            <span>Simpan alamat ini ke buku alamat akun saya</span>
                        </label>
                    </div>
                @endif
            </div>

            <!-- Payment Method Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4" x-data="{ method: 'manual_bank_transfer' }">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-accent/10 text-accent flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-sm text-primary">Pilihan Metode Pembayaran</h3>
                        <p class="text-[11px] text-gray-400">Pilih metode transfer manual atau gateway</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Manual Bank Transfer (MVP Core) -->
                    <label class="flex items-start p-4 rounded-2xl border transition cursor-pointer"
                           :class="method === 'manual_bank_transfer' ? 'border-accent bg-accent/5' : 'border-gray-200 bg-white'">
                        <input type="radio" name="payment_method" value="manual_bank_transfer" x-model="method" class="mt-1 text-accent focus:ring-accent">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-heading font-bold text-xs text-primary">Transfer Bank Manual (BCA / Mandiri)</span>
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded">Rekomendasi MVP</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5">Transfer ke rekening resmi platform dan upload bukti transfer setelah checkout. Diverifikasi cepat oleh admin.</p>
                            
                            <!-- Bank Accounts Preview -->
                            <div x-show="method === 'manual_bank_transfer'" class="mt-3 pt-3 border-t border-accent/20 grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                                <div class="p-2.5 bg-white rounded-xl border border-gray-200">
                                    <div class="font-bold text-gray-800">Bank BCA</div>
                                    <div class="font-mono text-xs text-primary font-semibold">{{ $bankBca['number'] }}</div>
                                    <div class="text-[10px] text-gray-500">a.n {{ $bankBca['holder'] }}</div>
                                </div>
                                <div class="p-2.5 bg-white rounded-xl border border-gray-200">
                                    <div class="font-bold text-gray-800">Bank Mandiri</div>
                                    <div class="font-mono text-xs text-primary font-semibold">{{ $bankMandiri['number'] }}</div>
                                    <div class="text-[10px] text-gray-500">a.n {{ $bankMandiri['holder'] }}</div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Midtrans Payment Gateway (Optional feature) -->
                    <label class="flex items-start p-4 rounded-2xl border transition cursor-pointer"
                           :class="method === 'midtrans' ? 'border-accent bg-accent/5' : 'border-gray-200 bg-white'">
                        <input type="radio" name="payment_method" value="midtrans" x-model="method" class="mt-1 text-accent focus:ring-accent">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-heading font-bold text-xs text-primary">Midtrans Payment Gateway (QRIS / Virtual Account)</span>
                                <span class="bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-0.5 rounded">Online Otomatis</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5">Mendukung pembayaran instan melalui QRIS GoPay, OVO, ShopeePay, dan Virtual Account.</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary & Confirmation (Right 4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-5 sticky top-24">
            <h3 class="font-heading font-bold text-base text-primary pb-3 border-b border-gray-100">Ringkasan Belanja</h3>

            <!-- Cart Items Preview -->
            <div class="space-y-3 max-h-56 overflow-y-auto pr-1">
                @foreach($cart->items as $item)
                    <div class="flex items-center space-x-3 text-xs">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded-xl object-cover bg-gray-50 shrink-0 border border-gray-100">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate">{{ $item->product->name }}</p>
                            <p class="text-[11px] text-gray-400">{{ $item->product->size ?: 'Free Size' }} &bull; {{ $item->product->condition_label }}</p>
                        </div>
                        <span class="font-bold text-gray-900 font-mono shrink-0">Rp{{ number_format($item->product->selling_price, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Price Breakdown -->
            <div class="space-y-2 pt-3 border-t border-gray-100 text-xs">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal Barang ({{ $cart->items_count }} item):</span>
                    <span class="font-semibold text-gray-800 font-mono">Rp{{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Biaya Pengiriman:</span>
                    <span class="font-semibold text-gray-800 font-mono" x-text="formatRupiah(shippingCost)"></span>
                </div>
                <div class="pt-3 border-t border-gray-100 flex justify-between items-center text-sm">
                    <span class="font-heading font-bold text-primary">Total Pembayaran:</span>
                    <span class="font-heading font-extrabold text-xl text-primary font-mono" x-text="formatRupiah(subtotal + shippingCost)"></span>
                </div>
            </div>

            <!-- Place Order Button -->
            <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-4 px-6 rounded-2xl text-xs transition shadow-lg flex items-center justify-center space-x-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Buat Pesanan Sekarang</span>
            </button>

            <p class="text-[10px] text-gray-400 text-center leading-relaxed">
                Dengan mengonfirmasi pesanan, barang akan di-reservasi khusus untuk Anda selama masa pembayaran.
            </p>
        </div>
    </form>
</div>
@endsection

