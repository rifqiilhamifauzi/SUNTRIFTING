@extends('layouts.admin', ['title' => 'Manajemen Supplier — Suntrifting', 'headerTitle' => 'Manajemen Mitra Supplier Konsinyasi'])

@section('content')
<div class="-mt-2" x-data="{ filterOpen: false }" @keydown.escape.window="filterOpen = false">
    @php
        $activeFilters = collect([$search, $status])->filter()->count();
    @endphp

    <!-- Supplier Table -->
    <div class="bg-white rounded-2xl border border-warm-beige shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-warm-beige flex items-center justify-between gap-3">
            <h3 class="font-heading font-bold text-sm text-charcoal">Data Mitra Supplier</h3>

            <div class="flex items-center gap-2">
                <button type="button" @click="filterOpen = true"
                        class="relative w-9 h-9 bg-white hover:bg-cream border border-warm-beige text-charcoal rounded-xl flex items-center justify-center transition shadow-sm"
                        title="Filter supplier">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                    @if($activeFilters > 0)
                        <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-secondary text-white text-[9px] font-bold flex items-center justify-center">{{ $activeFilters }}</span>
                    @endif
                </button>

                <a href="{{ route('admin.suppliers.create') }}" class="bg-primary hover:bg-primary-dark text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Tambah Supplier Baru</span>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-cream/70 text-charcoal uppercase tracking-wider text-[10px] font-bold border-b border-warm-beige">
                    <tr>
                        <th class="py-3.5 px-4">Supplier</th>
                        <th class="py-3.5 px-4">Kontak</th>
                        <th class="py-3.5 px-4">Rekening Bank</th>
                        <th class="py-3.5 px-4 text-center">Produk</th>
                        <th class="py-3.5 px-4">Saldo Belum Dicairkan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-beige/40">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-cream/30">
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="font-heading font-bold text-charcoal hover:text-primary">
                                    {{ $supplier->name }}
                                </a>
                                <p class="text-[10px] text-muted-gray truncate max-w-xs">{{ $supplier->address }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-charcoal">{{ $supplier->phone }}</span>
                                <span class="text-[10px] text-muted-gray block">{{ $supplier->email ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal">{{ $supplier->bank_name }}</span>
                                <span class="font-mono text-muted-gray block">{{ $supplier->bank_account_number }}</span>
                                <span class="text-[10px] text-muted-gray">a.n {{ $supplier->bank_account_holder }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-secondary">
                                {{ $supplier->products_count }} item
                            </td>
                            <td class="py-3.5 px-4 font-mono font-extrabold text-sm {{ $supplier->balance > 0 ? 'text-primary' : 'text-muted-gray' }}">
                                Rp{{ number_format($supplier->balance, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $supplier->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $supplier->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div x-data="{ open: false, top: 0, left: 0 }" class="inline-block text-left">
                                    <button type="button" x-ref="btn"
                                            @click="const r = $refs.btn.getBoundingClientRect(); top = (r.bottom + 150 > window.innerHeight) ? r.top - 132 : r.bottom + 4; left = r.right - 144; open = !open"
                                            class="inline-flex items-center space-x-1 bg-primary hover:bg-primary-dark text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition shadow-sm">
                                        <span>Aksi</span>
                                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                    </button>

                                    <div x-show="open" x-cloak @click.outside="open = false" @scroll.window.passive="open = false" @keydown.escape.window="open = false"
                                         :style="`top:${top}px; left:${left}px`"
                                         class="fixed z-40 w-36 bg-white rounded-xl shadow-xl border border-warm-beige py-1.5 text-left">
                                        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-charcoal hover:bg-cream transition">
                                            <i data-lucide="eye" class="w-3.5 h-3.5 text-muted-gray"></i>
                                            <span>Detail</span>
                                        </a>
                                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-charcoal hover:bg-cream transition">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5 text-muted-gray"></i>
                                            <span>Edit</span>
                                        </a>
                                        <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus/menonaktifkan supplier ini?')"
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
                            <td colspan="7" class="py-8 text-center text-muted-gray">Belum ada data supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-warm-beige">
            {{ $suppliers->links() }}
        </div>
    </div>

    <!-- Filter Modal -->
    <div x-show="filterOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div @click.outside="filterOpen = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-warm-beige">
                <span class="font-heading font-bold text-sm text-charcoal">Filter Supplier</span>
                <button type="button" @click="filterOpen = false" class="text-muted-gray hover:text-charcoal"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>

            <form action="{{ route('admin.suppliers.index') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama supplier, telepon, nama pemilik rekening..."
                               class="w-full bg-cream border border-warm-beige rounded-xl pl-9 pr-3 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-muted-gray absolute left-3 top-2.5"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Status</label>
                    <select name="status" class="w-full bg-cream border border-warm-beige rounded-xl px-3 py-2 text-xs text-charcoal focus:ring-secondary">
                        <option value="">Semua Status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <a href="{{ route('admin.suppliers.index') }}" class="text-xs font-semibold text-muted-gray hover:text-charcoal">Reset</a>
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