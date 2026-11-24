@extends('layouts.admin', ['title' => 'Produk Muslim Thrift — SUN-THRIFTING', 'headerTitle' => 'Manajemen Produk Muslim Thrift'])

@section('content')
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-charcoal">Katalog Produk Muslim Thrift & Preloved</h2>
            <p class="text-xs text-muted-gray mt-0.5">Kelola stok unik 1-of-1, kurasi kondisi barang, dan pembagian hasil penjualan.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-primary hover:bg-primary-dark text-white font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tambah Produk Muslim Baru</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-warm-beige p-4 shadow-sm">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="relative lg:col-span-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama busana, kode SKU ST-..., brand..." 
                       class="w-full bg-cream border border-warm-beige rounded-xl pl-9 pr-3 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                <i data-lucide="search" class="w-3.5 h-3.5 text-muted-gray absolute left-3 top-2.5"></i>
            </div>

            <div>
                <select name="status" class="w-full bg-cream border border-warm-beige rounded-xl px-3 py-2 text-xs text-charcoal focus:ring-secondary">
                    <option value="">Semua Status</option>
                    <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Tersedia (Ready)</option>
                    <option value="reserved" {{ $status === 'reserved' ? 'selected' : '' }}>Dipesan (Reserved)</option>
                    <option value="sold" {{ $status === 'sold' ? 'selected' : '' }}>Terjual (Sold)</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </div>

            <div>
                <select name="category_id" class="w-full bg-cream border border-warm-beige rounded-xl px-3 py-2 text-xs text-charcoal focus:ring-secondary">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $categoryId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <select name="supplier_id" class="w-full bg-cream border border-warm-beige rounded-xl px-3 py-2 text-xs text-charcoal focus:ring-secondary">
                    <option value="">Semua Mitra Seller</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ $supplierId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-primary text-white hover:bg-primary-dark px-3 py-2 rounded-xl text-xs font-semibold shadow-sm">Filter</button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-warm-beige shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-cream/70 text-charcoal uppercase tracking-wider text-[10px] font-bold border-b border-warm-beige">
                    <tr>
                        <th class="py-3.5 px-4">Produk Busana Muslim</th>
                        <th class="py-3.5 px-4">Toko / Seller</th>
                        <th class="py-3.5 px-4">Kategori & Sub</th>
                        <th class="py-3.5 px-4">Kondisi</th>
                        <th class="py-3.5 px-4">Harga Jual</th>
                        <th class="py-3.5 px-4">Bagi Hasil</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-beige/40">
                    @forelse($products as $prod)
                        <tr class="hover:bg-cream/30">
                            <td class="py-3 px-4 flex items-center space-x-3">
                                <img src="{{ $prod->image_url }}" alt="" class="w-12 h-14 rounded-xl object-cover bg-cream border border-warm-beige shrink-0">
                                <div class="min-w-0">
                                    <a href="{{ route('product.show', $prod->code) }}" target="_blank" class="font-bold text-charcoal hover:text-primary truncate block">
                                        {{ $prod->name }}
                                    </a>
                                    <div class="flex items-center space-x-2 text-[10px] text-muted-gray mt-0.5">
                                        <span class="font-mono text-secondary font-semibold">{{ $prod->code }}</span>
                                        <span>&bull;</span>
                                        <span class="font-semibold text-charcoal">{{ $prod->brand ?: '-' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $prod->size ?: '-' }}</span>
                                    </div>
                                    @if($prod->defect_description)
                                        <span class="inline-block mt-1 text-[9px] text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded">
                                            Minus Transparan
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-semibold text-charcoal">{{ $prod->supplier ? $prod->supplier->name : '-' }}</span>
                                <span class="block text-[10px] text-muted-gray">{{ $prod->location }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-semibold text-primary block">{{ $prod->category ? $prod->category->name : '-' }}</span>
                                <span class="text-[10px] text-muted-gray">{{ $prod->subcategory ? $prod->subcategory->name : '-' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $prod->condition_badge_class }}">
                                    {{ $prod->condition_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-primary text-sm">
                                Rp{{ number_format($prod->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-[11px]">
                                @php
                                    $adminAmt = round($prod->selling_price * ($prod->admin_commission_pct / 100));
                                    $suppAmt = $prod->selling_price - $adminAmt;
                                @endphp
                                <span class="text-secondary font-bold block">Hak Mitra: Rp{{ number_format($suppAmt, 0, ',', '.') }}</span>
                                <span class="text-muted-gray text-[10px]">Komisi: Rp{{ number_format($adminAmt, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                {!! $prod->status_badge !!}
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $prod->id) }}" class="text-secondary hover:text-primary font-semibold">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus / mengarsipkan produk ini?')" class="text-rose-500 hover:text-rose-700">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-muted-gray">
                                Belum ada produk yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-warm-beige">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
