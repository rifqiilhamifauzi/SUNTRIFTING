@extends('layouts.admin', ['title' => 'Kategori & Subkategori Produk — SUN-THRIFTING', 'headerTitle' => 'Manajemen Kategori & Subkategori'])

@section('content')
<div class="-mt-2" x-data="{ addModal: false, editModal: false, editCat: {} }" @keydown.escape.window="addModal = false; editModal = false">
    @php
        $rows = collect();
        foreach ($parents as $parent) {
            $rows->push(['cat' => $parent, 'parent' => null, 'canDelete' => ($parent->products_count + $parent->subcategories->sum('products_count')) === 0]);
            foreach ($parent->subcategories as $sub) {
                $rows->push(['cat' => $sub, 'parent' => $parent, 'canDelete' => $sub->products_count === 0]);
            }
        }
    @endphp

    <!-- Tabel Kategori -->
    <div class="bg-white rounded-2xl border border-warm-beige shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-warm-beige flex items-center justify-between gap-3">
                <h3 class="font-heading font-bold text-sm text-charcoal">Data Kategori & Subkategori</h3>
                <button type="button" @click="addModal = true"
                        class="bg-primary hover:bg-primary-dark text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center space-x-1.5 transition shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-cream/70 text-charcoal uppercase tracking-wider text-[10px] font-bold border-b border-warm-beige">
                        <tr>
                            <th class="py-3.5 px-4">Nama</th>
                            <th class="py-3.5 px-4">Kategori Induk</th>
                            <th class="py-3.5 px-4">Icon</th>
                            <th class="py-3.5 px-4">Deskripsi</th>
                            <th class="py-3.5 px-4 text-center">No. Urutan</th>
                            <th class="py-3.5 px-4 text-center">Produk</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-beige/40">
                        @forelse($rows as $row)
                            @php $cat = $row['cat']; @endphp
                            <tr class="hover:bg-cream/30">
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2 {{ $row['parent'] ? 'pl-4' : '' }}">
                                        @if($row['parent'])
                                            <i data-lucide="corner-down-right" class="w-3.5 h-3.5 text-secondary shrink-0"></i>
                                        @endif
                                        <div>
                                            <span class="font-semibold text-charcoal block">{{ $cat->name }}</span>
                                            <span class="font-mono text-[10px] text-muted-gray">{{ $cat->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-charcoal">
                                    {{ $row['parent'] ? $row['parent']->name : '— (Kategori Utama)' }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center space-x-1.5 text-muted-gray">
                                        <i data-lucide="{{ $cat->icon ?: 'tag' }}" class="w-3.5 h-3.5 text-secondary"></i>
                                        <span class="font-mono text-[10px]">{{ $cat->icon ?: '-' }}</span>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-muted-gray max-w-[220px]">
                                    <span class="line-clamp-2">{{ $cat->description ?: '-' }}</span>
                                </td>
                                <td class="py-3 px-4 text-center font-mono text-charcoal">{{ $cat->sort_order ?? 0 }}</td>
                                <td class="py-3 px-4 text-center font-bold text-secondary">{{ $cat->products_count }} item</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $cat->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div x-data="{ open: false, top: 0, left: 0 }" class="inline-block text-left">
                                        <button type="button" x-ref="btn"
                                                @click="const r = $refs.btn.getBoundingClientRect(); top = (r.bottom + 120 > window.innerHeight) ? r.top - {{ $row['canDelete'] ? 92 : 52 }} : r.bottom + 4; left = r.right - 144; open = !open"
                                                class="inline-flex items-center space-x-1 bg-primary hover:bg-primary-dark text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition shadow-sm">
                                            <span>Aksi</span>
                                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                        </button>

                                        <div x-show="open" x-cloak @click.outside="open = false" @scroll.window.passive="open = false" @keydown.escape.window="open = false"
                                             :style="`top:${top}px; left:${left}px`"
                                             class="fixed z-40 w-36 bg-white rounded-xl shadow-xl border border-warm-beige py-1.5 text-left">
                                            <button type="button" @click="editCat = {{ json_encode($cat) }}; editModal = true; open = false"
                                                    class="w-full flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-charcoal hover:bg-cream transition">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5 text-muted-gray"></i>
                                                <span>Edit</span>
                                            </button>
                                            @if($row['canDelete'])
                                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('{{ $row['parent'] ? 'Hapus subkategori ini?' : 'Hapus kategori utama ini beserta subkategorinya?' }}')"
                                                            class="w-full flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-charcoal hover:bg-rose-50 hover:text-rose-600 transition">
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5 text-muted-gray"></i>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-muted-gray">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Add Category Modal -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto">
        <div @click.outside="addModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 my-8">
            <div class="flex items-center justify-between pb-3 border-b border-warm-beige">
                <span class="font-heading font-bold text-sm text-charcoal">Tambah Kategori / Subkategori</span>
                <button type="button" @click="addModal = false" class="text-muted-gray hover:text-charcoal"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>

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