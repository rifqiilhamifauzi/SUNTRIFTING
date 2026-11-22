@extends('layouts.admin', ['title' => 'Input Produk Thrift Baru — Suntrifting', 'headerTitle' => 'Input Barang Konsinyasi Baru'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6" 
     x-data="{
        sellingPrice: 200000,
        agreedPrice: 180000,
        adminPct: {{ $defaultAdminPct }},
        supplierPct: {{ $defaultSupplierPct }},
        previews: [],
        handleFiles(event) {
            this.previews = [];
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push(e.target.result);
                };
                reader.readAsDataURL(files[i]);
            }
        },
        calculateShare() {
            const price = parseFloat(this.sellingPrice) || 0;
            const aAmount = Math.round(price * (this.adminPct / 100));
            const sAmount = price - aAmount;
            return {
                admin: aAmount,
                supplier: sAmount
            };
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(num);
        }
     }">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Product Basic Info -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100">Informasi Produk & Supplier</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Barang Thrift *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Vintage Carhartt Detroit Jacket J97" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Produk (Otomatis jika kosong)</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="ST-..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Mitra Supplier Pemilik Barang *</label>
                    <select name="supplier_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        <option value="">Pilih Supplier Penitip</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }} ({{ $supplier->bank_name }} - {{ $supplier->bank_account_holder }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Produk *</label>
                    <select name="category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kondisi Barang *</label>
                    <select name="condition" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        <option value="like_new" {{ old('condition') === 'like_new' ? 'selected' : '' }}>Seperti Baru (Like New)</option>
                        <option value="good" {{ old('condition', 'good') === 'good' ? 'selected' : '' }}>Kondisi Baik (Good)</option>
                        <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Cukup Baik (Fair)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran / Size</label>
                    <input type="text" name="size" value="{{ old('size') }}" placeholder="Contoh: L (P:68, L:58)" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Brand / Merk</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Nike / Levi's / Carhartt..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Warna Pakaian</label>
                    <input type="text" name="color" value="{{ old('color') }}" placeholder="Navy / Faded Black" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Lengkap & Detail Minus (jika ada)</label>
                <textarea name="description" rows="3" placeholder="Jelaskan kondisi bahan, kancing, zipper, atau detail vintage lainnya..." 
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Consignment Pricing & Profit Sharing Calculator -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="font-heading font-bold text-sm text-gray-900">Penetapan Harga & Pembagian Hasil Konsinyasi</h3>
                    <p class="text-[11px] text-gray-400">Kalkulasi pembagian hasil otomatis berdasarkan persentase kesepakatan</p>
                </div>
                <span class="bg-accent/10 text-accent font-bold text-xs px-2.5 py-1 rounded-lg">Konsinyasi 90:10</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Kesepakatan Supplier (Rp) *</label>
                    <input type="number" name="agreed_price" required x-model="agreedPrice" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
                    <span class="text-[10px] text-gray-400">Harga dasar yang disepakati dengan supplier</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Jual Marketplace (Rp) *</label>
                    <input type="number" name="selling_price" required x-model="sellingPrice" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono font-bold text-gray-900">
                    <span class="text-[10px] text-gray-400">Harga yang akan tampil di halaman belanja konsumen</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Persentase Admin (%) *</label>
                    <input type="number" step="0.01" name="admin_commission_pct" required x-model="adminPct" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Persentase Supplier (%) *</label>
                    <input type="number" step="0.01" name="supplier_commission_pct" required x-model="supplierPct" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Awal Produk *</label>
                    <select name="status" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        <option value="available" selected>Tersedia (Langsung Dipublikasikan)</option>
                        <option value="draft">Draft (Simpan Sementara)</option>
                    </select>
                </div>
            </div>

            <!-- Real-time Profit Sharing Live Preview Box -->
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                <span class="text-xs font-bold text-gray-900 block">Simulasi Pembagian Hasil Ketika Terjual:</span>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div class="p-3 bg-white rounded-xl border border-emerald-200">
                        <span class="text-[10px] text-gray-400 uppercase font-semibold block">Hak Supplier (<span x-text="supplierPct"></span>%)</span>
                        <span class="font-heading font-extrabold text-base text-emerald-700 font-mono" x-text="formatRupiah(calculateShare().supplier)"></span>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-gray-200">
                        <span class="text-[10px] text-gray-400 uppercase font-semibold block">Keuntungan Admin (<span x-text="adminPct"></span>%)</span>
                        <span class="font-heading font-extrabold text-base text-gray-900 font-mono" x-text="formatRupiah(calculateShare().admin)"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photos Upload & Client-side Preview -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="font-heading font-bold text-sm text-gray-900">Foto Produk Thrift (Multiple Upload)</h3>
                <span class="text-xs text-gray-400">Format: JPG, PNG, WEBP (Maks 4MB/foto)</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Pilih Foto-Foto Produk</label>
                <input type="file" name="images[]" multiple accept="image/*" @change="handleFiles($event)"
                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-gray-800 cursor-pointer">
            </div>

            <!-- Preview Grid -->
            <div x-show="previews.length > 0" x-cloak class="space-y-2">
                <span class="text-xs font-semibold text-gray-700">Preview Foto Sebelum Disimpan:</span>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <template x-for="(imgSrc, index) in previews" :key="index">
                        <div class="relative aspect-square rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                            <img :src="imgSrc" class="w-full h-full object-cover">
                            <span x-show="index === 0" class="absolute top-1 left-1 bg-accent text-white text-[9px] font-bold px-1.5 py-0.5 rounded">Foto Utama</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900">Batal</a>
            <button type="submit" class="bg-primary hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-xl text-xs transition shadow-sm">
                Simpan & Publikasikan Produk
            </button>
        </div>
    </form>
</div>
@endsection

