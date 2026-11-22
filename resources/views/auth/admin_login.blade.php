@extends('layouts.app', ['title' => 'Login Administrator — Suntrifting'])

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-primary text-secondary rounded-2xl flex items-center justify-center font-heading font-extrabold text-2xl mx-auto mb-3 shadow-sm">
                S
            </div>
            <h2 class="font-heading font-bold text-2xl text-primary">Login Administrator</h2>
            <p class="text-xs text-gray-500 mt-1">Portal internal pengelola Thrift Marketplace</p>
        </div>

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Administrator</label>
                <input type="email" name="email" id="email" required value="{{ old('email', 'admin@suntrifting.com') }}" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required value="password123" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent transition">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-accent focus:ring-accent mr-2">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-3 px-4 rounded-xl text-sm transition shadow-sm flex items-center justify-center space-x-2">
                <i data-lucide="lock" class="w-4 h-4"></i>
                <span>Masuk ke Dashboard</span>
            </button>
        </form>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
            <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-2 text-gray-400">Atau Pengujian Instan</span></div>
        </div>

        <!-- 1-Click Demo Admin Login -->
        <form action="{{ route('auth.demo-login') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="admin@suntrifting.com">
            <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-accent hover:bg-accent-dark text-white font-semibold py-3 px-4 rounded-xl text-sm transition shadow-sm">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Masuk Cepat Demo Admin (1-Klik)</span>
            </button>
        </form>

        <div class="mt-6 p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-800">
            <strong>Kredensial Login Admin:</strong><br>
            Email: <code class="bg-amber-100 px-1 py-0.5 rounded font-mono">admin@suntrifting.com</code><br>
            Password: <code class="bg-amber-100 px-1 py-0.5 rounded font-mono">password123</code>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-xs text-gray-500 hover:text-accent transition">
                &larr; Kembali ke Login Customer
            </a>
        </div>
    </div>
</div>
@endsection

