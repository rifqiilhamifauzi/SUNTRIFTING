@extends('layouts.app', ['title' => 'Masuk — SUN-THRIFTING'])

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-sm border border-warm-beige p-8 text-center">
        <!-- Logo -->
        <div class="w-14 h-14 bg-primary text-white rounded-2xl flex items-center justify-center font-heading font-extrabold text-2xl mx-auto mb-4 shadow-sm border border-warm-beige/40">
            S
        </div>
        
        <h2 class="font-heading font-bold text-2xl text-charcoal mb-2">Selamat Datang di SUN-THRIFTING</h2>
        <p class="text-xs text-muted-gray mb-8 leading-relaxed">
            Marketplace kurasi busana muslim, hijab, dan perlengkapan ibadah preloved berkualitas. Masuk untuk melanjutkan belanja hemat dan bermakna Anda.
        </p>

        <!-- Google OAuth Button -->
        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center space-x-3 bg-white border border-warm-beige hover:border-secondary hover:bg-cream/40 text-charcoal font-semibold py-3 px-4 rounded-xl text-sm transition shadow-sm mb-4">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Masuk dengan Akun Google</span>
        </a>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-warm-beige"></div></div>
            <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-muted-gray">Atau untuk Pengujian</span></div>
        </div>

        <!-- Demo Customer Login Button -->
        <form action="{{ route('auth.demo-login') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="customer@suntrifting.com">
            <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-secondary hover:bg-emerald-600 text-white font-semibold py-3 px-4 rounded-xl text-sm transition shadow-sm">
                <i data-lucide="user-check" class="w-4 h-4"></i>
                <span>Masuk Cepat Demo Customer (1-Klik)</span>
            </button>
            <p class="text-[11px] text-muted-gray mt-2">Login instan tanpa perlu akun Google aktif untuk testing fitur.</p>
        </form>

        <div class="mt-8 pt-6 border-t border-warm-beige/60 text-center">
            <p class="text-xs text-muted-gray">
                Pengelola Marketplace? 
                <a href="{{ route('admin.login') }}" class="font-semibold text-primary hover:text-secondary transition">Login Admin di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
