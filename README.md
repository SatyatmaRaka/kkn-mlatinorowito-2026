# KKN Mlatinorowito 2026

Website profil kelompok KKN Universitas Muria Kudus di Kelurahan Mlatinorowito, Kudus.

**Stack:** Laravel 12, Blade, Bootstrap 5, Alpine.js, Vite

## Fitur

- Halaman publik: beranda, tentang, anggota, program kerja, kegiatan & galeri, kontak
- Panel admin: CRUD anggota, program kerja, kegiatan, galeri, pengaturan website & akun
- Login admin berbasis username (single admin)
- **Logbook KKN** per anggota (draft → submit → review koordinator)
- **Absensi QR** di posko (scan → login → konfirmasi kehadiran)
- **Role sistem**: admin, koordinator, anggota (terhubung ke data anggota)
- Halaman arsip kegiatan publik (`/kegiatan`)

## Instalasi Lokal

```bash
# Clone & masuk folder proyek
composer install
cp .env.example .env
php artisan key:generate

# Isi ADMIN_DEFAULT_PASSWORD di .env (wajib untuk seeding)
# Opsional MEMBER_DEFAULT_PASSWORD untuk akun demo koordinator/anggota
# Contoh:
# ADMIN_DEFAULT_PASSWORD=PasswordKuatMinimal12
# MEMBER_DEFAULT_PASSWORD=PasswordAnggota12

npm install
npm run dev          # terminal 1
php artisan serve    # terminal 2
```

### Database

```bash
# SQLite (default di .env.example)
php artisan migrate --seed

# Atau MySQL — sesuaikan DB_* di .env lalu:
php artisan migrate --seed
```

### Storage & Asset

```bash
php artisan storage:link   # wajib agar foto upload tampil
npm run build            # untuk production / preview build
```

## Akun Admin Default

Setelah `db:seed`, login dengan:
- **Username:** `kkn_mlati26`
- **Password:** nilai `ADMIN_DEFAULT_PASSWORD` di `.env`

Segera ganti password lewat menu **Pengaturan → Keamanan Akun** setelah login pertama.

### Akun Demo Anggota (jika `MEMBER_DEFAULT_PASSWORD` diisi)

| Username | Role | Keterangan |
|----------|------|------------|
| `koor_mlati26` | Koordinator | Terhubung ke Koordinator Desa |
| `anggota_demo` | Anggota | Contoh akun anggota |

Buat akun anggota lain lewat **Admin → Anggota → Buat Akun**.

## Absensi QR (Cara Pakai)

1. Admin/koordinator: **Cetak QR Absensi** atau buka **Mode Tablet** di posko
2. QR **berubah otomatis setiap hari** — foto QR kemarin tidak bisa dipakai
3. Anggota scan QR → login akun pribadi → **Konfirmasi Kehadiran**
4. Absensi hanya valid dalam jam yang diatur di **Pengaturan → Jam Absensi**
5. Koordinator pantau **Rekap Absensi** dan export CSV

```bash
# Generate token hari ini manual (opsional, otomatis saat buka halaman QR)
php artisan absensi:rotate-token
```

## Logbook KKN

- Anggota: **Logbook → Tulis Logbook** (draft atau kirim review)
- Koordinator: review & setujui/tolak logbook yang masuk

## Deploy ke Production

### cPanel (public_html + kkn_app)

Struktur di server:

```
/home/user/
├── kkn_app/          ← seluruh proyek Laravel (di luar web root)
└── public_html/
    ├── index.php     ← salin dari deploy/index.php
    ├── .htaccess     ← salin dari public/.htaccess
    ├── build/        ← salin dari public/build/
    └── images/       ← salin dari public/images/
```

`public/index.php` di repo tetap untuk development lokal. Untuk production, gunakan `deploy/index.php` yang mengarah ke folder `kkn_app/` di server.

### Checklist

1. `APP_ENV=production` dan `APP_DEBUG=false`
2. `SESSION_ENCRYPT=true`
3. `npm run build` — pastikan folder `public/build/` ada
4. **Jangan** deploy file `public/hot` (penanda Vite dev server)
5. `php artisan storage:link`
6. `php artisan migrate --force`
7. Rotasi `APP_KEY` dan password admin jika pernah bocor

## Testing

```bash
php artisan test
```

## Struktur Penting

| Path | Keterangan |
|------|------------|
| `app/Http/Controllers/Admin/` | CRUD panel admin |
| `app/Services/PengaturanService.php` | Cache pengaturan situs |
| `app/Support/HtmlSanitizer.php` | Sanitasi konten kegiatan (HTMLPurifier) |
| `resources/views/welcome.blade.php` | Halaman publik utama |
| `database/seeders/` | Data awal admin, anggota, proker, kegiatan, pengaturan |

## Lisensi

Proyek internal KKN UMK Mlatinorowito 2026.
