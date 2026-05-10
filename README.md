# SIMUH — Sistem Informasi Masjid Unggulan Muhammadiyah
## Panduan Instalasi di Shared Hosting / cPanel

---

## Persyaratan Server
- PHP >= 8.1 (dengan ekstensi: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json)
- MySQL / MariaDB >= 5.7
- Composer (atau upload vendor secara manual)

---

## Langkah Instalasi

### 1. Upload File ke Hosting
Upload seluruh isi folder project ke direktori hosting Anda.
Letakkan folder `public/` sebagai document root (public_html) atau atur subdomain.

Struktur yang disarankan di cPanel:
```
/home/namauser/
├── simuh/          ← seluruh file Laravel (kecuali public/)
│   ├── app/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   └── ...
└── public_html/    ← isi folder public/ Laravel
    ├── index.php   ← ubah path-nya (lihat langkah 3)
    ├── .htaccess
    └── ...
```

### 2. Buat Database MySQL di cPanel
1. Buka **MySQL Databases** di cPanel
2. Buat database baru, misalnya: `namauser_simuh`
3. Buat user MySQL baru dan set password kuat
4. Assign user ke database dengan privilege **All Privileges**

### 3. Konfigurasi .env
Copy file `.env.example` menjadi `.env` dan isi:

```env
APP_NAME="SIMUH"
APP_ENV=production
APP_KEY=                        # akan diisi setelah php artisan key:generate
APP_DEBUG=false
APP_URL=https://domainanda.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=namauser_simuh
DB_USERNAME=namauser_dbuser
DB_PASSWORD=passwordkuat

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### 4. Edit index.php (public_html)
Ubah path di `public_html/index.php`:

```php
// Ganti baris ini:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Menjadi (sesuaikan path):
require __DIR__.'/../simuh/vendor/autoload.php';
$app = require_once __DIR__.'/../simuh/bootstrap/app.php';
```

### 5. Install Dependencies
Jika ada akses SSH:
```bash
cd ~/simuh
composer install --optimize-autoloader --no-dev
```

Jika tidak ada SSH: upload folder `vendor/` yang sudah di-generate secara lokal.

### 6. Generate App Key
```bash
php artisan key:generate
```

Atau generate secara lokal dan isi manual di `.env`:
```bash
# Jalankan di lokal:
php artisan key:generate --show
# Copy hasilnya ke APP_KEY= di .env hosting
```

### 7. Jalankan Migrasi & Seeder
```bash
php artisan migrate --force
php artisan db:seed --force
```

Jika tidak ada SSH, gunakan **phpMyAdmin**:
1. Import file SQL dari `database/simuh_schema.sql` (generate dulu secara lokal)
2. Jalankan seeder secara manual atau insert data super admin langsung

### 8. Set Permissions
```bash
chmod -R 755 ~/simuh/storage
chmod -R 755 ~/simuh/bootstrap/cache
```

### 9. Storage Link
```bash
php artisan storage:link
```
Atau buat symlink manual di cPanel File Manager:
`public_html/storage` → `../simuh/storage/app/public`

---

## Akun Default Setelah Instalasi

| Role        | Username    | Password        |
|-------------|-------------|-----------------|
| Super Admin | superadmin  | SimuhAdmin@2024 |

**PENTING: Segera ganti password super admin setelah login pertama!**

---

## Struktur Role & Hak Akses

| Role         | Tambah User | CRUD Masjid        | Lihat Data            |
|--------------|-------------|--------------------|-----------------------|
| Super Admin  | Semua level | Semua masjid       | Semua                 |
| Admin PP     | —           | Read only          | Semua (filter PWM+)   |
| Admin PWM    | PDM ke bawah| Cakupan PWM-nya    | Cakupan PWM           |
| Admin PDM    | PCM ke bawah| Cakupan PDM-nya    | Cakupan PDM           |
| Admin PCM    | PRM ke bawah| Cakupan PCM-nya    | Cakupan PCM           |
| Admin PRM    | Admin Masjid| Cakupan PRM-nya    | Cakupan PRM           |
| Admin Masjid | —           | Masjidnya sendiri  | Masjidnya sendiri     |

---

## Alur Input Data Bulanan

1. Admin Masjid login → menu **Input Indikator**
2. Pilih tahun & bulan
3. Isi 7 indikator (Jama'ah 1-2, Jariyah 1-2, Jam'iyah 1-3)
4. Klik **Simpan Draft** (bisa diubah) atau **Kirim Data** (final)
5. Data otomatis masuk ke dashboard di semua level di atasnya

---

## File yang Dihasilkan Generator Ini

```
simuh/
├── database/
│   ├── migrations/
│   │   ├── ..._create_wilayah_tables.php    # PWM, PDM, PCM, PRM
│   │   ├── ..._create_masjid_table.php      # Masjid
│   │   ├── ..._create_users_table.php       # Users multi-role
│   │   └── ..._create_indikator_tables.php  # Indikator & data bulanan
│   └── seeders/DatabaseSeeder.php
├── app/
│   ├── Models/
│   │   ├── User.php                         # Model user + scope akses
│   │   └── Models.php                       # Pwm,Pdm,Pcm,Prm,Masjid,Indikator,DataIndikator
│   └── Http/
│       ├── Middleware/RoleMiddleware.php
│       └── Controllers/
│           ├── AuthController.php
│           ├── DashboardController.php
│           ├── MasjidController.php
│           ├── DataIndikatorController.php
│           └── UserController.php
├── routes/web.php
└── resources/views/
    ├── layouts/app.blade.php
    ├── auth/login.blade.php
    ├── dashboard/index.blade.php
    └── indikator/input.blade.php
```

---

## Langkah Selanjutnya (Development)

- [ ] View: `masjid/index.blade.php` — daftar masjid dengan filter
- [ ] View: `masjid/create.blade.php` & `edit.blade.php` — form CRUD
- [ ] View: `masjid/show.blade.php` — detail masjid
- [ ] View: `indikator/rekap.blade.php` — tabel rekap pemenuhan
- [ ] View: `user/index.blade.php`, `create.blade.php`, `edit.blade.php`
- [ ] Daftarkan `RoleMiddleware` di `bootstrap/app.php` (Laravel 11) atau `Kernel.php` (Laravel 10)
- [ ] Tambahkan `'role' => RoleMiddleware::class` di alias middleware
- [ ] Export laporan ke Excel (gunakan package `maatwebsite/excel`)
- [ ] Notifikasi email pengingat input bulanan (gunakan Laravel Scheduler)
