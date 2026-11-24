@extends('layouts.admin', ['title' => 'Produk Muslim Thrift — SUN-THRIFTING', 'headerTitle' => 'Manajemen Produk Muslim Thrift'])


@section('content')
<div class="-mt-2" x-data="{ filterOpen: false }" @open-filter.window="filterOpen = true" @keydown.escape.window="filterOpen = false">
    @php
        $activeFilters = collect([$search, $status, $categoryId, $supplierId])->filter()->count();
    @endphp

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-warm-beige shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-warm-beige flex items-center justify-between gap-3">
            <h3 class="font-heading font-bold text-sm text-charcoal">Data Produk Muslim Thrift</h3>

            <div class="flex items-center gap-2">
                <button type="button" @click="filterOpen = true"
                        class="relative w-9 h-9 bg-white hover:bg-cream border border-warm-beige text-charcoal rounded-xl flex items-center justify-center transition shadow-sm"
                        title="Filter produk">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                    @if($activeFilters > 0)
                        <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-secondary text-white text-[9px] font-bold flex items-center justify-center">{{ $activeFilters }}</span>
                    @endif
                </button>

                <a href="{{ route('admin.products.create') }}" class="bg-primary hover:bg-primary-dark text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Tambah Produk Muslim Baru</span>
                </a>
            </div>
        </div>

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
                            <td class="py-3 px-4 text-right">
                                <div x-data="{ open: false, top: 0, left: 0 }" class="inline-block text-left">
                                    <button type="button" x-ref="btn"
                                            @click="const r = $refs.btn.getBoundingClientRect(); top = (r.bottom + 120 > window.innerHeight) ? r.top - 92 : r.bottom + 4; left = r.right - 144; open = !open"
                                            class="inline-flex items-center space-x-1 bg-primary hover:bg-primary-dark text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition shadow-sm">
                                        <span>Aksi</span>
                                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                    </button>

                                    <div x-show="open" x-cloak @click.outside="open = false" @scroll.window.passive="open = false" @keydown.escape.window="open = false"
                                         :style="`top:${top}px; left:${left}px`"
                                         class="fixed z-50 w-36 bg-white rounded-xl shadow-xl border border-warm-beige py-1.5 text-left">
                                        <a href="{{ route('admin.products.edit', $prod->id) }}" class="flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-charcoal hover:bg-cream transition">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5 text-muted-gray"></i>
                                            <span>Edit</span>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus / mengarsipkan produk ini?')"
                                                    class="w-full flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-charcoal hover:bg-rose-50 hover:text-rose-600 transition">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5 text-muted-gray"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
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

    <!-- Filter Modal -->
    <div x-show="filterOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div @click.outside="filterOpen = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-warm-beige">
                <span class="font-heading font-bold text-sm text-charcoal">Filter Produk</span>
                <button type="button" @click="filterOpen = false" class="text-muted-gray hover:text-charcoal"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>

            <form action="{{ route('admin.products.index') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama busana, kode SKU ST-..., brand..."
                               class="w-full bg-cream border border-warm-beige rounded-xl pl-9 pr-3 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-muted-gray absolute left-3 top-2.5"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Status</label>
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
                    <label class="block text-xs font-semibold text-charcoal mb-1">Kategori</label>
                    <select name="category_id" class="w-full bg-cream border border-warm-beige rounded-xl px-3 py-2 text-xs text-charcoal focus:ring-secondary">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ $categoryId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Mitra Seller</label>
                    <select name="supplier_id" class="w-full bg-cream border border-warm-beige rounded-xl px-3 py-2 text-xs text-charcoal focus:ring-secondary">
                        <option value="">Semua Mitra Seller</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ $supplierId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-muted-gray hover:text-charcoal">Reset</a>
                    <div class="flex items-center space-x-2">
                        <button type="button" @click="filterOpen = false" class="px-3 py-2 text-xs text-charcoal">Batal</button>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold px-5 py-2 rounded-xl text-xs transition shadow-sm">Terapkan Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection