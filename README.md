# SUN-THRIFTING — Muslim Fashion & Lifestyle Thrift Marketplace
> **"Secondhand Treasures, Meaningful Style."**
> **Platform E-Commerce Busana Muslim & Lifestyle Thrift Berbasis Konsinyasi (Titip Jual) dengan Progressive Web App (PWA)**

---

## 1. Tentang SUN-THRIFTING

**SUN-THRIFTING** adalah platform digital marketplace yang secara khusus mewadahi aktivitas jual beli pakaian muslim, hijab, perlengkapan ibadah, dan aneka produk gaya hidup muslim dalam kondisi thrift/preloved berkualitas maupun baru (brand new).

Platform ini mengusung empat pilar nilai utama:
1. **Sustainable Fashion**: Mengurangi limbah tekstil melalui perpanjangan siklus hidup busana muslim.
2. **Affordable Muslim Lifestyle**: Memberikan akses pakaian muslim dan perlengkapan ibadah premium dengan harga terjangkau.
3. **Trusted Marketplace**: Transaksi aman dengan verifikasi admin, transparansi kondisi/minus barang, serta sistem proteksi stok unik (1-of-1).
4. **Inclusive Marketplace**: Memfasilitasi seller/supplier konsinyasi dan pembeli dari seluruh nusantara.

---

## 2. Identitas Visual & Desain

- **Konsep:** Modern Islamic Lifestyle, Clean, Minimalis, Elegan, dan Terpercaya.
- **Palet Warna:**
  - `Deep Forest Green (#174C3C)` — Warna primer: merepresentasikan keasrian, keberlanjutan, dan nuansa Islami modern.
  - `Emerald Green (#26745A)` — Warna sekunder & aksen interaktif.
  - `Soft Cream (#F8F6F0)` — Warna latar belakang lembut.
  - `Warm Beige (#EAE3D5)` — Aksen pembatas & badge elegan.
  - `Charcoal (#252A27)` — Tipografi teks utama berdaya baca tinggi.
- **Tipografi:** Plus Jakarta Sans (Headings) & Inter (Body Text).
- **PWA Experience:** Standalone app mode, icon SVG monokromatik Islami, service worker cache, dan offline fallback view.

---

## 3. Struktur 7 Kategori Utama & Subkategori (Dinamis)

SUN-THRIFTING mengadopsi hierarki **Parent Category** dan **Subcategory** yang dikelola langsung secara dinamis melalui dashboard admin:

1. **Pakaian Muslim Wanita**
   - Subkategori: Gamis, Abaya, Tunik, Blouse Muslimah, Dress Syar'i, Rok Panjang, Celana Kulot, Outer Muslimah, Setelan Muslimah.
2. **Pakaian Muslim Pria**
   - Subkategori: Baju Koko, Kurta, Jubah, Gamis Pria, Kemeja Muslim, Celana Sirwal, Sarung Pria, Setelan Muslim Pria.
3. **Hijab & Kerudung**
   - Subkategori: Hijab Segi Empat, Pashmina, Bergo, Khimar, Hijab Instan, Ciput / Inner Hijab.
4. **Perlengkapan Ibadah**
   - Subkategori: Mukena, Sajadah, Sarung, Peci / Kopiah, Tasbih, Tas Mukena, Perlengkapan Salat Lainnya.
5. **Aksesori Muslim**
   - Subkategori: Bros Hijab, Pin Hijab, Manset Tangan, Kaus Kaki Muslimah, Aksesori Busana Muslim, Aksesori Fashion Muslim.
6. **Alas Kaki & Tas**
   - Subkategori: Sepatu, Sandal, Flat Shoes, Tote Bag, Handbag, Sling Bag, Backpack.
7. **Fashion Muslim Anak**
   - Subkategori: Gamis Anak, Baju Koko Anak, Hijab Anak, Mukena Anak, Sarung Anak, Setelan Muslim Anak.

---

