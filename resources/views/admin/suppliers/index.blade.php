@extends('layouts.admin', ['title' => 'Manajemen Supplier — Suntrifting', 'headerTitle' => 'Manajemen Mitra Supplier Konsinyasi'])

@section('content')
<div class="space-y-6">
    <!-- Top Action & Search -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-gray-900">Mitra Supplier (Penitip Barang)</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola data supplier konsinyasi, rekening transfer, dan pantau saldo pencairan.</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="bg-primary hover:bg-gray-800 text-white font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Tambah Supplier Baru</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form action="{{ route('admin.suppliers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative sm:col-span-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama supplier, telepon, nama pemilik rekening..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-2.5"></i>
            </div>
            <div class="flex items-center space-x-2">
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    <option value="">Semua Status</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-semibold">Filter</button>
            </div>
        </form>
    </div>

    <!-- Supplier Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
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
                <tbody class="divide-y divide-gray-100">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="font-heading font-bold text-gray-900 hover:text-accent">
                                    {{ $supplier->name }}
                                </a>
                                <p class="text-[10px] text-gray-400 truncate max-w-xs">{{ $supplier->address }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-gray-800">{{ $supplier->phone }}</span>
                                <span class="text-[10px] text-gray-400 block">{{ $supplier->email ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-900">{{ $supplier->bank_name }}</span>
                                <span class="font-mono text-gray-600 block">{{ $supplier->bank_account_number }}</span>
                                <span class="text-[10px] text-gray-400">a.n {{ $supplier->bank_account_holder }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold">
                                {{ $supplier->products_count }} item
                            </td>
                            <td class="py-3.5 px-4 font-mono font-extrabold text-sm {{ $supplier->balance > 0 ? 'text-emerald-700' : 'text-gray-400' }}">
                                Rp{{ number_format($supplier->balance, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $supplier->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="text-accent hover:underline font-semibold" title="Detail Supplier">
                                    Detail
                                </a>
                                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="text-gray-500 hover:text-gray-800" title="Edit">
                                    Edit
                                </a>
                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus/menonaktifkan supplier ini?')" class="text-rose-500 hover:text-rose-700" title="Hapus">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Belum ada data supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection

