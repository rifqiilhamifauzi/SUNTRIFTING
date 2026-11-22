# Suntrifting — Thrift Marketplace PWA
> **Platform E-Commerce Barang Preloved / Thrift Berbasis Konsinyasi (Titip Jual) dengan Dukungan Progressive Web App (PWA)**

---

## 1. Tentang Aplikasi

**Suntrifting** adalah web application modern berbasis Progressive Web App (PWA) yang memfasilitasi model bisnis konsinyasi (titip jual) untuk produk thrift / preloved. 

Dalam platform ini:
- **Supplier** menitipkan barang preloved berkualitas ke pengelola platform.
- **Admin** mendata barang, menentukan harga kesepakatan dan harga jual, mempublikasikan produk, memverifikasi pembayaran, mengatur pengiriman, dan mengelola pembagian hasil (profit sharing) serta pencairan dana supplier (payout).
- **Customer** dapat menjelajahi katalog thrift yang unik (1-of-1 inventory), login cepat dengan Google OAuth atau akun demo, memasukkan ke keranjang belanja, checkout aman dengan locking database, melakukan pembayaran transfer manual dengan bukti transfer, dan melacak status pesanan secara real-time.
- **Progressive Web App (PWA)** memungkinkan aplikasi di-install di smartphone (Android/iOS) maupun desktop (Chrome/Edge/Safari), memiliki app icon, splash screen mandiri (*standalone*), serta fallback halaman offline yang informatif.

---

## 2. Fitur Utama & Logika Bisnis Konsinyasi

### A. Sistem Konsinyasi & Pembagian Hasil (Revenue Sharing)
- **Persentase Default:** 90% Hak Supplier, 10% Komisi Admin (dapat disesuaikan di Pengaturan Admin).
- **Snapshot Immutability:** Persentase dan nominal pembagian hasil dihitung dan dikunci dalam bentuk snapshot permanen pada tabel `profit_shares` saat pembayaran diverifikasi admin. Perubahan persentase di masa depan tidak mempengaruhi data historis transaksi.
- **Akurasi Finansial:** Seluruh perhitungan moneter menggunakan tipe data `DECIMAL(12, 2)` dengan database transaction (`DB::transaction`) guna mencegah pembulatan floating-point error.
- **Pemisahan Finansial:** Sistem memisahkan secara tegas antara **Omzet Penjualan**, **Pendapatan Komisi Admin**, dan **Kewajiban Hak Supplier (Liabilities)**.

### B. Perlindungan Stok Unik Thrift (1-of-1 Inventory)
- Barang thrift bersifat unik (stok 1).
- Saat customer melakukan proses checkout, sistem menerapkan **Pessimistic Locking (`lockForUpdate`)** dan mengubah status produk menjadi `reserved` untuk mencegah kondisi *race condition* (dua pembeli membeli barang yang sama secara bersamaan).
- Ketika pembayaran diverifikasi oleh Admin, status produk otomatis menjadi `sold`. Jika pesanan dibatalkan/ditolak, produk kembali menjadi `available`.

### C. Manajemen Pencairan Dana Supplier (Payout)
- Admin dapat melihat saldo supplier yang telah terjual dan belum dicairkan (`unpaid`).
- Validasi ketat: Nominal pencairan **dilarang melebihi saldo tersedia**.
- Pencatatan nomor referensi transfer bank dan unggah bukti transfer pencairan dana ke supplier.

### D. Fitur Pelanggan (Customer Marketplace)
- **Katalog & Filter:** Pencarian kata kunci, filter kategori, filter kondisi (Baru dengan Tag, Seperti Baru, Sangat Bagus, Bagus), rentang harga, dan sorting (harga terendah/tertinggi, produk terbaru).
- **Detail Produk:** Galeri multi-foto, badge kondisi, rincian ukuran & bahan, info garansi keaslian, rekomendasi produk serupa.
- **Keranjang & Checkout:** Validasi ketersediaan langsung, penghitungan ongkos kirim berbasis kota/provinsi tujuan, formulir pengiriman lengkap.
- **Tracking & Upload Bukti:** Riwayat transaksi, unggah bukti pembayaran transfer, pelacakan nomor resi pengiriman, konfirmasi penerimaan barang, dan pembatalan pesanan yang belum dibayar.

