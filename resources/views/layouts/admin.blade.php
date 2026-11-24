<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin — SUN-THRIFTING' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: { DEFAULT: '#174C3C', dark: '#12392E' },
                        secondary: '#26745A',
                        accent: { DEFAULT: '#26745A', dark: '#1F6049' },
                        cream: '#F8F6F0',
                        'warm-beige': '#EAE3D5',
                        charcoal: '#2B2B2B',
                        'muted-gray': '#6B7280',
                    },
                },
            },
        }
    </script>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>[x-cloak]{display:none !important;}</style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

@php
    // Ambil route pertama yang ada dari daftar kandidat, supaya tidak error kalau nama route berbeda.
    $r = function (array $names) {
        foreach ($names as $n) {
            if (\Illuminate\Support\Facades\Route::has($n)) return route($n);
        }
        return '#';
    };

    $menus = [
        [
            'label' => 'Data Master',
            'icon'  => 'database',
            'active' => ['admin.products.*', 'admin.categories.*', 'admin.subcategories.*', 'admin.suppliers.*'],
            'items' => [
                ['label' => 'Produk Muslim Thrift',   'icon' => 'shirt',    'url' => $r(['admin.products.index'])],
                ['label' => 'Kategori & Subkategori', 'icon' => 'tag',      'url' => $r(['admin.categories.index'])],
                ['label' => 'Mitra Seller / Supplier', 'icon' => 'store',   'url' => $r(['admin.suppliers.index'])],
            ],
        ],
        [
            'label' => 'Pesanan',
            'icon'  => 'shopping-cart',
            'active' => ['admin.orders.*'],
            'items' => [
                ['label' => 'Pesanan Customer', 'icon' => 'shopping-cart', 'url' => $r(['admin.orders.index'])],
            ],
        ],
        [
            'label' => 'Keuangan',
            'icon'  => 'wallet',
            'active' => ['admin.payments.*', 'admin.profit*', 'admin.profit-sharing.*', 'admin.payouts.*'],
            'items' => [
                ['label' => 'Verifikasi Pembayaran', 'icon' => 'receipt-text', 'url' => $r(['admin.payments.index'])],
                ['label' => 'Bagi Hasil (Profit Sharing)', 'icon' => 'pie-chart', 'url' => $r(['admin.profit-sharing.index', 'admin.profit_sharing.index', 'admin.profits.index', 'admin.profit.index'])],
                ['label' => 'Pencairan Dana', 'icon' => 'banknote', 'url' => $r(['admin.payouts.index'])],
            ],
        ],
        [
            'label' => 'Laporan',
            'icon'  => 'file-bar-chart',
            'active' => ['admin.reports.*', 'admin.laporan.*'],
            'items' => [
                ['label' => 'Laporan', 'icon' => 'file-bar-chart', 'url' => $r(['admin.reports.index', 'admin.laporan.index'])],
            ],
        ],
    ];

    $profileUrl = $r(['admin.profile.edit', 'admin.profile', 'admin.settings.index', 'admin.settings', 'profile.edit']);

    $dashboardUrl = $r(['admin.dashboard', 'admin.index']);
    $dashboardActive = request()->routeIs('admin.dashboard') || request()->routeIs('admin.index') || request()->is('admin');
    $logoutUrl = $r(['admin.logout', 'logout']);
@endphp

