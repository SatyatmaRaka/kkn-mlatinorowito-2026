# KKN Mlatinorowito 2026

Website profil kelompok KKN Universitas Muria Kudus di Kelurahan Mlatinorowito, Kudus.

**Stack:** Laravel 12, Blade, Bootstrap 5, Alpine.js, Vite

## Fitur

- Halaman publik: beranda, tentang, anggota, program kerja, kegiatan & galeri, kontak
- Panel admin: CRUD anggota, program kerja, kegiatan, galeri, pengaturan website & akun
- Login admin berbasis username (single admin)

## Instalasi Lokal

```bash
# Clone & masuk folder proyek
composer install
cp .env.example .env
php artisan key:generate

# Isi ADMIN_DEFAULT_PASSWORD di .env (wajib untuk seeding)
# Contoh:
# ADMIN_DEFAULT_PASSWORD=PasswordKuatMinimal12

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
