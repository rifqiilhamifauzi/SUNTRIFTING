@extends('layouts.admin', ['title' => 'Buat Pencairan Dana Supplier — Suntrifting', 'headerTitle' => 'Pencairan Dana Hasil Penjualan Supplier'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6" 
     x-data="{
        suppliers: {{ json_encode($suppliers->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'bank_name' => $s->bank_name,
            'bank_account_number' => $s->bank_account_number,
            'bank_account_holder' => $s->bank_account_holder,
            'balance' => (float) $s->balance,
        ])->all()) }},
        selectedId: '{{ $selectedSupplier ? $selectedSupplier->id : '' }}',
        amount: 0,
        currentSupplier: null,
        init() {
            if (this.selectedId) {
                this.updateSelected();
            }
        },
        updateSelected() {
            this.currentSupplier = this.suppliers.find(s => s.id == this.selectedId) || null;
            if (this.currentSupplier) {
                this.amount = this.currentSupplier.balance;
            } else {
                this.amount = 0;
            }
        },
        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(num);
        }
     }">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.payouts.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 flex items-center">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Riwayat Pencairan
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="font-heading font-bold text-base text-gray-900 pb-3 border-b border-gray-100 mb-5">Formulir Transfer Pencairan Dana Supplier</h3>

        <form action="{{ route('admin.payouts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Select Supplier -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Mitra Supplier *</label>
                <select name="supplier_id" required x-model="selectedId" @change="updateSelected()"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-semibold text-gray-900">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}">
                            {{ $sup->name }} &mdash; Saldo Tersedia: Rp{{ number_format($sup->balance, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Supplier Bank & Balance Callout Box -->
            <div x-show="currentSupplier" x-cloak class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-emerald-800 font-bold uppercase tracking-wider text-[11px]">Saldo Konsinyasi Siap Cair:</span>
                    <span class="font-heading font-extrabold text-lg text-emerald-900 font-mono" x-text="formatRupiah(currentSupplier ? currentSupplier.balance : 0)"></span>
                </div>
                <div class="pt-2 border-t border-emerald-200 text-emerald-950">
                    <strong>Rekening Transfer Tujuan:</strong> <br>
                    <span x-text="currentSupplier ? currentSupplier.bank_name : ''"></span> &mdash; 
                    <span class="font-mono font-bold" x-text="currentSupplier ? currentSupplier.bank_account_number : ''"></span>
                    (a.n <span x-text="currentSupplier ? currentSupplier.bank_account_holder : ''"></span>)
                </div>
            </div>

            <!-- Amount Input with Balance Validation -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-gray-700">Nominal Pencairan (Rp) *</label>
                    <button type="button" @click="amount = currentSupplier ? currentSupplier.balance : 0" 
                            x-show="currentSupplier && currentSupplier.balance > 0" 
                            class="text-[11px] font-semibold text-accent hover:underline">
                        Cairkan Semua Saldo
                    </button>
                </div>
                <input type="number" name="amount" required x-model="amount" :max="currentSupplier ? currentSupplier.balance : 0" min="1000"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono font-bold text-gray-900 text-sm">
                
                <p x-show="currentSupplier && amount > currentSupplier.balance" x-cloak class="text-[11px] text-rose-600 font-semibold mt-1">
                    Peringatan: Nominal pencairan tidak boleh melebihi saldo tersedia!
                </p>
            </div>

            <input type="hidden" name="payment_method" value="bank_transfer">
            <input type="hidden" name="bank_name" :value="currentSupplier ? currentSupplier.bank_name : 'BCA'">
            <input type="hidden" name="bank_account_number" :value="currentSupplier ? currentSupplier.bank_account_number : ''">
            <input type="hidden" name="bank_account_holder" :value="currentSupplier ? currentSupplier.bank_account_holder : ''">

            <!-- Reference Number -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Referensi Transfer Bank (Opsional)</label>
                <input type="text" name="reference_number" placeholder="Contoh: TRF-BCA-20261111-92810" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono">
            </div>

            <!-- Upload Proof of Transfer -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Bukti Transfer / Struk Bank</label>
                <input type="file" name="proof_image" accept="image/*" 
                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-gray-800 cursor-pointer">
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                <input type="text" name="notes" placeholder="Catatan internal pencairan..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-accent/40">
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.payouts.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900">Batal</a>
                <button type="submit" 
                        :disabled="!currentSupplier || currentSupplier.balance <= 0 || amount <= 0 || amount > currentSupplier.balance"
                        class="bg-accent hover:bg-accent-dark disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white font-semibold px-6 py-2.5 rounded-xl text-xs transition shadow-sm">
                    Konfirmasi Pencairan Dana
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

