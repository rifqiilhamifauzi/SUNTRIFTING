@extends('layouts.app', ['title' => 'Tentang Kami — SUN-THRIFTING'])

@section('content')
<!-- Hero About -->
<section class="bg-gradient-to-br from-[#174C3C] to-[#26745A] text-[#F8F6F0] py-16 sm:py-24 rounded-b-[2.5rem] relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#EAE3D5_1px,transparent_1px)] [background-size:20px_20px]"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-4">
        <span class="inline-block bg-white/10 text-warm-beige text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full border border-white/10">
            Tentang SUN-THRIFTING
        </span>
        <h1 class="font-heading font-extrabold text-3xl sm:text-5xl text-white tracking-tight">
            Secondhand Treasures, <br class="hidden sm:inline">
            <span class="text-warm-beige underline decoration-secondary underline-offset-8">Meaningful Style.</span>
        </h1>
        <p class="text-sm sm:text-base text-warm-beige/90 max-w-2xl mx-auto leading-relaxed font-normal">
            Platform marketplace digital yang mewadahi aktivitas jual beli dan titip jual pakaian muslim, hijab, serta perlengkapan ibadah preloved berkualitas.
        </p>
    </div>
</section>

<!-- Values Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-bold text-secondary tracking-widest uppercase">Pondasi Kami</span>
        <h2 class="font-heading font-bold text-2xl sm:text-3xl text-charcoal mt-1">Nilai Utama Platform</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-warm-beige shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                <i data-lucide="leaf" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading font-bold text-base text-charcoal">Sustainable Fashion</h3>
            <p class="text-xs text-muted-gray mt-2 leading-relaxed">
                Mendukung perpanjangan siklus hidup pakaian muslim untuk mengurangi limbah tekstil dan jejak karbon lingkungan secara nyata.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-warm-beige shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                <i data-lucide="tag" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading font-bold text-base text-charcoal">Affordable Muslim Lifestyle</h3>
            <p class="text-xs text-muted-gray mt-2 leading-relaxed">
                Memberikan akses luas terhadap pakaian muslim berkualitas, abaya, koko, dan mukena premium dengan harga yang jauh lebih hemat.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-warm-beige shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading font-bold text-base text-charcoal">Trusted Marketplace</h3>
            <p class="text-xs text-muted-gray mt-2 leading-relaxed">
                Membangun ekosistem transaksi yang aman, transparan dalam menjelaskan kondisi barang/minus, dan terpercaya bagi penjual maupun pembeli.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-warm-beige shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading font-bold text-base text-charcoal">Inclusive Marketplace</h3>
            <p class="text-xs text-muted-gray mt-2 leading-relaxed">
                Memfasilitasi berbagai kalangan, mulai dari individu yang ingin merapikan lemari (decluttering) hingga butik dan toko busana muslim mitra.
            </p>
        </div>
    </div>
</section>

<!-- Mitra Seller / Titip Jual Section -->
<section id="mitra" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-warm-beige">
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-warm-beige shadow-sm">
        <div class="max-w-3xl mx-auto text-center space-y-4 mb-12">
            <span class="text-xs font-bold text-secondary tracking-widest uppercase">Kemitraan Penjual</span>
            <h2 class="font-heading font-bold text-2xl sm:text-3xl text-charcoal">Bagaimana Cara Menjual / Titip Jual?</h2>
            <p class="text-xs sm:text-sm text-muted-gray leading-relaxed">
                SUN-THRIFTING memudahkan Anda mencairkan nilai dari pakaian muslim dan perlengkapan ibadah yang sudah tidak terpakai namun masih sangat layak.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
            <div class="p-5 rounded-2xl bg-cream border border-warm-beige/80 space-y-2">
                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs mb-3">1</div>
                <h4 class="font-heading font-bold text-sm text-charcoal">Kumpulkan & Pilah</h4>
                <p class="text-[11px] text-muted-gray leading-relaxed">
                    Pilih busana muslim, gamis, abaya, koko, atau mukena preloved Anda yang masih bersih, layak pakai, dan autentik.
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-cream border border-warm-beige/80 space-y-2">
                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs mb-3">2</div>
                <h4 class="font-heading font-bold text-sm text-charcoal">Daftar & Kirim</h4>
                <p class="text-[11px] text-muted-gray leading-relaxed">
                    Hubungi tim kurator SUN-THRIFTING via WhatsApp untuk konfirmasi barang dan pengiriman ke konsinyasi hub terdekat.
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-cream border border-warm-beige/80 space-y-2">
                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs mb-3">3</div>
                <h4 class="font-heading font-bold text-sm text-charcoal">Kurasi & Tayang</h4>
                <p class="text-[11px] text-muted-gray leading-relaxed">
                    Tim kami memotret produk secara profesional, mendeskripsikan kondisi dengan jujur, dan menayangkan di katalog marketplace.
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-cream border border-warm-beige/80 space-y-2">
                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs mb-3">4</div>
                <h4 class="font-heading font-bold text-sm text-charcoal">Bagi Hasil Cair</h4>
                <p class="text-[11px] text-muted-gray leading-relaxed">
                    Saat barang Anda terjual dan pembayaran terverifikasi, 90% hasil penjualan langsung dicatatkan dan dapat dicairkan ke rekening Anda.
                </p>
            </div>
        </div>

        <div class="mt-10 text-center">
            @php
                $waContact = \App\Models\Setting::get('contact_whatsapp', '6281234567890');
            @endphp
            <a href="https://wa.me/{{ $waContact }}?text={{ urlencode('Halo Admin SUN-THRIFTING, saya ingin mendaftar sebagai mitra penjual / titip jual busana muslim.') }}" target="_blank" 
               class="inline-flex items-center space-x-2 bg-secondary hover:bg-emerald-600 text-white text-xs sm:text-sm font-semibold px-8 py-3.5 rounded-full transition shadow-md">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Hubungi Admin Kemitraan via WhatsApp</span>
            </a>
        </div>
    </div>
</section>
@endsection

