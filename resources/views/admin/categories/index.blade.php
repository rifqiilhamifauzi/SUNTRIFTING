@extends('layouts.admin', ['title' => 'Kategori & Subkategori Produk — SUN-THRIFTING', 'headerTitle' => 'Manajemen Kategori & Subkategori'])

@section('content')
<div class="space-y-6" x-data="{ editModal: false, editCat: {} }">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-xl text-charcoal">Kategori & Subkategori Fashion Muslim</h2>
            <p class="text-xs text-muted-gray mt-0.5">Kelola struktur hierarki kategori produk busana muslim dan perlengkapan ibadah.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Categories List (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
            @foreach($parents as $parent)
                <div class="bg-white rounded-2xl border border-warm-beige shadow-sm overflow-hidden" x-data="{ openSub: true }">
                    <!-- Parent Header -->
                    <div class="p-4 bg-cream/60 flex items-center justify-between border-b border-warm-beige">
                        <div class="flex items-center space-x-3">
                            <button type="button" @click="openSub = !openSub" class="text-secondary hover:text-primary">
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="openSub ? '' : '-rotate-90'"></i>
                            </button>
                            <div class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $parent->icon ?: 'tag' }}" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-sm text-charcoal">{{ $parent->name }}</h3>
                                <p class="text-[10px] text-muted-gray">{{ $parent->subcategories->count() }} Subkategori • {{ $parent->products_count }} Produk Langsung</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 text-xs">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $parent->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $parent->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                            <button type="button" @click="editCat = {{ json_encode($parent) }}; editModal = true" class="text-secondary hover:underline font-semibold">
                                Edit
                            </button>
                            @php
                                $totalProd = $parent->products_count + $parent->subcategories->sum('products_count');
                            @endphp
                            @if($totalProd === 0)
                                <form action="{{ route('admin.categories.destroy', $parent->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus kategori utama ini beserta subkategorinya?')" class="text-rose-500 hover:text-rose-700">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Subcategories Sub-table -->
                    <div x-show="openSub" class="p-2">
                        @if($parent->subcategories->isEmpty())
                            <p class="text-xs text-muted-gray p-4 text-center">Belum ada subkategori pada kategori ini.</p>
                        @else
                            <table class="w-full text-xs text-left">
                                <thead class="text-muted-gray uppercase text-[10px] font-bold border-b border-warm-beige/60">
                                    <tr>
                                        <th class="py-2 px-3">Nama Subkategori</th>
                                        <th class="py-2 px-3">Slug</th>
                                        <th class="py-2 px-3 text-center">Produk</th>
                                        <th class="py-2 px-3 text-center">Status</th>
                                        <th class="py-2 px-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-warm-beige/40">
                                    @foreach($parent->subcategories as $sub)
                                        <tr class="hover:bg-cream/40">
                                            <td class="py-2.5 px-3 font-semibold text-charcoal flex items-center space-x-2">
                                                <i data-lucide="corner-down-right" class="w-3.5 h-3.5 text-secondary"></i>
                                                <span>{{ $sub->name }}</span>
                                            </td>
                                            <td class="py-2.5 px-3 font-mono text-[11px] text-muted-gray">{{ $sub->slug }}</td>
                                            <td class="py-2.5 px-3 text-center font-bold text-secondary">{{ $sub->products_count }} item</td>
                                            <td class="py-2.5 px-3 text-center">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold {{ $sub->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                                    {{ $sub->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="py-2.5 px-3 text-right space-x-2">
                                                <button type="button" @click="editCat = {{ json_encode($sub) }}; editModal = true" class="text-secondary hover:underline font-semibold">
                                                    Edit
                                                </button>
                                                @if($sub->products_count === 0)
                                                    <form action="{{ route('admin.categories.destroy', $sub->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus subkategori ini?')" class="text-rose-500 hover:text-rose-700">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add Category / Subcategory Form (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-warm-beige p-6 shadow-sm space-y-4 sticky top-24">
            <h3 class="font-heading font-bold text-sm text-charcoal pb-3 border-b border-warm-beige">Tambah Kategori / Subkategori</h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Kategori Induk (Parent)</label>
                    <select name="parent_id" class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                        <option value="">-- Kategori Utama (Tanpa Induk) --</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-muted-gray mt-1">Pilih kategori induk jika ingin membuat subkategori.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Nama Kategori / Subkategori *</label>
                    <input type="text" name="name" required placeholder="Contoh: Abaya / Gamis Syar'i" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Icon Lucide (Opsional)</label>
                    <input type="text" name="icon" placeholder="sparkles / shirt / moon / sun / gem" 
                           class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-charcoal mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi untuk kurasi katalog..." 
                              class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-charcoal mb-1">No. Urutan</label>
                        <input type="number" name="sort_order" value="0" 
                               class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal focus:ring-secondary focus:border-secondary">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center text-xs text-charcoal cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-secondary focus:ring-secondary mr-2">
                            <span>Status Aktif</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-sm flex items-center justify-center space-x-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Simpan Kategori</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="editModal = false"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 border border-warm-beige">
                <div class="flex items-center justify-between pb-3 border-b border-warm-beige">
                    <h3 class="font-heading font-bold text-base text-charcoal">Edit Kategori / Subkategori</h3>
                    <button @click="editModal = false" class="text-muted-gray hover:text-charcoal"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>

                <form :action="'{{ url('admin/categories') }}/' + editCat.id" method="POST" class="space-y-4 mt-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-charcoal mb-1">Kategori Induk</label>
                        <select name="parent_id" x-model="editCat.parent_id" class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal">
                            <option value="">-- Kategori Utama (Tanpa Induk) --</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-charcoal mb-1">Nama Kategori *</label>
                        <input type="text" name="name" x-model="editCat.name" required class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-charcoal mb-1">Icon Lucide</label>
                        <input type="text" name="icon" x-model="editCat.icon" class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-charcoal mb-1">Deskripsi</label>
                        <textarea name="description" x-model="editCat.description" rows="2" class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-charcoal mb-1">No. Urutan</label>
                            <input type="number" name="sort_order" x-model="editCat.sort_order" class="w-full bg-cream border border-warm-beige rounded-xl px-3.5 py-2 text-xs text-charcoal">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="flex items-center text-xs text-charcoal cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" :checked="editCat.is_active" class="rounded border-gray-300 text-secondary mr-2">
                                <span>Status Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end space-x-2">
                        <button type="button" @click="editModal = false" class="bg-gray-100 hover:bg-gray-200 text-charcoal px-4 py-2 rounded-xl text-xs font-semibold">Batal</button>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-xl text-xs font-semibold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