### E. Dashboard Admin & Laporan Finansial
- **Statistik & Metrik:** Total omzet penjualan, pendapatan komisi admin, kewajiban hak supplier, total supplier, total produk aktif/terjual, dan grafik interaktif (Chart.js) tren penjualan mingguan/bulanan.
- **Ekspor Laporan:** Ekspor laporan penjualan, hak supplier, dan komisi admin ke format **PDF (DomPDF)** dan **CSV**.

### F. Progressive Web App (PWA)
- Manifest PWA valid (`public/manifest.webmanifest`) dengan theme color Charcoal & Muted Sage.
- Service Worker (`public/sw.js`) dengan strategi caching aset statis dan fallback halaman offline (`/offline`).
- Kompatibel dengan tombol "Install Aplikasi" pada browser desktop dan smartphone.

---

## 3. Technology Stack

- **Backend:** Laravel 13 (PHP 8.2+)
- **Database:** MySQL
- **Frontend:** Laravel Blade + Tailwind CSS + Alpine.js
- **Icons:** Lucide Icons
- **Charts:** Chart.js
- **Authentication:** Laravel Auth + Laravel Socialite (Google OAuth) + Demo Login
- **Export Laporan:** Barryvdh Laravel DomPDF & Streamed CSV Export
- **PWA:** Web App Manifest + Service Worker + Offline Fallback

---

## 4. Struktur Database & Model

1. `users`: Akun admin dan customer (dengan `google_id`, `role`, `avatar`, `phone`).
2. `suppliers`: Data mitra supplier konsinyasi (identitas, nomor rekening, bank, status).
3. `categories`: Kategori pakaian thrift (Outerwear, Vintage Tees, Knitwear, Denim, dll).
4. `products`: Katalog barang thrift, supplier owner, harga kesepakatan, harga jual, kondisi, dan status (`available`, `reserved`, `sold`, `archived`).
5. `product_images`: Galeri foto produk.
6. `carts` & `cart_items`: Keranjang belanja customer.
7. `orders` & `order_items`: Transaksi pesanan dengan snapshot harga & komisi.
8. `payments`: Pembayaran (metode manual/gateway, status, bukti transfer, verifikator admin).
9. `shipments`: Pengiriman kurir, nomor resi pengiriman, dan status ekspedisi.
10. `profit_shares`: Rekap pembagian hasil konsinyasi per produk yang terjual.
11. `supplier_payouts` & `payout_items`: Pencairan dana ke rekening supplier.
12. `shipping_rates`: Tarif ongkos kirim terkonfigurasi.
13. `settings`: Pengaturan persentase default bagi hasil dan rekening bank admin.
14. `order_status_histories`: Audit log perubahan status pesanan.

---

## 5. Panduan Instalasi & Menjalankan Aplikasi

### A. Prasyarat
- PHP 8.2 atau lebih baru (dengan ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `openssl`).
- Composer.
- MySQL Server (misal via Laragon, XAMPP, atau MySQL native).

### B. Langkah Instalasi

1. **Clone / Buka Direktori Proyek:**
   ```bash
   cd "c:/New Volume c/INFORMATIKA/SEMESTER 7/ICT ISLAM/PROJEK AKHIR/suntrifting"
   ```

2. **Install Dependensi Composer:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (.env):**
   Salin file `.env.example` menjadi `.env` jika belum ada:
   ```bash
   cp .env.example .env
   ```
   Pastikan konfigurasi database di file `.env` sudah sesuai dengan MySQL Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=thrift_marketplace
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi & Seeder Database:**
   Perintah ini akan membuat database tables, data kategori, 4 supplier mitra, pengaturan komisi, ongkos kirim, akun demo, serta 10 produk thrift kurasi lengkap dengan gambar ilustratif:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Buat Symlink Storage Publik:**
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Development Server:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di: `http://localhost:8000`

---

## 6. Kredensial Akun untuk Pengujian (Development)

Aplikasi telah dilengkapi seeder akun untuk kedua role:

### 1. Akun Administrator
- **URL Login Admin:** `http://localhost:8000/admin/login`
- **Email:** `admin@suntrifting.com`
- **Password:** `password123`
- *Hak Akses:* Akses penuh ke seluruh menu dashboard, produk, kategori, supplier, verifikasi pembayaran, pembagian hasil, payout supplier, laporan keuangan, dan settings.

### 2. Akun Pelanggan (Customer)
- **URL Login Customer:** `http://localhost:8000/login`
- **Email:** `customer@suntrifting.com`
- **Password:** `password123`
- *Opsi Instan:* Di halaman login customer, tersedia tombol **"Masuk sebagai Akun Demo Pembeli"** untuk pengujian cepat 1-klik tanpa perlu mengetik kredensial.

