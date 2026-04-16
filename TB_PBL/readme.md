# sistem manajemen inventaris & analisis stok

sebuah aplikasi web modern untuk mengelola inventaris produk, menganalisis penjualan, dan membuat rekomendasi pemesanan ulang secara otomatis berdasarkan tren penjualan historis.

## 📋 pengenalan project

project ini adalah sistem manajemen inventaris berbasis web yang dibangun menggunakan **laravel 13** sebagai backend dan **vue 3 + inertia.js** untuk frontend. aplikasi ini dirancang untuk membantu bisnis dalam:

- **mengelola data produk** - menyimpan informasi produk lengkap (nama, deskripsi, supplier, harga)
- **monitoring inventaris real-time** - melacak jumlah stok setiap produk dan menentukan ambang batas minimum
- **analisis penjualan** - mengumpulkan data penjualan harian untuk analisis tren
- **prediksi kehabisan stok** - menghitung berapa hari lagi stok akan habis berdasarkan rata-rata penjualan harian
- **rekomendasi reorder otomatis** - sistem otomatis membuat draft pemesanan ketika stok akan habis

##  fitur utama

### 1. dashboard utama
   - menampilkan ringkasan stok semua produk
   - menunjukkan prediksi kapan stok akan habis (dalam berapa hari)
   - menampilkan rata-rata penjualan harian per produk
   - menampilkan daftar draft reorder yang menunggu persetujuan

### 2. analisis stok otomatis
   - sistem secara otomatis menganalisis setiap produk
   - menghitung rata-rata penjualan dari data 30 hari terakhir
   - memprediksi kapan stok akan habis
   - membuat draft reorder jika stok diperkirakan akan habis dalam 3 hari ke depan dan sudah di bawah minimum stok level

### 3. manajemen profil user
   - user dapat melihat dan mengubah data profilnya
   - mengganti kata sandi
   - menghapus akun

### 4. sistem autentikasi
   - registrasi user baru
   - login yang aman
   - verifikasi email
   - reset password

##  teknologi yang digunakan

### backend
- **laravel 13** - web framework php modern
- **php 8.3** - bahasa pemrograman
- **sqlite/mysql** - database (bisa di-konfigurasi)
- **inertia.js** - server-side routing untuk spa
- **sanctum** - api authentication

### frontend
- **vue 3** - javascript framework untuk ui interaktif
- **inertia.js** - koneksi antara laravel dan vue secara seamless
- **tailwind css** - css utility framework untuk styling
- **vite** - build tool dan dev server (super cepat)
- **chart.js** - library untuk membuat grafik/chart

### developer tools
- **composer** - package manager untuk php
- **npm/yarn** - package manager untuk javascript
- **phpunit** - testing framework untuk php
- **prettier/pint** - code formatter

##  struktur database

database terdiri dari 7 tabel utama:

### 1. users (default laravel)
```
id (primary key)
name (varchar 255)
email (varchar 255, unique)
email_verified_at (timestamp, nullable)
password (varchar 255, hashed)
remember_token (varchar 100, nullable)
created_at, updated_at (timestamp)
```

### 2. products
```
id (primary key)
name (varchar 255) - nama produk
description (text, nullable) - deskripsi produk
supplier (varchar 255) - nama supplier/penyedia
price (decimal 10,2) - harga satuan
created_at, updated_at (timestamp)
```

### 3. inventories
```
id (primary key)
product_id (foreign key → products.id)
quantity (integer) - jumlah stok saat ini
min_stock_level (integer, default 10) - ambang batas minimum stok
created_at, updated_at (timestamp)
```

### 4. sales
```
id (primary key)
product_id (foreign key → products.id)
quantity_sold (integer) - jumlah unit yang terjual
sale_date (date) - tanggal penjualan
created_at, updated_at (timestamp)
```

### 5. reorder_drafts
```
id (primary key)
product_id (foreign key → products.id)
suggested_quantity (integer) - jumlah yang disarankan untuk dipesan
reason (text) - alasan/penjelasan kenapa perlu reorder
status (enum: pending/approved/rejected, default 'pending')
created_at, updated_at (timestamp)
```

### 6. cache (default laravel)
```
key (primary key)
value (longtext)
expiration (integer)
```

