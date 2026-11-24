@extends('layouts.app', ['title' => 'Login Administrator — SUN-THRIFTING'])

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-sm border border-warm-beige p-8">
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-primary text-white rounded-2xl flex items-center justify-center font-heading font-extrabold text-2xl mx-auto mb-3 shadow-sm border border-warm-beige/40">
                S
            </div>
            <h2 class="font-heading font-bold text-2xl text-charcoal">Login Administrator</h2>
            <p class="text-xs text-muted-gray mt-1">Portal pengelola SUN-THRIFTING Marketplace</p>
        </div>

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-charcoal mb-1">Email Administrator</label>
                <input type="email" name="email" id="email" required value="{{ old('email', 'admin@suntrifting.com') }}" 
                       class="w-full bg-cream border border-warm-beige rounded-xl px-4 py-2.5 text-xs text-charcoal focus:ring-secondary focus:border-secondary transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-charcoal mb-1">Password</label>
                <input type="password" name="password" id="password" required value="password123" 
                       class="w-full bg-cream border border-warm-beige rounded-xl px-4 py-2.5 text-xs text-charcoal focus:ring-secondary focus:border-secondary transition">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-charcoal cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-secondary focus:ring-secondary mr-2">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-4 rounded-xl text-xs sm:text-sm transition shadow-sm flex items-center justify-center space-x-2">
                <i data-lucide="lock" class="w-4 h-4"></i>
                <span>Masuk ke Dashboard Admin</span>
            </button>
        </form>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-warm-beige"></div></div>
            <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-muted-gray">Atau Pengujian Instan</span></div>
        </div>

        <!-- 1-Click Demo Admin Login -->
        <form action="{{ route('auth.demo-login') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="admin@suntrifting.com">
            <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-secondary hover:bg-emerald-600 text-white font-semibold py-3 px-4 rounded-xl text-xs sm:text-sm transition shadow-sm">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Masuk Cepat Demo Admin (1-Klik)</span>
            </button>
        </form>

        <div class="mt-6 p-3 bg-cream rounded-xl border border-warm-beige text-[11px] text-charcoal">
            <strong>Kredensial Login Admin:</strong><br>
            Email: <code class="bg-white px-1.5 py-0.5 rounded border border-warm-beige font-mono text-primary font-bold">admin@suntrifting.com</code><br>
            Password: <code class="bg-white px-1.5 py-0.5 rounded border border-warm-beige font-mono text-primary font-bold">password123</code>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-xs text-muted-gray hover:text-primary transition">
                &larr; Kembali ke Login Customer
            </a>
        </div>
    </div>
</div>
@endsection
