@extends('layouts.admin', ['title' => 'Edit Produk: ' . $product->name . ' — Suntrifting', 'headerTitle' => 'Edit Barang Thrift'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6" 
     x-data="{
        sellingPrice: {{ (float) $product->selling_price }},
        agreedPrice: {{ (float) $product->agreed_price }},
        adminPct: {{ (float) $product->admin_commission_pct }},
        supplierPct: {{ (float) $product->supplier_commission_pct }},
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

    <!-- Existing Images Manager -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
        <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100">Foto Produk Tersimpan</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @forelse($product->images as $img)
                <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-200 group bg-gray-50">
                    <img src="{{ $img->url }}" class="w-full h-full object-cover">
                    @if($img->is_primary)
                        <span class="absolute top-2 left-2 bg-accent text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow">Utama</span>
                    @endif
                    <form action="{{ route('admin.products.image.delete', $img->id) }}" method="POST" class="absolute top-2 right-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus foto ini?')" class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center hover:bg-rose-700 shadow transition" title="Hapus Foto">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-xs text-gray-400 col-span-4">Belum ada foto yang diunggah.</p>
            @endforelse
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Product Basic Info -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100">Informasi Produk & Supplier</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Barang Thrift *</label>
                    <input type="text" name="name" required value="{{ old('name', $product->name) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Produk *</label>
                    <input type="text" name="code" required value="{{ old('code', $product->code) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Mitra Supplier Pemilik Barang *</label>
                    <select name="supplier_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }} ({{ $supplier->bank_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Produk *</label>
                    <select name="category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                        <option value="like_new" {{ old('condition', $product->condition) === 'like_new' ? 'selected' : '' }}>Seperti Baru (Like New)</option>
                        <option value="good" {{ old('condition', $product->condition) === 'good' ? 'selected' : '' }}>Kondisi Baik (Good)</option>
                        <option value="fair" {{ old('condition', $product->condition) === 'fair' ? 'selected' : '' }}>Cukup Baik (Fair)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran / Size</label>
                    <input type="text" name="size" value="{{ old('size', $product->size) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Brand / Merk</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Warna Pakaian</label>
                    <input type="text" name="color" value="{{ old('color', $product->color) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Lengkap & Detail Minus</label>
                <textarea name="description" rows="3" 
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- Pricing & Profit Sharing -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100">Harga & Bagi Hasil Konsinyasi</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Kesepakatan Supplier (Rp) *</label>
                    <input type="number" name="agreed_price" required x-model="agreedPrice" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Jual Marketplace (Rp) *</label>
                    <input type="number" name="selling_price" required x-model="sellingPrice" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono font-bold text-gray-900">
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
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Produk *</label>
                    <select name="status" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        <option value="available" {{ old('status', $product->status) === 'available' ? 'selected' : '' }}>Tersedia (Ready)</option>
                        <option value="reserved" {{ old('status', $product->status) === 'reserved' ? 'selected' : '' }}>Dipesan (Reserved)</option>
                        <option value="sold" {{ old('status', $product->status) === 'sold' ? 'selected' : '' }}>Terjual (Sold)</option>
                        <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ old('status', $product->status) === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>
            </div>

            <!-- Profit Sharing Live Preview Box -->
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                <span class="text-xs font-bold text-gray-900 block">Kalkulasi Pembagian Hasil:</span>
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

        <!-- Add Additional Photos -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100">Tambah Foto Baru (Opsional)</h3>
            <input type="file" name="images[]" multiple accept="image/*" @change="handleFiles($event)"
                   class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-gray-800 cursor-pointer">

            <div x-show="previews.length > 0" x-cloak class="space-y-2">
                <span class="text-xs font-semibold text-gray-700">Preview Foto Baru:</span>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <template x-for="(imgSrc, index) in previews" :key="index">
                        <div class="relative aspect-square rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                            <img :src="imgSrc" class="w-full h-full object-cover">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900">Batal</a>
            <button type="submit" class="bg-primary hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-xl text-xs transition shadow-sm">
                Perbarui Data Produk
            </button>
        </div>
    </form>
</div>
@endsection