### 7. jobs (default laravel)
```
id (primary key)
queue (varchar 255)
payload (longtext)
exceptions (longtext)
failed_at (timestamp, nullable)
```

##  relasi database

```
user (1) ──────── (many) products (via relationships di vue)
product (1) ─────── (1) inventory
product (1) ─────── (many) sales
product (1) ─────── (many) reorderdrafts
```

## cara menjalankan aplikasi

### prasyarat instalasi
pastikan sudah terinstall:
- **php 8.3+** - [download](https://www.php.net/downloads)
- **composer** - [download](https://getcomposer.org/download/)
- **node.js 18+** - [download](https://nodejs.org/)
- **git** - [download](https://git-scm.com/)
- **database** - sqlite (bawaan) atau mysql 8.0+

### step 1: clone repository

```bash
git clone <repository-url>
cd tb_pbl
```

### step 2: install php dependencies

```bash
composer install
```

ini akan mendownload semua package php yang diperlukan ke folder `vendor/`. prosesnya bisa memakan waktu 2-5 menit tergantung koneksi internet.

### step 3: setup environment

buat file `.env` dari template:

```bash
cp .env.example .env
```

lalu buka file `.env` dan sesuaikan konfigurasi (khususnya database):

```env
app_name="sistem inventaris"
app_env=local
app_debug=true
app_key=<generated>

# konfigurasi database (pilih satu)
db_connection=sqlite
# atau jika pakai mysql:
# db_connection=mysql
# db_host=127.0.0.1
# db_port=3306
# db_database=nama_database
# db_username=root
# db_password=
```

generate application key:

```bash
php artisan key:generate
```

### step 4: setup database

jalankan migration untuk membuat semua tabel:

```bash
php artisan migrate
```

ini akan membuat struktur database lengkap sesuai file migrations.

lengkapi database dengan data dummy untuk testing:

```bash
php artisan db:seed
```

data dummy ini termasuk:
- 5 pengguna contoh dengan password "password"
- 20 produk sampel dengan berbagai informasi
- inventaris untuk setiap produk dengan stok dan minimum level
- 200+ record penjualan dari berbagai tanggal untuk realistic analysis

### step 5: install javascript dependencies

```bash
npm install
```

atau jika menggunakan yarn:

```bash
yarn install
```

ini akan mendownload semua package node.js yang diperlukan.

### step 6: jalankan development server

buka **2 terminal** di folder project:

**terminal 1 - backend server (laravel):**

```bash
php artisan serve
```

server akan berjalan di `http://localhost:8000`. tekan `ctrl+c` untuk menghentikan.

**terminal 2 - frontend dev server (vite):**

```bash
npm run dev
```

dev server akan berjalan di `http://localhost:5173`. tekan `ctrl+c` untuk menghentikan.

atau jalankan keduanya sekaligus dengan satu command:

```bash
npm run dev:full
```

kemudian buka browser ke `http://localhost:8000`

### step 7: login & testing

akses aplikasi:
- **url:** `http://localhost:8000`
- **email:** `user@example.com` (atau email yang ada di seeder)
- **password:** `password`

setelah login, anda akan melihat dashboard dengan data semua produk dan analisis stoknya.

##  alur kerja analisis stok

berikut adalah bagaimana sistem bekerja secara otomatis:

### 1. **user trigger analisis**
   - user membuka dashboard
   - klik tombol "analisis stok" untuk trigger analisis manual
   - atau dapat dikonfigurasi untuk berjalan otomatis dengan scheduler

### 2. **service analisis memproses**
   - `stockanalysisservice::analyzeandcreatereorderdrafts()` dijalankan
   - loop semua produk yang ada di sistem

### 3. **untuk setiap produk, sistem:**
   - mengambil stok saat ini dari tabel inventories
   - mengambil minimum stock level yang sudah dikonfigurasi
   - mengquery data sales 30 hari terakhir dari tabel sales
   - menghitung total penjualan 30 hari terakhir
   - membagi dengan 30 untuk mendapat rata-rata penjualan per hari

### 4. **prediksi depletion**
   - hitung: `daystodepletion = currentstock / avgdailysales`
   - contoh: jika stok = 100 unit, penjualan rata-rata = 5/hari
   - maka: daystodepletion = 100 / 5 = **20 hari**

### 5. **logika reorder**
   - **kondisi reorder terpenuhi jika:**
     - stok akan habis dalam ≤ 3 hari, dan
     - stok saat ini ≤ minimum stock level
   - **jumlah yang disarankan:** `max(minstock × 2 - currentstock, 10)`
     - minimum 10 unit agar selalu ada
   - **reason:** "stok akan habis dalam x hari berdasarkan tren penjualan"

### 6. **buat atau update draft**
   - jika kondisi terpenuhi, sistem membuat draft reorder
   - jika sudah ada draft pending untuk produk yang sama, update saja
   - status awal selalu "pending" (menunggu persetujuan)

### 7. **dashboard update**
   - draft reorder baru muncul di daftar "pending reorder drafts"
   - user dapat approve atau reject draft tersebut

## 📁 struktur folder project

```
tb_pbl/
├── app/
│   ├── console/              # command-line commands
│   ├── http/
│   │   ├── controllers/       # request handlers
│   │   │   ├── auth/         # auth controllers
│   │   │   ├── dashboardcontroller.php
│   │   │   └── profilecontroller.php
│   │   ├── middleware/        # http middleware
│   │   └── requests/         # form request validation
│   ├── models/                # database models
│   │   ├── user.php
│   │   ├── product.php
│   │   ├── inventory.php
│   │   ├── sale.php
│   │   └── reorderdraft.php
│   ├── services/             # business logic
│   │   └── stockanalysisservice.php
│   └── providers/            # service providers
│
├── bootstrap/                # bootstrap files
│
├── config/                   # configuration files
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── filesystems.php
│   └── ...
│
├── database/
│   ├── migrations/           # database table creation
│   ├── seeders/             # seed data scripts
│   └── factories/           # fake data generation
│
├── public/                   # public accessible files
│   └── index.php            # entry point
│
├── resources/
│   ├── css/                 # stylesheets
│   ├── js/                  # javascript
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   ├── components/      # vue components
│   │   ├── pages/          # page components
│   │   └── layouts/        # layout components
│   └── views/              # blade templates
│
├── routes/
│   ├── web.php             # web routes
│   ├── auth.php            # auth routes
│   └── console.php         # command routes
│
├── storage/                # file & log storage
│
├── tests/                  # test files
│
├── composer.json           # php dependencies
├── package.json            # node dependencies
├── vite.config.js          # vite configuration
├── tailwind.config.js      # tailwind configuration
├── phpunit.xml             # phpunit configuration
└── .env.example            # environment template
```

## 🛠️ command-command penting

### development commands

menjalankan development server:
```bash
php artisan serve
```

menjalankan vite dev server:
```bash
npm run dev
```

### database commands

jalankan semua migrations:
```bash
php artisan migrate
```

rollback migration terakhir:
```bash
php artisan migrate:rollback
```

rollback semua migrations (hati-hati!):
```bash
php artisan migrate:reset
```

refresh semua migrations + seed:
```bash
php artisan migrate:refresh --seed
```

jalankan seeders:
```bash
php artisan db:seed
```

jalankan seeder spesifik:
```bash
php artisan db:seed --class=productseeder
```

### build commands

build frontend untuk production:
```bash
npm run build
```

build dengan watch mode (auto-build saat perubahan):
```bash
npm run watch
```

### testing commands

jalankan semua tests:
```bash
php artisan test
```

test file spesifik:
```bash
php artisan test tests/feature/dashboardtest.php
```

test dengan output verbose:
```bash
php artisan test --verbose
```

### lint & format commands

format code dengan pint:
```bash
./vendor/bin/pint
```

format code dengan prettier:
```bash
npm run format
```

### artisan interactive shell

```bash
php artisan tinker
```

gunakan untuk test logika langsung, contoh:

```php
// di dalam tinker shell:
>>> $product = product::first();
>>> $product->inventory;
>>> stockanalysisservice::analyzeandcreatereorderdrafts();
```

## 🎓 penjelasan detail kode

### bagaimana data flow dashboard

1. **user akses `/dashboard`**
   - route di `routes/web.php` memanggil `dashboardcontroller@index`

2. **dashboardcontroller mengambil data:**
   ```php
   $stockdata = $service->getstockdata();
   $reorderdrafts = reorderdraft::with('product')->where('status', 'pending')->get();
   ```

3. **stockanalysisservice::getstockdata() proses:**
   - ambil semua produk dengan inventory-nya
   - hitung penjualan 30 hari terakhir
   - hitung rata-rata penjualan per hari
   - prediksi berapa hari stok habis
   - return array dengan semua data tersebut

4. **data dikirim ke vue component dashboard/index**
   - inertia.js secara otomatis render vue component
   - vue menerima data melalui props
   - tampilkan di halaman

### bagaimana reorder otomatis bekerja

1. **user klik tombol "analisis stok" di dashboard**

2. **frontend kirim post request ke `/dashboard/analyze`**
   ```javascript
   // di vue component
   axios.post('/dashboard/analyze')
   ```

3. **backend menjalankan analyze:**
   ```php
   // dashboardcontroller::runanalysis
   $service->analyzeandcreatereorderdrafts();
   ```

4. **service loop semua produk dan check logika:**
   ```php
   // jika stok akan habis dalam 3 hari dan sudah di bawah min_stock_level
   if ($daystodepletion <= 3 && $currentstock <= $minstock) {
       // buat/update draft
       reorderdraft::updateorcreate([...]);
   }
   ```

5. **response balik ke frontend:**
   ```json
   {"success": true, "message": "analisis stok dijalankan..."}
   ```

6. **dashboard refresh dan tampilkan draft baru**

### penjelasan model relationships

```php
// product.php
public function inventory() {
    return $this->hasone(inventory::class);
}
// artinya: satu produk punya satu inventaris

public function sales() {
    return $this->hasmany(sale::class);
}
// artinya: satu produk punya banyak record penjualan

public function reorderdrafts() {
    return $this->hasmany(reorderdraft::class);
}
// artinya: satu produk bisa punya banyak draft reorder
```

cara menggunakan:
```php
// ambil produk beserta inventarisnya
$product = product::with('inventory')->first();
echo $product->inventory->quantity; // akses stok

// ambil penjualan produk
$product->sales()->where('sale_date', '>=', now()->subdays(30))->sum('quantity_sold');

// ambil draft reorder
$product->reorderdrafts()->where('status', 'pending')->first();
```

### penjelasan migration

migrations adalah file yang mendefinisikan struktur database dalam kode php.

contoh `migrations/2026_04_15_061203_create_products_table.php`:

```php
schema::create('products', function (blueprint $table) {
    $table->id();                               // auto-increment id
    $table->string('name');                     // varchar(255)
    $table->text('description')->nullable();    // text, bisa kosong
    $table->string('supplier');                 // varchar(255)
    $table->decimal('price', 10, 2);           // decimal(10,2) untuk harga
    $table->timestamps();                       // created_at, updated_at timestamp
});
```

jalankan migration:
```bash
php artisan migrate
```

ini akan membuat tabel `products` di database.

## 🐛 troubleshooting

### error: "sqlstate[hy000]: general error: 1 attempt to write a readonly database"

**penyebab:** database file tidak bisa ditulis (permission)

**solusi:**
```bash
# jika menggunakan sqlite
chmod 666 database/database.sqlite
chmod 777 database/
```

### error: "no application encryption key has been generated"

**penyebab:** `app_key` di `.env` belum di-generate

**solusi:**
```bash
php artisan key:generate
```

### error: "sqlstate[hy000]: general error: 1 table xyz does not exist"

**penyebab:** database belum di-migrate

**solusi:**
```bash
php artisan migrate
```

### error: "npm: command not found"

**penyebab:** node.js/npm belum terinstall

**solusi:**
- install node.js dari [nodejs.org](https://nodejs.org/)
- tutup dan buka terminal lagi

### error: "composer: command not found"

**penyebab:** composer belum terinstall atau tidak di path

**solusi:**
- install composer dari [getcomposer.org](https://getcomposer.org/)
- atau gunakan php langsung: `php composer.phar install`

### vite dev server error "eaddrinuse: address already in use"

**penyebab:** port 5173 sudah dipakai

**solusi:**
```bash
# jalankan di port berbeda
npm run dev -- --port 5174
```

### hot reload tidak working

**penyebab:** vite perlu detect perubahan file

**solusi:**
- pastikan `npm run dev` tetap berjalan
- check apakah browser sudah refresh otomatis
- jika tidak, coba hard refresh: `ctrl+shift+r` (windows/linux) atau `cmd+shift+r` (mac)

### password migration file tidak bisa di-run

**penyebab:** migration file yang error atau duplikat

**solusi:**
```bash
# rollback semua
php artisan migrate:reset

# jalankan lagi
php artisan migrate
```

##  catatan dari kita

### menambah produk baru lewat kode

```php
// di artisan tinker
>>> $product = product::create([
>>>     'name' => 'laptop gaming',
>>>     'description' => 'laptop gaming dengan spec tinggi',
>>>     'supplier' => 'pt tech indonesia',
>>>     'price' => 15000000
>>> ]);

>>> inventory::create([
>>>     'product_id' => $product->id,
>>>     'quantity' => 50,
>>>     'min_stock_level' => 10
>>> ]);
```

### menambah record penjualan

```php
>>> sale::create([
>>>     'product_id' => 1,
>>>     'quantity_sold' => 5,
>>>     'sale_date' => now()->subdays(10)
>>> ]);
```

### menjalankan analisis manual

```php
>>> app(stockanalysisservice::class)->analyzeandcreatereorderdrafts();
>>> // cek draft yang dibuat
>>> reorderdraft::where('status', 'pending')->with('product')->get();
```


## support

# ==========================================

# 🔥 terminal command master + penjelasan 🔥

# ==========================================

# ===== HAPUS (DELETE) =====

rm file                # hapus file
rm -r folder           # hapus folder beserta isi
rm -f file             # hapus tanpa konfirmasi
rm -rf folder          # hapus paksa semua (BAHAYA!)
rmdir folder           # hapus folder kosong

# ===== NAVIGASI =====

pwd                    # lihat lokasi folder sekarang
ls                     # lihat isi folder
ls -la                 # lihat semua file (termasuk hidden)
ls -lh                 # lihat ukuran file readable
cd folder              # masuk ke folder
cd ..                  # naik 1 level
cd ~                   # ke home directory
cd -                   # balik ke folder sebelumnya

# ===== FILE & FOLDER =====

mkdir folder           # buat folder
mkdir -p a/b/c         # buat folder bertingkat
touch file.txt         # buat file kosong
cp a.txt b.txt         # copy file
cp -r dir1 dir2        # copy folder
mv file folder/        # pindah file
mv a.txt b.txt         # rename file
stat file.txt          # detail info file

# ===== LIHAT FILE =====

cat file.txt           # tampilkan isi file
less file.txt          # baca file panjang scroll
more file.txt          # baca file per halaman
head -n 10 file        # lihat 10 baris awal
tail -n 10 file        # lihat 10 baris akhir
tail -f log.txt        # monitor realtime log
nl file.txt            # tampilkan nomor baris

# ===== SEARCH =====

find . -name "a.txt"   # cari file berdasarkan nama
find . -type f         # cari semua file
find . -type d         # cari semua folder
grep "text" file       # cari teks dalam file
grep -r "text" .       # cari di semua folder
grep -i "text" file    # ignore huruf besar/kecil
grep -n "text" file    # tampilkan nomor baris

# ===== DISK =====

du -h                  # cek ukuran file/folder
du -sh folder          # total ukuran folder
df -h                  # cek kapasitas disk

# ===== PERMISSION =====

chmod 755 file         # ubah izin file
chmod 777 file         # full akses semua (tidak aman)
chmod +x file          # buat file executable
chown user file        # ubah pemilik file
chown -R user folder   # ubah pemilik folder

# ===== PROCESS =====

ps                     # lihat proses aktif
ps aux                 # detail semua proses
top                    # monitor proses realtime
kill PID               # hentikan proses
kill -9 PID            # paksa stop proses
pkill nama             # stop berdasarkan nama

# ===== NETWORK =====

ping google.com        # cek koneksi internet
curl url               # ambil data dari URL
wget url               # download file
ifconfig               # info jaringan (lama)
ip a                   # info IP modern
netstat -tuln          # cek port aktif

# ===== ARCHIVE =====

zip -r file.zip folder # compress ke zip
unzip file.zip         # extract zip
tar -cvf file.tar dir  # buat tar
tar -xvf file.tar      # extract tar
tar -czvf file.tar.gz dir  # compress gzip
tar -xzvf file.tar.gz      # extract gzip

# ===== SYSTEM =====

clear                  # bersihkan terminal
history                # lihat riwayat command
reset                  # reset terminal
echo "text"            # tampilkan text
whoami                 # lihat user aktif
uname -a               # info sistem
uptime                 # lama komputer hidup
date                   # tanggal sekarang
cal                    # kalender

# ===== ENV =====

env                    # lihat environment
printenv               # tampilkan variable
export A=1             # set variable
echo $A                # tampilkan variable

# ===== ALIAS =====

alias ll="ls -la"      # buat shortcut command
unalias ll             # hapus shortcut

# ===== EDITOR =====

nano file.txt          # edit file sederhana
vim file.txt           # editor advanced

# ===== SSH =====

ssh user@host          # konek server
scp file user@host:/   # kirim file ke server
scp user@host:/file .  # ambil file

# ===== GIT =====

git init               # init repo
git clone url          # clone repo
git add .              # tambah semua file
git add file           # tambah file tertentu
git commit -m "msg"    # commit perubahan
git push               # kirim ke remote
git pull               # ambil update
git status             # cek status
git log                # lihat history
git branch             # lihat branch
git checkout branch    # pindah branch
git checkout -b new    # buat branch baru
git merge branch       # gabung branch
git remote -v          # cek remote repo

# ===== NPM =====

npm init -y            # init project
npm install            # install dependency
npm install pkg        # install package
npm install -g pkg     # global install
npm uninstall pkg      # hapus package
npm run dev            # run dev
npm run build          # build project

# ===== YARN =====

yarn                   # install dependency
yarn add pkg           # tambah package
yarn remove pkg        # hapus package
yarn dev               # run dev

# ===== PNPM =====

pnpm install           # install dependency
pnpm add pkg           # tambah package
pnpm dev               # run dev

# ===== COMPOSER =====

composer install       # install PHP deps
composer update        # update deps
composer require pkg   # tambah package

# ===== LARAVEL =====

php artisan serve              # jalankan server
php artisan migrate           # jalankan migration
php artisan migrate:fresh     # reset DB
php artisan make:model A -mcr # buat model + migration + controller
php artisan make:controller C # buat controller
php artisan make:migration    # buat migration
php artisan route:list        # lihat route
php artisan cache:clear       # clear cache
php artisan config:clear      # clear config
php artisan view:clear        # clear view

# ===== DOCKER =====

docker ps              # lihat container aktif
docker ps -a           # semua container
docker images          # list image
docker build -t app .  # build image
docker run -p 8080:80 app # run container
docker stop id         # stop container
docker rm id           # hapus container

# ==========================================

# ⚠️ NOTE:

# - rm -rf = sangat berbahaya

# - chmod 777 = tidak aman

# - gunakan ls dulu sebelum delete

# ================================

---

### ENV

Tempat menyimpan konfigurasi aplikasi (database, API key, dll) agar tidak ditulis langsung di kode.

---

### NPM

Alat untuk menginstal dan mengelola library JavaScript.

---

### Yarn

Alternatif NPM dengan fungsi sama, terkadang lebih cepat/stabil.

---

### PNPM

Versi lebih efisien dari NPM, lebih hemat storage dan cepat.

---

### Git

Sistem untuk menyimpan dan melacak perubahan kode.

---

### SSH

Cara mengakses server atau komputer lain secara aman lewat terminal.

---

### Composer

Alat untuk mengelola library PHP.

---

### Laravel

Framework PHP untuk membuat aplikasi web dengan lebih cepat dan terstruktur.

---

### Docker

Tool untuk menjalankan aplikasi dalam container agar bisa berjalan konsisten di mana saja.

---

### Intinya

* ENV → konfigurasi
* NPM/Yarn/PNPM → library JS
* Git → version control
* SSH → akses server
* Composer → library PHP
* Laravel → framework web
* Docker → lingkungan aplikasi

---