---

## 7. Panduan Konfigurasi Google OAuth (Socialite)

Customer dapat mendaftar dan login otomatis menggunakan akun Google. Untuk mengaktifkannya:

1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Buat project baru atau pilih project yang sudah ada.
3. Masuk ke menu **APIs & Services > Credentials**.
4. Klik **Create Credentials > OAuth Client ID**.
5. Pilih Application Type: **Web Application**.
6. Pada bagian **Authorized redirect URIs**, tambahkan:
   ```
   http://localhost:8000/auth/google/callback
   ```
7. Salin **Client ID** dan **Client Secret** yang dihasilkan, lalu masukkan ke file `.env`:
   ```env
   GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

---

## 8. Panduan Integrasi Pembayaran (Payment Gateway & Manual)

### A. Metode Transfer Manual (Aktif / Default MVP)
- Customer melakukan transfer ke rekening bank toko (BCA / Mandiri / BSI sesuai data di Pengaturan Admin).
- Customer mengunggah foto / scan struk transfer di halaman pesanan.
- Status pesanan berpindah ke `waiting_verification` (Pembayaran: `pending_verification`).
- Admin memeriksa bukti transfer di menu **Admin > Pembayaran**.
- Admin dapat **Menyetujui (Verify)** atau **Menolak (Reject)** bukti pembayaran disertai alasan penolakan.
- Verifikasi yang berhasil memicu pembuatan pembagian hasil otomatis dan mengubah status pesanan ke `processing` & barang menjadi `sold`.

### B. Integrasi Midtrans (Opsional / Production-Ready)
Sistem telah dirancang modular untuk payment gateway Midtrans:
```env
MIDTRANS_SERVER_KEY=your-midtrans-server-key
MIDTRANS_CLIENT_KEY=your-midtrans-client-key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

---

## 9. Pengujian Fitur PWA & Offline Mode

1. **Install PWA:**
   - Di browser Google Chrome / Microsoft Edge, klik ikon **Install** di sebelah kanan address bar (atau tombol install banner di web).
   - Di smartphone Android (Chrome), pilih menu titik tiga > **"Tambahkan ke Layar Utama" / "Install Aplikasi"**.
   - Di iPhone (Safari), pilih ikon Share > **"Add to Home Screen"**.
2. **Uji Offline Fallback:**
   - Buka DevTools di Chrome (F12) > tab **Network** > ubah throttling menjadi **Offline**.
   - Buka halaman `http://localhost:8000/offline` atau refresh aplikasi. Halaman offline yang dirancang ramah pengguna akan ditampilkan tanpa pesan crash browser.

---

## 10. Menjalankan Automated Test Suite

Aplikasi dilengkapi dengan 9 automated unit & feature tests yang mencakup seluruh alur bisnis kritis:
- Akses halaman publik & katalog.
- Proteksi route admin terhadap tamu tak berizin.
- Login admin & tampilan metrik konsinyasi.
- Alur keranjang & checkout customer dengan penguncian stok.
- Verifikasi pembayaran & akurasi kalkulasi bagi hasil 90% / 10% (Desimal akurat).
- Validasi integritas manifest PWA.
- Pencegahan payout yang melebihi saldo supplier.
- Pencegahan checkout barang yang sudah terjual (*unique thrift protection*).
- Unduhan laporan finansial dalam format CSV dan PDF.

Jalankan test suite menggunakan perintah:
```bash
php artisan test --filter=ThriftMarketplaceTest
```

Output yang diharapkan:
```
PASS  Tests\Feature\ThriftMarketplaceTest
✓ public pages are accessible
✓ guest cannot access admin dashboard
✓ admin can login and access dashboard
✓ customer can add to cart and checkout
✓ payment verification triggers accurate profit sharing
✓ pwa manifest is valid json
✓ supplier payout rejects amount greater than balance
✓ cannot add sold product to cart
✓ admin can export financial reports csv and pdf

Tests:    9 passed (35 assertions)
Duration: ~1.8s
```

---

## 11. Lisensi & Hak Cipta

Dikembangkan untuk memenuhi spesifikasi **Projek Akhir ICT Islam - Thrift Marketplace PWA Konsinyasi (Suntrifting)**.
Dibuat dengan standar kode bersih, arsitektur Service Layer, dan kepatuhan penuh terhadap prinsip transaksi konsinyasi.
#   S U N T R I F T I N G  
 