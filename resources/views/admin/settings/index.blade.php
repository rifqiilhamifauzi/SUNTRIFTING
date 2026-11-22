@extends('layouts.admin', ['title' => 'Pengaturan Sistem — Suntrifting', 'headerTitle' => 'Konfigurasi Sistem & Persentase Komisi'])

@section('content')
<div class="space-y-8 max-w-4xl mx-auto" x-data="{ adminPct: {{ $settings['admin_commission_pct'] }}, supplierPct: {{ $settings['supplier_commission_pct'] }} }">
    <div>
        <h2 class="font-heading font-bold text-xl text-gray-900">Pengaturan Sistem & Parameter Konsinyasi</h2>
        <p class="text-xs text-gray-500 mt-0.5">Kelola skema persentase komisi bagi hasil, rekening penampung transfer bank resmi, dan tarif ongkir.</p>
    </div>

    <!-- General & Financial Settings Form -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-6">
        <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100 flex items-center">
            <i data-lucide="sliders" class="w-4 h-4 mr-2 text-accent"></i>
            Skema Persentase Komisi Konsinyasi Default
        </h3>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Commission Split Row -->
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                <span class="text-xs font-bold text-gray-900 block">Default Revenue Sharing (Total harus 100%):</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Persentase Keuntungan Admin (%) *</label>
                        <input type="number" step="0.01" name="admin_commission_pct" required x-model="adminPct" @input="supplierPct = (100 - parseFloat(adminPct || 0)).toFixed(2)" 
                               class="w-full bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono font-bold text-gray-900">
                        <span class="text-[10px] text-gray-400">Komisi platform dari setiap barang terjual</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Persentase Hak Supplier (%) *</label>
                        <input type="number" step="0.01" name="supplier_commission_pct" required x-model="supplierPct" @input="adminPct = (100 - parseFloat(supplierPct || 0)).toFixed(2)" 
                               class="w-full bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono font-bold text-emerald-700">
                        <span class="text-[10px] text-gray-400">Hak bagian yang diterima supplier mitra</span>
                    </div>
                </div>

                <div class="p-2.5 bg-accent/10 rounded-xl text-[11px] text-accent-dark flex items-center space-x-2">
                    <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                    <span>Catatan: Perubahan persentase hanya berlaku untuk input produk baru dan tidak akan mengubah transaksi historis lama (menggunakan snapshot).</span>
                </div>
            </div>

            <!-- Official Bank Accounts -->
            <div class="space-y-4">
                <h4 class="font-heading font-bold text-xs text-gray-900 uppercase tracking-wider">Rekening Bank Resmi Penampung Pembayaran Manual</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Bank BCA -->
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                        <div class="font-bold text-xs text-gray-900">Bank Central Asia (BCA)</div>
                        <div>
                            <label class="block text-[11px] text-gray-500 mb-0.5">Nomor Rekening *</label>
                            <input type="text" name="bank_bca_number" required value="{{ old('bank_bca_number', $settings['bank_bca_number']) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 mb-0.5">Atas Nama Rekening *</label>
                            <input type="text" name="bank_bca_holder" required value="{{ old('bank_bca_holder', $settings['bank_bca_holder']) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        </div>
                    </div>

                    <!-- Bank Mandiri -->
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                        <div class="font-bold text-xs text-gray-900">Bank Mandiri</div>
                        <div>
                            <label class="block text-[11px] text-gray-500 mb-0.5">Nomor Rekening *</label>
                            <input type="text" name="bank_mandiri_number" required value="{{ old('bank_mandiri_number', $settings['bank_mandiri_number']) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 mb-0.5">Atas Nama Rekening *</label>
                            <input type="text" name="bank_mandiri_holder" required value="{{ old('bank_mandiri_holder', $settings['bank_mandiri_holder']) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Information -->
            <div class="space-y-4">
                <h4 class="font-heading font-bold text-xs text-gray-900 uppercase tracking-wider">Identitas & Kontak Marketplace</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Brand Toko *</label>
                        <input type="text" name="store_name" required value="{{ old('store_name', $settings['store_name']) }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tagline Slogan</label>
                        <input type="text" name="store_tagline" value="{{ old('store_tagline', $settings['store_tagline']) }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">WhatsApp CS</label>
                        <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp']) }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email Dukungan</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                <button type="submit" class="bg-primary hover:bg-gray-800 text-white font-semibold py-2.5 px-6 rounded-xl text-xs transition shadow-sm">
                    Simpan Semua Perubahan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Shipping Rates Table -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
        <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100 flex items-center">
            <i data-lucide="truck" class="w-4 h-4 mr-2 text-accent"></i>
            Konfigurasi Tarif Ongkos Kirim Wilayah / Provinsi
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-2.5 px-4">Provinsi / Wilayah</th>
                        <th class="py-2.5 px-4">Estimasi Hari</th>
                        <th class="py-2.5 px-4">Tarif Ongkir (Rp)</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                        <th class="py-2.5 px-4 text-right">Update Tarif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($shippingRates as $rate)
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-900">{{ $rate->province }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $rate->estimated_days_min }} - {{ $rate->estimated_days_max }} hari</td>
                            <form action="{{ route('admin.settings.shipping.update', $rate->id) }}" method="POST">
                                @csrf
                                <td class="py-3 px-4">
                                    <input type="number" name="cost" value="{{ (int)$rate->cost }}" 
                                           class="w-28 bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1 text-xs font-mono font-bold focus:outline-none focus:ring-1 focus:ring-accent">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $rate->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-accent focus:ring-accent">
                                    </label>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-lg text-[11px] font-semibold transition">
                                        Simpan
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

