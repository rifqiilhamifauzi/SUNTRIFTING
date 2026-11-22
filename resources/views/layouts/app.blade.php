<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF9F6] text-[#333333]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Suntrifting — Curated Preloved & Vintage Streetwear Consignment' }}</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#1C1C1C">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Suntrifting">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">

    <!-- Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Custom Theme Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1C1C1C',     // Charcoal / Almost Black
                        secondary: '#FAF9F6',   // Warm White
                        accent: '#84977B',      // Muted Sage
                        'accent-dark': '#697a61',
                        surface: '#FFFFFF',
                        beige: '#EAE5DC',       // Soft Beige
                        dark: '#1C1C1C',
                        body: '#333333',
                    },
                    fontFamily: {
                        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                        sans: ['"Inter"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons & Alpine.js -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; background-color: #FAF9F6; color: #333333; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen pb-16 md:pb-0" x-data="{ mobileMenu: false, installPrompt: null }" x-init="
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        installPrompt = e;
    });
">

    <!-- PWA Install Banner (Top Bar) -->
    <div x-show="installPrompt" x-cloak class="bg-primary text-secondary px-4 py-2.5 text-xs md:text-sm flex items-center justify-between transition-all">
        <div class="flex items-center space-x-2">
            <i data-lucide="smartphone" class="w-4 h-4 text-accent"></i>
            <span>Install <strong>Suntrifting PWA</strong> untuk akses lebih cepat & belanja offline!</span>
        </div>
        <div class="flex items-center space-x-2">
            <button @click="installPrompt.prompt(); installPrompt.userChoice.then(() => installPrompt = null)" class="bg-accent hover:bg-accent-dark text-white px-3 py-1 rounded font-semibold text-xs tracking-wider uppercase transition">Install App</button>
            <button @click="installPrompt = null" class="text-gray-400 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 bg-[#FAF9F6]/90 backdrop-blur-md border-b border-gray-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Brand Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-secondary font-heading font-extrabold text-lg shadow-sm group-hover:scale-105 transition-transform">
                            S
                        </div>
                        <div class="flex flex-col">
                            <span class="font-heading font-extrabold text-lg md:text-xl tracking-tight text-primary">SUNVALLEY</span>
                            <span class="text-[9px] tracking-[0.25em] -mt-1 font-semibold text-accent uppercase">Consignment Archive</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Search Bar -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <form action="{{ route('catalog.index') }}" method="GET" class="w-full relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari jaket vintage, band tee, Levi's 501..." 
                               class="w-full bg-white/80 border border-gray-200 rounded-full pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent transition placeholder:text-gray-400">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-3"></i>
                    </form>
                </div>

                <!-- Nav Links & Actions -->
                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="hover:text-accent transition {{ request()->routeIs('home') ? 'text-accent font-semibold' : 'text-gray-700' }}">Beranda</a>
                    <a href="{{ route('catalog.index') }}" class="hover:text-accent transition {{ request()->routeIs('catalog.*') ? 'text-accent font-semibold' : 'text-gray-700' }}">Katalog</a>
                    
                    @auth
                        @if(Auth::user()->isCustomer())
                            <a href="{{ route('orders.index') }}" class="hover:text-accent transition {{ request()->routeIs('orders.*') ? 'text-accent font-semibold' : 'text-gray-700' }}">Pesanan</a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="bg-primary text-secondary px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-800 transition">Dashboard Admin</a>
                        @endif
                    @endauth
                </nav>

                <!-- User & Cart Icons -->
                <div class="flex items-center space-x-3 md:space-x-4">
                    @auth
                        <!-- Shopping Cart -->
                        @if(Auth::user()->isCustomer())
                            <a href="{{ route('cart.index') }}" class="relative p-2 text-primary hover:text-accent transition" title="Keranjang Belanja">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                                @php
                                    $cartCount = Auth::user()->cart ? Auth::user()->cart->items()->count() : 0;
                                @endphp
                                @if($cartCount > 0)
                                    <span class="absolute top-1 right-1 w-4 h-4 bg-accent text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-accent/20 text-accent flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="hidden lg:inline text-xs font-semibold text-primary max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->isCustomer())
                                    <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 hover:text-accent">
                                        <i data-lucide="user" class="w-3.5 h-3.5 mr-2 text-gray-400"></i> Profil & Alamat
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 hover:text-accent">
                                        <i data-lucide="package" class="w-3.5 h-3.5 mr-2 text-gray-400"></i> Riwayat Pesanan
                                    </a>
                                @else
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 hover:text-accent">
                                        <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 mr-2 text-gray-400"></i> Dashboard Admin
                                    </a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-xs text-rose-600 hover:bg-rose-50">
                                        <i data-lucide="log-out" class="w-3.5 h-3.5 mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center space-x-1.5 bg-primary text-secondary hover:bg-gray-800 px-4 py-2 rounded-full text-xs font-semibold tracking-wide transition shadow-sm">
                            <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                            <span>Masuk</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Alert Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl flex items-center justify-between mb-4 shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center space-x-2.5">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm px-4 py-3 rounded-xl flex items-center justify-between mb-4 shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center space-x-2.5">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-800"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Editorial Footer -->
    <footer class="bg-primary text-secondary border-t border-gray-800 mt-20 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-gray-800">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg bg-accent text-white flex items-center justify-center font-heading font-extrabold text-base">S</div>
                        <span class="font-heading font-bold text-lg tracking-tight text-white">SUNVALLEY THRIFT</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                        Platform konsinyasi digital untuk fesyen preloved dan streetwear vintage terkurasi. Menghubungkan kolektor, supplier pilihan, dan pecinta gaya autentik.
                    </p>
                    <div class="pt-2 text-xs text-gray-400 space-y-1">
                        <p><i data-lucide="shield-check" class="w-4 h-4 text-accent inline mr-1"></i> Sistem Konsinyasi Transparan 90% Hak Supplier</p>
                        <p><i data-lucide="check" class="w-4 h-4 text-accent inline mr-1"></i> Stok 1-of-1 Authenticated Preloved</p>
                    </div>
                </div>

                <div>
                    <h4 class="font-heading font-semibold text-sm tracking-wider uppercase text-gray-200 mb-4">Navigasi Cepat</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-accent transition">Semua Katalog</a></li>
                        <li><a href="{{ route('catalog.index', ['condition' => 'like_new']) }}" class="hover:text-accent transition">Koleksi Like New</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-accent transition">Keranjang Belanja</a></li>
                        <li><a href="{{ route('orders.index') }}" class="hover:text-accent transition">Lacak Pesanan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-heading font-semibold text-sm tracking-wider uppercase text-gray-200 mb-4">Admin & Mitra</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><a href="{{ route('admin.login') }}" class="hover:text-accent transition">Portal Admin</a></li>
                        <li><span class="text-gray-400">WhatsApp: {{ \App\Models\Setting::get('contact_whatsapp', '081234567890') }}</span></li>
                        <li><span class="text-gray-400">Email: {{ \App\Models\Setting::get('contact_email', 'support@suntrifting.com') }}</span></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} Suntrifting Consignment Archive. All rights reserved.</p>
                <p class="mt-2 sm:mt-0 flex items-center space-x-2">
                    <span>Progressive Web App (PWA) Ready</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation (PWA Standard) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-gray-200 px-4 py-2 flex items-center justify-around shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('home') ? 'text-accent font-semibold' : 'text-gray-500' }}">
            <i data-lucide="home" class="w-5 h-5 mb-0.5"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('catalog.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('catalog.*') ? 'text-accent font-semibold' : 'text-gray-500' }}">
            <i data-lucide="grid" class="w-5 h-5 mb-0.5"></i>
            <span>Katalog</span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center text-xs relative {{ request()->routeIs('cart.*') ? 'text-accent font-semibold' : 'text-gray-500' }}">
            <i data-lucide="shopping-bag" class="w-5 h-5 mb-0.5"></i>
            <span>Keranjang</span>
            @auth
                @php $mCart = Auth::user()->cart ? Auth::user()->cart->items()->count() : 0; @endphp
                @if($mCart > 0)
                    <span class="absolute -top-1 right-2 w-4 h-4 bg-accent text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ $mCart }}</span>
                @endif
            @endauth
        </a>
        <a href="{{ route('orders.index') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('orders.*') ? 'text-accent font-semibold' : 'text-gray-500' }}">
            <i data-lucide="package" class="w-5 h-5 mb-0.5"></i>
            <span>Pesanan</span>
        </a>
        <a href="{{ auth()->check() ? route('profile.index') : route('login') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('profile.*') || request()->routeIs('login') ? 'text-accent font-semibold' : 'text-gray-500' }}">
            <i data-lucide="user" class="w-5 h-5 mb-0.5"></i>
            <span>{{ auth()->check() ? 'Akun' : 'Masuk' }}</span>
        </a>
    </nav>

    <!-- Register Service Worker for PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Suntrifting PWA Service Worker Registered', reg.scope))
                    .catch(err => console.error('Service Worker registration failed', err));
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>

