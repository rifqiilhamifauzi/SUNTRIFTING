@extends('layouts.admin', ['title' => 'Kategori Produk — Suntrifting', 'headerTitle' => 'Manajemen Kategori Produk'])

@section('content')
<div class="space-y-6" x-data="{ editModal: false, editCat: {} }">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-gray-900">Kategori Barang Thrift</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola kategori pengelompokan produk di katalog marketplace.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Categories List (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                        <tr>
                            <th class="py-3.5 px-4">Urutan</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Slug</th>
                            <th class="py-3.5 px-4 text-center">Jumlah Produk</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($categories as $cat)
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-400">{{ $cat->sort_order }}</td>
                                <td class="py-3.5 px-4 font-bold text-gray-900 flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-lg bg-accent/10 text-accent flex items-center justify-center shrink-0">
                                        <i data-lucide="{{ $cat->icon ?: 'tag' }}" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span>{{ $cat->name }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-gray-400">{{ $cat->slug }}</td>
                                <td class="py-3.5 px-4 text-center font-semibold">
                                    {{ $cat->products_count }} item
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $cat->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $cat->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2">
                                    <button type="button" @click="editCat = {{ json_encode($cat) }}; editModal = true" class="text-accent hover:underline font-semibold">
                                        Edit
                                    </button>
                                    @if($cat->products_count === 0)
                                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus kategori ini?')" class="text-rose-500 hover:text-rose-700">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Category Form (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <h3 class="font-heading font-bold text-sm text-gray-900 pb-3 border-b border-gray-100">Tambah Kategori Baru</h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori *</label>
                    <input type="text" name="name" required placeholder="Contoh: Vintage Jackets" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Icon Lucide (Opsional)</label>
                    <input type="text" name="icon" placeholder="shirt / tag / sparkles / layers / crown" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi untuk katalog..." 
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">No. Urutan</label>
                        <input type="number" name="sort_order" value="0" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-accent focus:ring-accent mr-2">
                            <span>Status Aktif</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-sm">
                    Simpan Kategori Baru
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div @click.outside="editModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="font-heading font-bold text-sm text-gray-900">Edit Kategori</h3>
                <button type="button" @click="editModal = false" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>

            <form :action="'/admin/categories/' + editCat.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori *</label>
                    <input type="text" name="name" required x-model="editCat.name" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Icon Lucide</label>
                    <input type="text" name="icon" x-model="editCat.icon" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" x-model="editCat.description" 
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">No. Urut</label>
                        <input type="number" name="sort_order" x-model="editCat.sort_order" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" :checked="editCat.is_active" class="rounded border-gray-300 text-accent focus:ring-accent mr-2">
                            <span>Status Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="editModal = false" class="px-3 py-2 text-xs text-gray-600 hover:text-gray-900">Batal</button>
                    <button type="submit" class="bg-primary hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded-xl text-xs transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

