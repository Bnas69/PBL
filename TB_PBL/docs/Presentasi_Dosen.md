#Supply Chain Optimizer (Dashboard Stok & Draft Re-order)


---

## Kelompok

- Nama Kelompok: [kelompok_4]
- Anggota:
  - [Septian Dwi Saputra] (NIM: 411232056) #0f9d58
  - [Tiara Adisa Marcianda] (NIM: 411232040) #0f149d
  - [Izatul Janah] (NIM: 411232019) #d17b0b

```
  _____  _   _  ____  _   _  _   _
 / ____|| \ | ||  _ \| \ | || \ | |
| (___  |  \| || |_) |  \| ||  \| |
 \___ \ | . ` ||  _ <| . ` || . ` |
 ____) || |\  || |_) | |\  || |\  |
|_____/ |_| \_||____/|_| \_||_| \_|

   [SupplySmart]  📦⚙️
```

---

## Ringkasan singkat pembukaan presentasi

Proyek ini membuat dashboard stok gudang yang otomatis membuat "Draft Re-order" ke supplier ketika algoritma mendeteksi stok akan habis dalam 3 hari ke depan berdasarkan kecepatan penjualan (rata-rata 30 hari terakhir). Tujuannya agar manajer gudang dapat melihat kebutuhan pemesanan ulang lebih cepat dan terencana.

---

## Alur kerja sederhana

1. Data penjualan disimpan setiap hari ke tabel `sales`.
2. Sistem menghitung rata-rata penjualan per hari dari 30 hari terakhir.
3. Dari data stok saat ini, sistem menghitung berapa hari stok akan habis.
4. Jika diperkirakan habis ≤ 3 hari dan stok sudah di bawah `min_stock_level`, sistem membuat atau memperbarui `ReorderDraft` (draft pemesanan ulang).
5. Draft tampil di dashboard; pengguna dapat meninjau dan menyetujui pemesanan.

---

## Penjelasan komponen

- **Model data (basis data)**
  - [app/Models/Product.php](app/Models/Product.php) : Menyimpan data produk (nama, deskripsi, supplier, harga).
  - [app/Models/Inventory.php](app/Models/Inventory.php) : Menyimpan jumlah stok saat ini (`quantity`) dan batas minimum (`min_stock_level`) untuk setiap produk.
  - [app/Models/Sale.php](app/Models/Sale.php) : Menyimpan catatan penjualan harian (berapa unit terjual pada tanggal tertentu).
  - [app/Models/ReorderDraft.php](app/Models/ReorderDraft.php) : Menyimpan draft pemesanan ulang (produk, jumlah yang disarankan, alasan, status).

- **Service inti**
  - [app/Services/StockAnalysisService.php](app/Services/StockAnalysisService.php) : Ini otak dari sistem. Fungsinya:
    - Mengambil data penjualan 30 hari terakhir per produk.
    - Menghitung rata-rata penjualan per hari.
    - Menghitung berapa hari lagi stok akan habis (jika jualan > 0).
    - Jika kondisi terpenuhi (≤ 3 hari dan stok ≤ min), maka membuat atau memperbarui `ReorderDraft`.
    - Juga menyiapkan data yang dipakai dashboard (stok sekarang, rata-rata jual/hari, perkiraan hari habis).

- **Controller & Route**
  - [app/Http/Controllers/DashboardController.php](app/Http/Controllers/DashboardController.php) : Menyediakan data untuk tampilan dashboard dan endpoint untuk memicu analisis manual.
  - [routes/web.php](routes/web.php) : Mendefinisikan route untuk halaman dashboard dan route POST `/dashboard/analyze` untuk menjalankan analisis dari UI.
  - [routes/console.php](routes/console.php) : Menjadwalkan perintah `app:analyze-stock` berjalan setiap hari (cron-like) sehingga analisis otomatis berjalan tiap hari.

- **Seeder & sample data**
  - `database/seeders/ProductSeeder.php` : Menambah 15 produk contoh.
  - `database/seeders/InventorySeeder.php` : Menambah stok awal dan `min_stock_level` untuk setiap produk.
  - `database/seeders/SaleSeeder.php` : Membuat data penjualan 30 hari terakhir untuk setiap produk (untuk demo/training algoritma).
  - `database/seeders/ReorderDraftSeeder.php` : Contoh draft reorder untuk menunjukkan status yang berbeda (pending/approved/rejected).

- **Frontend (UI)**
  - [resources/js/Pages/Dashboard/Index.vue](resources/js/Pages/Dashboard/Index.vue) : Menampilkan grafik stok, tabel detail 15 produk, dan daftar draft reorder. Ada tombol **"Jalankan Analisis"** untuk memicu analisis manual dari browser.

---

## Penjelasan algoritma

1. Ambil jumlah barang yang terjual selama 30 hari terakhir (misal total = 60 unit).
2. Rata-rata per hari = total / 30 → misal 60/30 = 2 unit/hari.
3. Prediksi hari habis = stok saat ini / rata-rata per hari → misal stok 5 / 2 = 2.5 hari.
4. Jika prediksi ≤ 3 hari dan stok ≤ level minimum, sistem sarankan pemesanan ulang (misal: tambahkan sampai stok = 2 × min_stock, atau setidaknya 10 unit).

Rumus ringkas:

$$\text{avgDailySales} = \frac{\sum_{i=1}^{30} sales_i}{30}$$
$$\text{daysToDepletion} = \frac{currentStock}{avgDailySales}$$

Jika `avgDailySales == 0` → berarti tidak ada penjualan, maka tidak direorder (atau diperlakukan sebagai 'aman').

Kode inti di service menggunakan `updateOrCreate()` untuk menghindari duplikasi draft saat analisis dijalankan berkali-kali.

---

## Cara menjalankan (langkah teknis cepat untuk demo lokal)

1. Install dependency PHP & JS:

```bash
composer install
npm install
```

2. Siapkan environment:

```bash
cp .env.example .env
php artisan key:generate
```

3. Buat database dan isi sample data:

```bash
php artisan migrate:fresh --seed
```

4. Jalankan frontend (development) dan server laravel:

```bash
npm run dev    # atau npm run build untuk produksi
php artisan serve
```

5. Buka browser: `http://127.0.0.1:8000/dashboard` (login bila perlu). Klik tombol **Jalankan Analisis** untuk memicu pembuatan draft.