## 4. Fitur Unggulan Sistem

### A. Transparansi Kondisi & Kurasi Thrift
- **Standar Kondisi Produk:**
  - `Brand New`: Barang baru lengkap dengan label/tag asli.
  - `New Without Tag`: Barang baru tanpa label pabrikan.
  - `Like New`: Preloved dengan kondisi istimewa hampir tanpa jejak pakai.
  - `Good Condition`: Preloved layak pakai, warna cerah, serat kain terawat.
  - `Fair Condition`: Preloved dengan jejak pemakaian wajar.
- **Fitur Keterbukaan Minus (Defect Disclosure):** 
  - Catatan detail minus/cacat kain atau kancing pada produk.
  - Galeri foto khusus bagian minus (`is_defect`) sehingga pembeli mendapatkan transparansi penuh sebelum checkout.
- **Harga Referensi (Strikethrough Original Price):**
  - Menampilkan perbandingan harga baru asli dan harga thrift hemat di SUN-THRIFTING.

### B. Fitur Wishlist / Favorit
- Customer dapat menyimpan produk impian ke daftar wishlist.
- Tombol toggle cepat di katalog, kartu produk, dan halaman detail produk.
- Indikator counter badge pada navigasi atas serta halaman kelola `/wishlist`.

### C. Konsinyasi & Pembagian Hasil Otomatis
- **Pembagian Hasil Default:** 90% Hak Supplier, 10% Komisi Admin (dapat disesuaikan di Admin Settings).
- **Snapshot Immutability:** Hasil perhitungan dikunci permanen pada tabel `profit_shares` saat verifikasi pembayaran, kebal terhadap perubahan persentase di masa depan.
- **Akurasi Finansial:** Kolom `DECIMAL(12, 2)` dan transaksi atomik `DB::transaction`.
- **Pencairan Dana (Supplier Payout):** Validasi saldo supplier sebelum penarikan, proteksi over-payout, dan rekapan mutasi.

### D. Keamanan Stok Thrift Unik (1-of-1 Inventory)
- Menggunakan **Pessimistic Locking (`lockForUpdate`)** saat proses checkout untuk mencegah *race condition* atas barang thrift 1-stok.

### E. Integrasi & Komunikasi
- **Tombol Tanya Seller via WhatsApp:** Direct link dari detail produk menuju customer care / admin konsinyasi dengan pesan terformat otomatis.
- **Ekspor Laporan:** Ekspor laporan keuangan dalam format CSV/Excel dan DomPDF beridentitas SUN-THRIFTING.

---

## 5. Akun Demo & Kredensial Pengujian

| Role | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@suntrifting.com` | `password123` | Akses penuh dashboard `/admin` |
| **Customer** | `customer@suntrifting.com` | `password123` | Akses belanja, cart, wishlist, checkout |

*Tersedia juga tombol Quick Login 1-Klik pada halaman login untuk kemudahan pengujian.*

---

## 6. Panduan Instalasi & Menjalankan Aplikasi

### Kebutuhan Sistem:
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (opsional untuk build assets tambahan)

### Langkah Setup:
```bash
# 1. Clone repository & masuk direktori
git clone <repository_url>
cd suntrifting

# 2. Install dependensi PHP
composer install

# 3. Salin environment & generate app key
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=suntrifting_db
DB_USERNAME=root
DB_PASSWORD=

# 5. Jalankan migrasi dan seeder data kurasi
php artisan migrate:fresh --seed

# 6. Buat symbolic link storage untuk foto produk
php artisan storage:link

# 7. Jalankan development server
php artisan serve
```

Aplikasi dapat diakses melalui browser di: `http://localhost:8000`

---

## 7. Menjalankan Pengujian Otomatis (Test Suite)

Untuk memvalidasi integritas logika konsinyasi, hierarki kategori, wishlist, dan checkout:

```bash
php artisan test
```

Semua 15 unit & feature test telah terverifikasi **100% PASSING (Green)**.
