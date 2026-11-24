<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8F6F0] text-[#252A27]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SUN-THRIFTING — Muslim Fashion & Lifestyle Thrift Marketplace' }}</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#174C3C">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SUN-THRIFTING">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">

    <!-- Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Islamic Lifestyle Theme Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#174C3C',       // Deep Forest Green
                        'primary-dark': '#11382C',
                        secondary: '#26745A',     // Emerald Green
                        accent: '#26745A',        // Emerald Accent
                        'accent-dark': '#174C3C',
                        cream: '#F8F6F0',         // Soft Cream background
                        surface: '#FFFFFF',
                        'warm-beige': '#EAE3D5',  // Warm Beige
                        charcoal: '#252A27',      // Primary Text
                        'muted-gray': '#858B87',  // Muted Gray
                        body: '#252A27',
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
        body { font-family: 'Inter', sans-serif; background-color: #F8F6F0; color: #252A27; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen pb-16 md:pb-0" x-data="{ mobileMenu: false, categoryMenu: false, installPrompt: null }" x-init="
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        installPrompt = e;
    });
">

    <!-- PWA Install Banner (Top Bar) -->
    <div x-show="installPrompt" x-cloak class="bg-primary text-cream px-4 py-2.5 text-xs md:text-sm flex items-center justify-between transition-all border-b border-primary-dark">
        <div class="flex items-center space-x-2">
            <i data-lucide="smartphone" class="w-4 h-4 text-warm-beige"></i>
            <span>Install <strong>SUN-THRIFTING PWA</strong> untuk akses instan & pengalaman aplikasi lebih nyaman!</span>
        </div>
        <div class="flex items-center space-x-2">
            <button @click="installPrompt.prompt(); installPrompt.userChoice.then(() => installPrompt = null)" class="bg-secondary hover:bg-emerald-600 text-white px-3 py-1 rounded-lg font-semibold text-xs tracking-wider uppercase transition">Install App</button>
            <button @click="installPrompt = null" class="text-warm-beige/70 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    </div>

    <!-- Top Announcement Bar -->
    <div class="bg-[#11382C] text-[#EAE3D5] text-[11px] py-1.5 px-4 text-center font-medium tracking-wide hidden md:block">
        <span>✨ Secondhand Treasures, Meaningful Style • Kurasi Fashion & Kebutuhan Muslim Preloved Terpercaya</span>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 bg-[#F8F6F0]/95 backdrop-blur-md border-b border-[#EAE3D5]/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Brand Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white font-heading font-extrabold text-xl shadow-sm group-hover:scale-105 transition-transform border border-warm-beige/30">
                            S
                        </div>
                        <div class="flex flex-col">
                            <span class="font-heading font-extrabold text-lg md:text-xl tracking-tight text-primary">SUN-THRIFTING</span>
                            <span class="text-[9px] tracking-[0.2em] -mt-1 font-semibold text-secondary uppercase">Muslim Thrift Archive</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Search Bar -->
                <div class="hidden md:flex flex-1 max-w-md mx-6">
                    <form action="{{ route('catalog.index') }}" method="GET" class="w-full relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari gamis, abaya, koko kurta, mukena silk..." 
                               class="w-full bg-white border border-[#EAE3D5] rounded-full pl-10 pr-4 py-2 text-sm text-charcoal focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition placeholder:text-muted-gray shadow-sm">
                        <i data-lucide="search" class="w-4 h-4 text-muted-gray absolute left-3.5 top-2.5"></i>
                    </form>
                </div>

                <!-- Nav Links & Actions -->
                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="hover:text-primary transition {{ request()->routeIs('home') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-charcoal' }}">Beranda</a>
                    <a href="{{ route('catalog.index') }}" class="hover:text-primary transition {{ request()->routeIs('catalog.*') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-charcoal' }}">Jelajahi Produk</a>
                    <a href="{{ route('about') }}" class="hover:text-primary transition {{ request()->routeIs('about') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-charcoal' }}">Tentang Kami</a>
                    
                    @auth
                        @if(Auth::user()->isCustomer())
                            <a href="{{ route('orders.index') }}" class="hover:text-primary transition {{ request()->routeIs('orders.*') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-charcoal' }}">Pesanan</a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="bg-primary text-cream px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-primary-dark transition shadow-sm">Dashboard Admin</a>
                        @endif
                    @endauth
                </nav>

                <!-- User & Action Icons -->
                <div class="flex items-center space-x-2 md:space-x-3">
                    @auth
                        @if(Auth::user()->isCustomer())
                            <!-- Wishlist Icon -->
                            <a href="{{ route('wishlist.index') }}" class="relative p-2 text-charcoal hover:text-primary transition rounded-full hover:bg-white/60" title="Wishlist Saya">
                                <i data-lucide="heart" class="w-5 h-5 {{ request()->routeIs('wishlist.*') ? 'fill-primary text-primary' : '' }}"></i>
                                @php
                                    $wCount = Auth::user()->wishlists()->count();
                                @endphp
                                @if($wCount > 0)
                                    <span class="absolute top-1 right-1 w-4 h-4 bg-primary text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                        {{ $wCount }}
                                    </span>
                                @endif
                            </a>

                            <!-- Shopping Cart Icon -->
                            <a href="{{ route('cart.index') }}" class="relative p-2 text-charcoal hover:text-primary transition rounded-full hover:bg-white/60" title="Keranjang Belanja">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                                @php
                                    $cartCount = Auth::user()->cart ? Auth::user()->cart->items()->count() : 0;
                                @endphp
                                @if($cartCount > 0)
                                    <span class="absolute top-1 right-1 w-4 h-4 bg-secondary text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none p-1 rounded-full hover:bg-white/80 transition">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border border-warm-beige">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs uppercase border border-primary/20">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="hidden lg:inline text-xs font-semibold text-charcoal max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-muted-gray"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-cloak class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-warm-beige/60 py-2 z-50">
                                <div class="px-4 py-2 border-b border-warm-beige/40">
                                    <p class="text-xs font-bold text-charcoal truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-muted-gray truncate">{{ Auth::user()->email }}</p>
                                    <span class="inline-block mt-1 text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full {{ Auth::user()->isAdmin() ? 'bg-primary/10 text-primary' : 'bg-secondary/10 text-secondary' }}">
                                        {{ Auth::user()->isAdmin() ? 'Administrator' : 'Customer' }}
                                    </span>
                                </div>
                                @if(Auth::user()->isCustomer())
                                    <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2.5 text-xs text-charcoal hover:bg-cream hover:text-primary transition">
                                        <i data-lucide="user" class="w-3.5 h-3.5 mr-2.5 text-muted-gray"></i> Profil & Alamat
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-2.5 text-xs text-charcoal hover:bg-cream hover:text-primary transition">
                                        <i data-lucide="package" class="w-3.5 h-3.5 mr-2.5 text-muted-gray"></i> Riwayat Pesanan
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center px-4 py-2.5 text-xs text-charcoal hover:bg-cream hover:text-primary transition">
                                        <i data-lucide="heart" class="w-3.5 h-3.5 mr-2.5 text-muted-gray"></i> Wishlist Saya
                                    </a>
                                @else
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-xs text-charcoal hover:bg-cream hover:text-primary transition">
                                        <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 mr-2.5 text-muted-gray"></i> Dashboard Admin
                                    </a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST" class="border-t border-warm-beige/40 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 transition">
                                        <i data-lucide="log-out" class="w-3.5 h-3.5 mr-2.5"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center space-x-1.5 bg-primary text-cream hover:bg-primary-dark px-4 py-2 rounded-full text-xs font-semibold tracking-wide transition shadow-sm">
                            <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                            <span>Masuk</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Search Bar (under logo on small screens) -->
            <div class="md:hidden pb-3">
                <form action="{{ route('catalog.index') }}" method="GET" class="w-full relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari gamis, koko, mukena, hijab..." 
                           class="w-full bg-white border border-[#EAE3D5] rounded-full pl-9 pr-4 py-2 text-xs text-charcoal focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition placeholder:text-muted-gray">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-muted-gray absolute left-3 top-2.5"></i>
                </form>
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
    <footer class="bg-[#174C3C] text-[#F8F6F0] border-t border-[#11382C] mt-20 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-[#26745A]/40">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-secondary text-white flex items-center justify-center font-heading font-extrabold text-lg shadow-sm">S</div>
                        <div>
                            <span class="font-heading font-bold text-xl tracking-tight text-white block">SUN-THRIFTING</span>
                            <span class="text-[10px] tracking-widest uppercase text-warm-beige block">Secondhand Treasures, Meaningful Style</span>
                        </div>
                    </div>
                    <p class="text-[#EAE3D5]/80 text-sm leading-relaxed max-w-sm">
                        Platform marketplace digital untuk kurasi fesyen muslim, hijab, dan perlengkapan ibadah preloved berkualitas. Mendukung gaya hidup muslim yang berkelanjutan dan terjangkau.
                    </p>
                    <div class="pt-2 text-xs text-[#EAE3D5]/90 space-y-1.5">
                        <p><i data-lucide="leaf" class="w-3.5 h-3.5 text-emerald-400 inline mr-1.5"></i> Sustainable Fashion: Kurangi limbah tekstil dengan produk preloved</p>
                        <p><i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400 inline mr-1.5"></i> Trusted Marketplace: Deskripsi kondisi transparan & kurasi terpercaya</p>
                    </div>
                </div>

                <div>
                    <h4 class="font-heading font-semibold text-xs tracking-wider uppercase text-warm-beige mb-4">Kategori Utama</h4>
                    <ul class="space-y-2 text-xs text-[#EAE3D5]/80">
                        <li><a href="{{ route('catalog.index', ['category' => 'pakaian-muslim-wanita']) }}" class="hover:text-white transition">Pakaian Muslim Wanita</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'pakaian-muslim-pria']) }}" class="hover:text-white transition">Pakaian Muslim Pria</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'hijab-kerudung']) }}" class="hover:text-white transition">Hijab & Kerudung</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'perlengkapan-ibadah']) }}" class="hover:text-white transition">Perlengkapan Ibadah</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'fashion-muslim-anak']) }}" class="hover:text-white transition">Fashion Muslim Anak</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-heading font-semibold text-xs tracking-wider uppercase text-warm-beige mb-4">Informasi & Bantuan</h4>
                    <ul class="space-y-2 text-xs text-[#EAE3D5]/80">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">Tentang SUN-THRIFTING</a></li>
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition">Semua Produk</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-white transition">Portal Pengelola (Admin)</a></li>
                        <li><span class="text-[#EAE3D5]/60">WhatsApp: {{ \App\Models\Setting::get('contact_whatsapp', '081234567890') }}</span></li>
                        <li><span class="text-[#EAE3D5]/60">Email: {{ \App\Models\Setting::get('contact_email', 'support@sun-thrifting.com') }}</span></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-[#EAE3D5]/70">
                <p>&copy; {{ date('Y') }} SUN-THRIFTING. All rights reserved. Secondhand Treasures, Meaningful Style.</p>
                <p class="mt-2 sm:mt-0 flex items-center space-x-2">
                    <span>Progressive Web App (PWA) Certified</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation (PWA Standard) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-[#F8F6F0]/95 backdrop-blur-md border-t border-[#EAE3D5] px-2 py-2 flex items-center justify-around shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-muted-gray' }}">
            <i data-lucide="home" class="w-5 h-5 mb-0.5"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('catalog.index') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('catalog.*') ? 'text-primary font-bold' : 'text-muted-gray' }}">
            <i data-lucide="grid" class="w-5 h-5 mb-0.5"></i>
            <span>Katalog</span>
        </a>
        @auth
            <a href="{{ route('wishlist.index') }}" class="flex flex-col items-center text-[10px] relative {{ request()->routeIs('wishlist.*') ? 'text-primary font-bold' : 'text-muted-gray' }}">
                <i data-lucide="heart" class="w-5 h-5 mb-0.5"></i>
                <span>Wishlist</span>
                @php $mWish = Auth::user()->wishlists()->count(); @endphp
                @if($mWish > 0)
                    <span class="absolute -top-1 right-1 w-3.5 h-3.5 bg-primary text-white text-[8px] font-bold rounded-full flex items-center justify-center">{{ $mWish }}</span>
                @endif
            </a>
            <a href="{{ route('cart.index') }}" class="flex flex-col items-center text-[10px] relative {{ request()->routeIs('cart.*') ? 'text-primary font-bold' : 'text-muted-gray' }}">
                <i data-lucide="shopping-bag" class="w-5 h-5 mb-0.5"></i>
                <span>Keranjang</span>
                @php $mCart = Auth::user()->cart ? Auth::user()->cart->items()->count() : 0; @endphp
                @if($mCart > 0)
                    <span class="absolute -top-1 right-2 w-3.5 h-3.5 bg-secondary text-white text-[8px] font-bold rounded-full flex items-center justify-center">{{ $mCart }}</span>
                @endif
            </a>
            <a href="{{ route('profile.index') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('profile.*') ? 'text-primary font-bold' : 'text-muted-gray' }}">
                <i data-lucide="user" class="w-5 h-5 mb-0.5"></i>
                <span>Akun</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('login') ? 'text-primary font-bold' : 'text-muted-gray' }}">
                <i data-lucide="user" class="w-5 h-5 mb-0.5"></i>
                <span>Masuk</span>
            </a>
        @endauth
    </nav>

    <!-- Register Service Worker for PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SUN-THRIFTING PWA Service Worker Registered', reg.scope))
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
