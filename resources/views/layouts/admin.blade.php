<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50 text-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Console — SUN-THRIFTING Muslim Thrift' }}</title>

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
                        primary: '#174C3C',
                        'primary-dark': '#0E2E24',
                        secondary: '#26745A',
                        accent: '#26745A',
                        'accent-dark': '#174C3C',
                        cream: '#F8F6F0',
                        'warm-beige': '#EAE3D5',
                        charcoal: '#252A27',
                        surface: '#FFFFFF',
                        sidebar: '#11382C',
                    },
                    fontFamily: {
                        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                        sans: ['"Inter"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons & Chart.js & Alpine.js -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-[#11382C] text-gray-200 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto border-r border-[#174C3C]">
        <!-- Brand Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-secondary text-white flex items-center justify-center font-heading font-extrabold text-base shadow-sm">S</div>
                <div class="flex flex-col">
                    <span class="font-heading font-bold text-white tracking-wide text-sm">SUN-THRIFTING</span>
                    <span class="text-[9px] text-[#EAE3D5] font-semibold tracking-wider uppercase">Admin Console</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-[#EAE3D5]/60">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                <span>Overview Dashboard</span>
            </a>

            <div class="pt-4 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-[#EAE3D5]/60">Katalog & Konsinyasi</div>

            <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i data-lucide="shirt" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.products.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                <span>Produk Muslim Thrift</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i data-lucide="tags" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.categories.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                <span>Kategori & Subkategori</span>
            </a>

            <a href="{{ route('admin.suppliers.index') }}" class="flex items-center px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i data-lucide="store" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.suppliers.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                <span>Mitra Seller / Supplier</span>
            </a>

            <div class="pt-4 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-[#EAE3D5]/60">Transaksi & Pesanan</div>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center">
                    <i data-lucide="shopping-cart" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.orders.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                    <span>Pesanan Customer</span>
                </div>
            </a>

            @php
                $pendingPayCount = \App\Models\Payment::where('status', 'pending')->whereNotNull('proof_image')->count();
            @endphp
            <a href="{{ route('admin.payments.index') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.payments.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center">
                    <i data-lucide="receipt" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.payments.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                    <span>Verifikasi Pembayaran</span>
                </div>
                @if($pendingPayCount > 0)
                    <span class="bg-amber-400 text-black text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingPayCount }}</span>
                @endif
            </a>

            <div class="pt-4 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-[#EAE3D5]/60">Keuangan & Bagi Hasil</div>

            <a href="{{ route('admin.profit-sharing.index') }}" class="flex items-center px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.profit-sharing.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i data-lucide="pie-chart" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.profit-sharing.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                <span>Bagi Hasil (Profit Sharing)</span>
            </a>

            <a href="{{ route('admin.payouts.index') }}" class="flex items-center px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.payouts.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i data-lucide="wallet" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.payouts.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                <span>Pencairan Dana (Payout)</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i data-lucide="file-text" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.reports.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                <span>Laporan Keuangan</span>
            </a>

            <div class="pt-4 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-[#EAE3D5]/60">Konfigurasi</div>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2.5 text-xs font-medium rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-white/15 text-white font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i data-lucide="settings" class="w-4 h-4 mr-3 {{ request()->routeIs('admin.settings.*') ? 'text-emerald-300' : 'text-gray-400' }}"></i>
                <span>Pengaturan Sistem</span>
            </a>
        </div>

        <!-- Admin Profile Footer -->
        <div class="p-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center space-x-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                    A
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-[#EAE3D5]/70">Admin Pengelola</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-400 rounded-lg hover:bg-white/10 transition" title="Logout">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-30">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="text-sm font-semibold text-gray-700">
                    {{ $headerTitle ?? 'Dashboard Management' }}
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center text-xs font-medium text-primary hover:text-secondary transition bg-cream hover:bg-emerald-50 px-3 py-1.5 rounded-lg border border-warm-beige">
                    <i data-lucide="external-link" class="w-3.5 h-3.5 mr-1.5"></i> Lihat Marketplace
                </a>
            </div>
        </header>

        <!-- Flash Alert Messages -->
        <div class="p-6 pb-0">
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

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm px-4 py-3 rounded-xl mb-4 shadow-sm">
                    <p class="font-semibold mb-1">Terdapat kesalahan pengisian formulir:</p>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
