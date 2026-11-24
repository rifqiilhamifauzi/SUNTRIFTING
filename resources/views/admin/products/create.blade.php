@extends('layouts.admin', ['title' => 'Input Produk Muslim Thrift Baru — SUN-THRIFTING', 'headerTitle' => 'Input Produk Muslim Thrift Baru'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6" 
     x-data="{
        selectedCat: '{{ old('category_id') }}',
        subcategories: [],
        categories: {{ json_encode($categories) }},
        sellingPrice: {{ old('selling_price', 200000) }},
        agreedPrice: {{ old('agreed_price', 180000) }},
        adminPct: {{ $defaultAdminPct }},
        supplierPct: {{ $defaultSupplierPct }},
        previews: [],
        defectPreviews: [],
        init() {
            this.updateSubs();
        },
        updateSubs() {
            const cat = this.categories.find(c => c.id == this.selectedCat);
            this.subcategories = (cat && cat.subcategories) ? cat.subcategories : [];
        },
        handleFiles(event, type) {
            const files = event.target.files;
            if (type === 'normal') {
                this.previews = [];
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = (e) => this.previews.push(e.target.result);
                    reader.readAsDataURL(files[i]);
                }
            } else {
                this.defectPreviews = [];
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = (e) => this.defectPreviews.push(e.target.result);
                    reader.readAsDataURL(files[i]);
                }
            }
        },
        calculateShare() {
            const price = parseFloat(this.sellingPrice) || 0;
            const aAmount = Math.round(price * (this.adminPct / 100));
            const sAmount = price - aAmount;
            return { admin: aAmount, supplier: sAmount };
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(num);
        }
     }">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-muted-gray hover:text-charcoal flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. Informasi Utama Produk & Kategori -->
        <div class="bg-white rounded-2xl border border-warm-beige p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-charcoal pb-3 border-b border-warm-beige flex items-center space-x-2">
                <i data-lucide="info" class="w-4 h-4 text-secondary"></i>
                <span>Informasi Utama Produk & Kategori</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Nama Produk Fashion Muslim *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Abaya Dubai Hitam Bordir Emas" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Kode SKU / Produk (Otomatis jika kosong)</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="ST-..." 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Mitra Seller / Supplier *</label>
                    <select name="supplier_id" required class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        <option value="">Pilih Mitra Penjual</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }} ({{ $supplier->bank_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Kategori Utama *</label>
                    <select name="category_id" x-model="selectedCat" @change="updateSubs()" required class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        <option value="">Pilih Kategori Utama</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Subkategori</label>
                    <select name="subcategory_id" class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        <option value="">-- Pilih Subkategori --</option>
                        <template x-for="sub in subcategories" :key="sub.id">
                            <option :value="sub.id" x-text="sub.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-charcoal mb-1">Deskripsi Lengkap Produk</label>
                <textarea name="description" rows="3" placeholder="Jelaskan detail potongan pakaian, keunggulan bahan, dan kenyamanan pemakaian..." 
                          class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- 2. Karakteristik & Kondisi Thrifting -->
        <div class="bg-white rounded-2xl border border-warm-beige p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-charcoal pb-3 border-b border-warm-beige flex items-center space-x-2">
                <i data-lucide="tag" class="w-4 h-4 text-secondary"></i>
                <span>Karakteristik & Kondisi Barang</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Kondisi Produk *</label>
                    <select name="condition" required class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        <option value="brand_new" {{ old('condition') === 'brand_new' ? 'selected' : '' }}>Brand New with Tag (Baru)</option>
                        <option value="new_without_tag" {{ old('condition') === 'new_without_tag' ? 'selected' : '' }}>New Without Tag</option>
                        <option value="like_new" {{ old('condition', 'like_new') === 'like_new' ? 'selected' : '' }}>Like New (Seperti Baru)</option>
                        <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>Good Condition (Kondisi Baik)</option>
                        <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Fair Condition (Ada Tanda Pakai)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Target Pengguna *</label>
                    <select name="gender" required class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        <option value="women" {{ old('gender', 'women') === 'women' ? 'selected' : '' }}>Wanita (Muslimah)</option>
                        <option value="men" {{ old('gender') === 'men' ? 'selected' : '' }}>Pria</option>
                        <option value="kids" {{ old('gender') === 'kids' ? 'selected' : '' }}>Anak-anak</option>
                        <option value="unisex" {{ old('gender') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Ukuran / Size</label>
                    <input type="text" name="size" value="{{ old('size') }}" placeholder="Contoh: L / LD: 105cm" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Merek / Brand</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Contoh: Al-Haramain / Vanilla" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Warna</label>
                    <input type="text" name="color" value="{{ old('color') }}" placeholder="Contoh: Olive / Sage / Jet Black" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Material / Bahan</label>
                    <input type="text" name="material" value="{{ old('material') }}" placeholder="Contoh: Katun Madinah / Silk / Linen" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Lokasi Toko</label>
                    <input type="text" name="location" value="{{ old('location', 'Jakarta') }}" placeholder="Kota asal barang" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>
            </div>

            <!-- Minus / Defect Disclosure -->
            <div>
                <label class="block text-xs font-semibold text-amber-800 mb-1 flex items-center space-x-1.5">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Catatan Minus / Kekurangan Produk (Transparan)</span>
                </label>
                <textarea name="defect_description" rows="2" placeholder="Jelaskan secara jujur jika ada noda samar, benang terlepas, atau kancing pengganti..." 
                          class="w-full bg-amber-50/50 border border-amber-200 rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-amber-500 focus:border-amber-500">{{ old('defect_description') }}</textarea>
                <p class="text-[10px] text-muted-gray mt-1">Kosongkan jika kondisi produk mulus tanpa minus.</p>
            </div>
        </div>

        <!-- 3. Penetapan Harga & Bagi Hasil Konsinyasi -->
        <div class="bg-white rounded-2xl border border-warm-beige p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-charcoal pb-3 border-b border-warm-beige flex items-center space-x-2">
                <i data-lucide="calculator" class="w-4 h-4 text-secondary"></i>
                <span>Penetapan Harga & Revenue Sharing</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Harga Jual Marketplace (Rp) *</label>
                    <input type="number" name="selling_price" x-model="sellingPrice" required min="1" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Harga Kesepakatan Supplier (Rp) *</label>
                    <input type="number" name="agreed_price" x-model="agreedPrice" required min="0" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Harga Referensi Rilis Baru (Rp)</label>
                    <input type="number" name="original_price" value="{{ old('original_price') }}" placeholder="Harga normal saat rilis" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                    <p class="text-[10px] text-muted-gray mt-1">Ditampilkan dicoret sebagai perbandingan diskon.</p>
                </div>
            </div>

            <!-- Commission Split Calculator -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Komisi Admin (%)</label>
                    <input type="number" step="0.01" name="admin_commission_pct" x-model="adminPct" required 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Hak Supplier (%)</label>
                    <input type="number" step="0.01" name="supplier_commission_pct" x-model="supplierPct" required 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary">
                </div>
            </div>

            <div class="p-4 bg-cream rounded-xl border border-warm-beige flex items-center justify-between text-xs">
                <div>
                    <span class="text-muted-gray">Estimasi Komisi Admin:</span>
                    <span class="font-bold text-primary block" x-text="formatRupiah(calculateShare().admin)"></span>
                </div>
                <div>
                    <span class="text-muted-gray">Estimasi Hak Supplier:</span>
                    <span class="font-bold text-secondary block" x-text="formatRupiah(calculateShare().supplier)"></span>
                </div>
                <div>
                    <span class="text-muted-gray">Stok Produk:</span>
                    <span class="font-bold text-charcoal block">1-of-1 (Unik Thrift)</span>
                </div>
            </div>
        </div>

        <!-- 4. Upload Foto Produk & Foto Minus -->
        <div class="bg-white rounded-2xl border border-warm-beige p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-charcoal pb-3 border-b border-warm-beige flex items-center space-x-2">
                <i data-lucide="image" class="w-4 h-4 text-secondary"></i>
                <span>Foto Produk & Detail Minus</span>
            </h3>

            <!-- Regular Photos -->
            <div>
                <label class="block text-xs font-semibold text-charcoal mb-1">Foto Produk Utama & Sudut (Bisa pilih beberapa)</label>
                <input type="file" name="images[]" multiple accept="image/*" @change="handleFiles($event, 'normal')" 
                       class="w-full text-xs text-muted-gray file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark cursor-pointer">
                
                <div x-show="previews.length > 0" class="flex flex-wrap gap-3 mt-3">
                    <template x-for="(src, idx) in previews" :key="idx">
                        <div class="w-20 h-24 rounded-xl overflow-hidden border border-warm-beige relative">
                            <img :src="src" class="w-full h-full object-cover">
                            <span x-show="idx === 0" class="absolute bottom-1 left-1 bg-primary text-white text-[8px] px-1 rounded">Utama</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Defect Detail Photos -->
            <div class="pt-2 border-t border-warm-beige/60">
                <label class="block text-xs font-semibold text-amber-800 mb-1">Foto Detail Kekurangan / Minus (Opsional)</label>
                <input type="file" name="defect_images[]" multiple accept="image/*" @change="handleFiles($event, 'defect')" 
                       class="w-full text-xs text-muted-gray file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                
                <div x-show="defectPreviews.length > 0" class="flex flex-wrap gap-3 mt-3">
                    <template x-for="(src, idx) in defectPreviews" :key="idx">
                        <div class="w-20 h-24 rounded-xl overflow-hidden border border-amber-300 relative bg-amber-50">
                            <img :src="src" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 right-1 bg-amber-600 text-white text-[8px] px-1 rounded font-bold">Minus</span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Status Publikasi *</label>
                    <select name="status" required class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary">
                        <option value="available">Tersedia (Publish ke Katalog)</option>
                        <option value="draft">Draft (Simpan Sementara)</option>
                        <option value="reserved">Dipesan (Reserved)</option>
                        <option value="archived">Diarsipkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Berat Pengiriman (Gram)</label>
                    <input type="number" name="weight_grams" value="{{ old('weight_grams', 500) }}" min="1" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('admin.products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-charcoal px-5 py-2.5 rounded-xl text-xs font-semibold transition">Batal</a>
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-7 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm flex items-center space-x-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Simpan & Publikasikan Produk</span>
            </button>
        </div>
    </form>
</div>
@endsection
