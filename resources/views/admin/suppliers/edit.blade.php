@extends('layouts.admin', ['title' => 'Edit Supplier — Suntrifting', 'headerTitle' => 'Edit Data Mitra Supplier'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Detail Supplier
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="font-heading font-bold text-base text-gray-900 pb-3 border-b border-gray-100 mb-5">Edit Data: {{ $supplier->name }}</h3>

        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Supplier / Brand Penitip *</label>
                <input type="text" name="name" required value="{{ old('name', $supplier->name) }}" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor WhatsApp / Telepon *</label>
                    <input type="text" name="phone" required value="{{ old('phone', $supplier->phone) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Lengkap Supplier *</label>
                <textarea name="address" rows="2" required 
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">{{ old('address', $supplier->address) }}</textarea>
            </div>

            <!-- Bank Account Info -->
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                <span class="text-xs font-bold text-gray-900 block">Informasi Rekening Bank Pencairan Dana</span>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nama Bank *</label>
                        <input type="text" name="bank_name" required value="{{ old('bank_name', $supplier->bank_name) }}" 
                               class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nomor Rekening *</label>
                        <input type="text" name="bank_account_number" required value="{{ old('bank_account_number', $supplier->bank_account_number) }}" 
                               class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nama Pemilik Rekening *</label>
                        <input type="text" name="bank_account_holder" required value="{{ old('bank_account_holder', $supplier->bank_account_holder) }}" 
                               class="w-full bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Supplier *</label>
                    <select name="status" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                        <option value="active" {{ old('status', $supplier->status) === 'active' ? 'selected' : '' }}>Aktif (Bisa Titip Barang)</option>
                        <option value="inactive" {{ old('status', $supplier->status) === 'inactive' ? 'selected' : '' }}>Non-Aktif (Ditangguhkan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Admin (Opsional)</label>
                    <input type="text" name="notes" value="{{ old('notes', $supplier->notes) }}" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900">Batal</a>
                <button type="submit" class="bg-primary hover:bg-gray-800 text-white font-semibold px-5 py-2.5 rounded-xl text-xs transition shadow-sm">
                    Perbarui Data Supplier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