<header x-data="{ mobileOpen: false }" class="bg-primary text-white shadow-md sticky top-0 z-40">
    <div class="px-4 lg:px-6 h-14 flex items-center justify-between gap-4">
        {{-- Brand --}}
        <a href="{{ $dashboardUrl }}" class="flex items-center space-x-2.5 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-accent flex items-center justify-center font-heading font-extrabold text-sm">S</div>
            <div class="leading-tight hidden sm:block">
                <div class="font-heading font-extrabold text-sm tracking-wide">SUN-THRIFTING</div>
                <div class="text-[9px] uppercase tracking-widest text-white/60 font-semibold">Admin Console</div>
            </div>
        </a>

        {{-- Menu desktop --}}
        <nav class="hidden lg:flex items-center space-x-1 flex-1 ml-6">
            <a href="{{ $dashboardUrl }}"
               class="flex items-center space-x-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $dashboardActive ? 'bg-white/15' : 'hover:bg-white/10 text-white/80' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                <span>Dashboard</span>
            </a>

            @foreach($menus as $menu)
                @php $isActive = request()->routeIs(...$menu['active']); @endphp
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                    <button type="button" @click="open = !open"
                            class="flex items-center space-x-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition {{ $isActive ? 'bg-white/15' : 'hover:bg-white/10 text-white/80' }}">
                        <i data-lucide="{{ $menu['icon'] }}" class="w-4 h-4"></i>
                        <span>{{ $menu['label'] }}</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-cloak x-transition.opacity.duration.150ms
                         class="absolute left-0 top-full pt-1.5 w-60">
                        <div class="bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 overflow-hidden">
                            @foreach($menu['items'] as $item)
                                <a href="{{ $item['url'] }}"
                                   class="flex items-center space-x-2.5 px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:text-accent transition">
                                    <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 text-gray-400"></i>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </nav>

        {{-- Kanan: marketplace + user --}}
        <div class="flex items-center space-x-2">
            <a href="{{ url('/') }}" target="_blank"
               class="hidden md:flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-semibold transition">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>Lihat Marketplace</span>
            </a>

            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" @click="open = !open" class="flex items-center space-x-2 pl-2 pr-1 py-1 rounded-lg hover:bg-white/10 transition">
                    <div class="text-right leading-tight hidden sm:block">
                        <div class="text-xs font-bold">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="text-[10px] text-white/60">Admin Pengelola</div>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </button>

                <div x-show="open" x-cloak x-transition.opacity.duration.150ms
                     class="absolute right-0 mt-1.5 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 text-gray-700">
                    <a href="{{ $profileUrl }}" class="flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold hover:bg-gray-50">
                        <i data-lucide="user-cog" class="w-4 h-4 text-gray-400"></i><span>Profil & Pengaturan</span>
                    </a>
                    <a href="{{ url('/') }}" target="_blank" class="md:hidden flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold hover:bg-gray-50">
                        <i data-lucide="external-link" class="w-4 h-4 text-gray-400"></i><span>Lihat Marketplace</span>
                    </a>
                    <form action="{{ $logoutUrl }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                            <i data-lucide="log-out" class="w-4 h-4"></i><span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Hamburger mobile --}}
            <button type="button" @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-lg hover:bg-white/10">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div x-show="mobileOpen" x-cloak class="lg:hidden border-t border-white/10 px-4 py-3 space-y-3">
        <a href="{{ $dashboardUrl }}" class="flex items-center space-x-2 px-2 py-2 rounded-lg text-sm font-semibold {{ $dashboardActive ? 'bg-white/15' : '' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i><span>Dashboard</span>
        </a>
        @foreach($menus as $menu)
            <div>
                <div class="px-2 text-[10px] font-bold uppercase tracking-widest text-white/50 mb-1">{{ $menu['label'] }}</div>
                @foreach($menu['items'] as $item)
                    <a href="{{ $item['url'] }}" class="flex items-center space-x-2 px-2 py-2 rounded-lg text-sm font-semibold text-white/90 hover:bg-white/10">
                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i><span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</header>

    {{-- Judul halaman --}}
    <div class="bg-cream border-b border-warm-beige">
        <div class="px-4 lg:px-6 py-3 min-h-[3.75rem] flex items-center justify-between gap-3">
            <h1 class="font-heading font-bold text-base text-charcoal">{{ $headerTitle ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-2">
                @yield('header_actions')
            </div>
        </div>
    </div>

    <main class="px-4 lg:px-6 py-6">
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl px-4 py-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold rounded-xl px-4 py-3">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-xl px-4 py-3">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => { lucide.createIcons(); });
    </script>
    @stack('scripts')
</body>
</html>