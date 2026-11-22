@extends('layouts.app', ['title' => 'Anda Sedang Offline — Suntrifting'])

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 text-center">
    <div class="max-w-md w-full bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
        <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="wifi-off" class="w-8 h-8"></i>
        </div>

        <h2 class="font-heading font-extrabold text-2xl text-primary">Koneksi Internet Terputus</h2>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">
            Aplikasi Suntrifting PWA berjalan dalam mode offline. Halaman dan data statis yang telah tersimpan di cache tetap dapat Anda akses.
        </p>

        <div class="p-3 bg-amber-50 rounded-2xl border border-amber-200 text-[11px] text-amber-800 text-left my-6 space-y-1">
            <p class="font-semibold flex items-center"><i data-lucide="shield-alert" class="w-3.5 h-3.5 mr-1 text-amber-600"></i> Mode Keamanan Offline:</p>
            <p>Fitur checkout, pembayaran, dan perubahan data penting dinonaktifkan sementara hingga koneksi internet Anda kembali stabil demi keamanan finansial.</p>
        </div>

        <div class="space-y-2">
            <button onclick="window.location.reload()" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-2xl text-xs transition flex items-center justify-center space-x-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Coba Hubungkan Ulang</span>
            </button>
            <a href="{{ route('home') }}" class="block text-xs font-semibold text-accent hover:underline py-2">
                &larr; Kembali ke Beranda Cache
            </a>
        </div>
    </div>
</div>
@endsection

