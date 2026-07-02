# KKN Mlatinorowito 2026

Website profil kelompok KKN Universitas Muria Kudus di Kelurahan Mlatinorowito, Kudus.

**Stack:** Laravel 12, Blade, Bootstrap 5, Alpine.js, Vite

## Fitur

- Halaman publik: beranda, tentang, anggota, program kerja, kegiatan & galeri, kontak
- **Panel** operasional: CRUD anggota, program kerja, kegiatan, galeri, pengaturan website & akun
- Login berbasis username (admin, koordinator, anggota)
- **Catatan Harian (Logbook) KKN** per anggota (draft → submit → review koordinator)
- **Absensi QR** di posko (scan → login → konfirmasi kehadiran)
- **Keuangan** pemasukan/pengeluaran dengan export CSV
- **Role sistem**: admin, koordinator, anggota (terhubung ke data anggota)
- **Jabatan organisasi**: Sekretaris (CMS), Bendahara (keuangan), dll.
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

**Wajib** ganti password lewat menu **Panel → Pengaturan → Keamanan Akun** segera setelah login pertama (jangan pakai password seed di production).

### Akun Demo Anggota (jika `MEMBER_DEFAULT_PASSWORD` diisi)

| Username | Role | Keterangan |
|----------|------|------------|
| `koor_mlati26` | Koordinator | Terhubung ke Koordinator Desa |
| `anggota_demo` | Anggota | Contoh akun anggota |

Buat akun anggota lain lewat **Panel → Anggota → Buat Akun**.

## Absensi QR (Cara Pakai)

1. Admin/koordinator: **Panel → Cetak QR Absensi** atau buka **Mode Tablet** di posko
2. QR **berubah otomatis setiap hari** — foto QR kemarin tidak bisa dipakai
3. Anggota scan QR → login akun pribadi → **Konfirmasi Kehadiran**
4. Absensi hanya valid dalam jam yang diatur di **Panel → Pengaturan → Jam Absensi**
5. Koordinator pantau **Panel → Rekap Absensi** dan export CSV

```bash
# Generate token hari ini manual (opsional, otomatis saat buka halaman QR)
php artisan absensi:rotate-token
```

## Catatan Harian (Logbook)

- Anggota: **Panel → Catatan Harian → Tulis** (draft atau kirim review)
- Koordinator: review & setujui/tolak catatan yang masuk

## Deploy ke Production (Shared Hosting / cPanel)

### Struktur di server

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

### Checklist wajib

1. `APP_ENV=production` dan `APP_DEBUG=false`
2. **`SESSION_ENCRYPT=true`** — wajib di shared hosting
3. `npm run build` — pastikan folder `public/build/` ada
4. **Jangan** deploy file `public/hot` (penanda Vite dev server)
5. `php artisan storage:link`
6. `php artisan migrate --force`
7. Ganti password admin default segera setelah deploy
8. Rotasi `APP_KEY` jika pernah bocor
9. Atur **cron job** di cPanel — lihat `deploy/cron.txt`

### Cron Job (cPanel) — wajib

Token QR absensi harian membutuhkan scheduler Laravel. Tambahkan cron **setiap menit**:

```
* * * * * /usr/local/bin/php /home/USERNAME/kkn_app/artisan schedule:run >> /dev/null 2>&1
```

Ganti `USERNAME` dengan username cPanel Anda. Detail ada di `deploy/cron.txt`.

## Testing

```bash
php artisan test
```

## Struktur Penting

| Path | Keterangan |
|------|------------|
| `app/Http/Controllers/Panel/` | CRUD panel operasional |
| `app/Http/Controllers/Autentikasi/` | Masuk & keluar akun |
| `app/Layanan/` | Logika bisnis (pengaturan, token absensi) |
| `app/Penunjang/` | Utilitas (sanitasi HTML, ekspor CSV, validasi peta) |
| `app/Enums/PeranPengguna.php` | Enum peran: admin, koordinator, anggota |
| `app/Enums/Jabatan.php` | Enum jabatan organisasi KKN |
| `.github/workflows/tests.yml` | CI otomatis — jalankan test saat push/PR |
| `resources/views/beranda/` | Partial halaman publik (hero, tentang, anggota, dll.) |
| `resources/views/panel/` | Tampilan panel |
| `resources/views/masuk/` | Halaman login |
| `database/seeders/` | Data awal admin & pengaturan |

**URL panel:** `/panel/...` (bukan `/admin/...`)

## Lisensi

Proyek internal KKN UMK Mlatinorowito 2026.
