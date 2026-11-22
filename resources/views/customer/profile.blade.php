@extends('layouts.app', ['title' => 'Profil & Alamat — Suntrifting'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-primary">Profil & Alamat Saya</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola data diri dan buku alamat pengiriman belanja Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Profile Form (Left 5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
            <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                <div class="w-10 h-10 rounded-2xl bg-accent/20 text-accent flex items-center justify-center font-bold text-base uppercase">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-heading font-bold text-sm text-primary">{{ $user->name }}</h3>
                    <p class="text-[11px] text-gray-400">{{ $user->email }}</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required value="{{ old('name', $user->name) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Akun (Terkait Akun Google)</label>
                    <input type="email" disabled value="{{ $user->email }}" 
                           class="w-full bg-gray-100 border border-gray-200 rounded-xl px-3.5 py-2 text-xs text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor WhatsApp / Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="081234567890" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-sm">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>

        <!-- Address Book (Right 7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Add Address Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4" x-data="{ showForm: false }">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="font-heading font-bold text-sm text-primary flex items-center">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-accent"></i>
                        Buku Alamat Pengiriman ({{ $addresses->count() }})
                    </h3>
                    <button type="button" @click="showForm = !showForm" class="bg-accent/10 hover:bg-accent/20 text-accent font-semibold px-3 py-1.5 rounded-xl text-xs flex items-center transition">
                        <i data-lucide="plus" class="w-3.5 h-3.5 mr-1"></i>
                        <span x-text="showForm ? 'Tutup Formulir' : 'Tambah Alamat Baru'"></span>
                    </button>
                </div>

                <!-- New Address Form -->
                <div x-show="showForm" x-cloak class="p-4 bg-gray-50 rounded-2xl border border-gray-200 mb-4">
                    <h4 class="font-heading font-bold text-xs text-primary mb-3">Formulir Tambah Alamat</h4>
                    <form action="{{ route('profile.address.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Label Alamat *</label>
                                <input type="text" name="label" placeholder="Rumah / Kantor / Kos" required 
                                       class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nama Penerima *</label>
                                <input type="text" name="recipient_name" required value="{{ $user->name }}" 
                                       class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nomor Telepon Penerima *</label>
                            <input type="text" name="phone" required value="{{ $user->phone }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Alamat Lengkap *</label>
                            <textarea name="address_line" rows="2" required 
                                      class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40"></textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Provinsi *</label>
                                <select name="province" required class="w-full bg-white border border-gray-200 rounded-xl px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov }}">{{ $prov }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Kota/Kabupaten *</label>
                                <input type="text" name="city" required class="w-full bg-white border border-gray-200 rounded-xl px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-700 mb-1">Kode Pos *</label>
                                <input type="text" name="postal_code" required class="w-full bg-white border border-gray-200 rounded-xl px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                            </div>
                        </div>

                        <label class="flex items-center text-xs text-gray-600 cursor-pointer pt-1">
                            <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300 text-accent focus:ring-accent mr-2">
                            <span>Jadikan alamat utama pengiriman</span>
                        </label>

                        <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-xl text-xs transition">
                            Simpan Alamat Baru
                        </button>
                    </form>
                </div>

                <!-- Existing Addresses List -->
                @if($addresses->isEmpty())
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada alamat tersimpan. Silakan tambahkan alamat pengiriman.</p>
                @else
                    <div class="space-y-3">
                        @foreach($addresses as $addr)
                            <div class="p-4 rounded-2xl border {{ $addr->is_default ? 'border-accent bg-accent/5' : 'border-gray-200 bg-white' }} flex items-start justify-between">
                                <div class="space-y-1 text-xs">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-heading font-bold text-gray-900">{{ $addr->label }}</span>
                                        @if($addr->is_default)
                                            <span class="bg-accent text-white text-[9px] font-bold px-2 py-0.5 rounded-full">Utama</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-800 font-semibold">{{ $addr->recipient_name }} ({{ $addr->phone }})</p>
                                    <p class="text-gray-500 leading-relaxed">{{ $addr->full_address }}</p>
                                </div>

                                <div class="flex items-center space-x-2 shrink-0">
                                    @if(!$addr->is_default)
                                        <form action="{{ route('profile.address.default', $addr->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-semibold text-accent hover:underline">
                                                Set Utama
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('profile.address.destroy', $addr->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus alamat ini?')" class="text-gray-400 hover:text-rose-500 p-1">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