6. Untuk melihat draft di DB (contoh SQLite):

```bash
sqlite3 database/database.sqlite "select id, product_id, suggested_quantity, status, reason from reorder_drafts;"
```

---

## Skrip presentasi singkat (2–4 menit)

1. Buka slide: masalah singkat (kekurangan stok, out-of-stock, manual reorder sulit terkoordinasi).
2. Perkenalkan solusi: "Supply Chain Optimizer" — dashboard otomatis yang mendeteksi kebutuhan reorder.
3. Demo live: buka dashboard, tampilkan tabel 15 produk, jelaskan kolom (stok, min stock, rata-rata jual/hari, perkiraan hari habis).
4. Tekan tombol **Jalankan Analisis**; reload; tunjukkan `Pending Reorder Drafts`.
5. Jelaskan algoritma dalam 3 langkah (ambil 30 hari → hitung rata-rata → prediksi habis → buat draft).
6. Sebutkan batasan & pengembangan: (contoh: belum ada integrasi langsung ke supplier, asumsi penjualan historis stabil, bisa ditingkatkan dengan lead time dan safety stock).

---

## Pertanyaan & jawaban singkat

- Q: Dari mana data penjualan? A: Dari tabel `sales` yang diisi oleh sistem (atau manual) setiap kali transaksi terjadi.
- Q: Bagaimana mencegah draft ganda? A: Menggunakan `updateOrCreate()` sehingga hanya ada satu draft `pending` per produk.
- Q: Bisakah otomatis kirim ke supplier? A: Saat ini belum; fungsi ini membuat draft agar operator manusia cek dulu. Integrasi API supplier bisa ditambahkan.

---

## Catatan Dari Kita

- Proyek ini menitikberatkan konsep: analisis tren sederhana + automasi proses bisnis (membuat draft pemesanan ulang).
- Kode mudah dibaca: service `StockAnalysisService` berfungsi sebagai lapisan logika, sedangkan controller & route hanya menghubungkan UI dan service.

---


File: `docs/Presentasi_Dosen.md`
