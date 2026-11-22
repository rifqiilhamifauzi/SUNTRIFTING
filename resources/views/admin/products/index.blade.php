@extends('layouts.admin', ['title' => 'Produk Thrift — Suntrifting', 'headerTitle' => 'Manajemen Inventaris Barang Thrift'])

@section('content')
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-gray-900">Katalog Barang Thrift & Konsinyasi</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola stok 1-of-1, penetapan harga konsinyasi, dan status produk.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-primary hover:bg-gray-800 text-white font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tambah Produk Thrift Baru</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="relative lg:col-span-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang, kode ST-..., brand..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-2.5"></i>
            </div>

            <div>
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Status</option>
                    <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Tersedia (Ready)</option>
                    <option value="reserved" {{ $status === 'reserved' ? 'selected' : '' }}>Dipesan (Reserved)</option>
                    <option value="sold" {{ $status === 'sold' ? 'selected' : '' }}>Terjual (Sold)</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </div>

            <div>
                <select name="category_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $categoryId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <select name="supplier_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ $supplierId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-xl text-xs font-semibold">Filter</button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">Barang Thrift</th>
                        <th class="py-3.5 px-4">Supplier</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Harga Jual</th>
                        <th class="py-3.5 px-4">Bagi Hasil (90:10)</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $prod)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3 px-4 flex items-center space-x-3">
                                <img src="{{ $prod->image_url }}" alt="" class="w-12 h-12 rounded-xl object-cover bg-gray-50 border border-gray-100 shrink-0">
                                <div class="min-w-0">
                                    <a href="{{ route('product.show', $prod->code) }}" target="_blank" class="font-bold text-gray-900 hover:text-accent truncate block">
                                        {{ $prod->name }}
                                    </a>
                                    <div class="flex items-center space-x-2 text-[10px] text-gray-400 mt-0.5">
                                        <span class="font-mono">{{ $prod->code }}</span>
                                        <span>&bull;</span>
                                        <span class="text-accent font-semibold">{{ $prod->brand ?: '-' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $prod->size ?: '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-semibold text-gray-800">{{ $prod->supplier ? $prod->supplier->name : '-' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-gray-600">{{ $prod->category ? $prod->category->name : '-' }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-gray-900 text-sm">
                                Rp{{ number_format($prod->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="text-emerald-700 block font-mono font-semibold">
                                    Supplier: Rp{{ number_format($prod->selling_price * ($prod->supplier_commission_pct / 100), 0, ',', '.') }} ({{ $prod->supplier_commission_pct }}%)
                                </span>
                                <span class="text-gray-500 block font-mono">
                                    Admin: Rp{{ number_format($prod->selling_price * ($prod->admin_commission_pct / 100), 0, ',', '.') }} ({{ $prod->admin_commission_pct }}%)
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                {!! $prod->status_badge !!}
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $prod->id) }}" class="text-accent hover:underline font-semibold">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus produk ini?')" class="text-rose-500 hover:text-rose-700">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Belum ada data produk thrift yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

