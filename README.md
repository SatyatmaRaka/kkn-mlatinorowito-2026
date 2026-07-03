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
npm run build              # opsional, jika tidak pakai npm run dev
```

## Akun Admin Default

Saat `php artisan migrate --seed`, akun admin **dibuat otomatis**:

- **Username:** `kkn_mlati26`
- **Password awal:** nilai `ADMIN_DEFAULT_PASSWORD` di `.env` (contoh bawaan: `KknMlati2026!`)

Setelah login, **ganti password** lewat **Panel → Pengaturan → Keamanan Akun**.

### Akun Anggota & Koordinator

Tidak ada pendaftaran mandiri. **Hanya admin** yang membuat akun login anggota:

1. **Panel → Anggota → Tambah Anggota** (data profil)
2. Klik **Buat Akun** di baris anggota → isi username, role (Anggota/Koordinator), password
3. Bagikan username & password ke anggota yang bersangkutan

Sekretaris dapat mengelola data anggota di CMS, tetapi **tidak** dapat membuat akun login.

## Absensi QR (Cara Pakai)

1. Admin/koordinator: **Panel → Cetak QR Absensi** — cetak sekali atau buka **Mode Tablet** di posko
2. QR **tetap sama** sepanjang periode KKN (tidak perlu diganti setiap hari)
3. Anggota scan QR → login akun pribadi → **Konfirmasi Kehadiran**
4. Absensi hanya valid dalam jam yang diatur di **Panel → Pengaturan → Jam Absensi**
5. Koordinator pantau **Panel → Rekap Absensi** dan export CSV

Jika QR bocor, admin/koordinator bisa klik **Buat Ulang QR** di halaman cetak QR, atau jalankan:

```bash
php artisan absensi:rotate-token
```

## Catatan Harian (Logbook)

- Anggota: **Panel → Catatan Harian → Tulis** (draft atau kirim review)
- Koordinator: review & setujui/tolak catatan yang masuk

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
