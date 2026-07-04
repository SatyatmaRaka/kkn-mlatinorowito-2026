<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\Panel\AnggotaController;
use App\Http\Controllers\Panel\CatatanHarianController;
use App\Http\Controllers\Panel\DataLiveController;
use App\Http\Controllers\Panel\DasborController;
use App\Http\Controllers\Panel\KeuanganController;
use App\Http\Controllers\Panel\LaporanController;
use App\Http\Controllers\Panel\PengaturanController;
use App\Http\Controllers\Panel\ProgramKerjaController;
use App\Layanan\LayananTokenAbsensi;
use Illuminate\Support\Facades\Route;

// --- Halaman publik ---
Route::get('/', [BerandaController::class, 'index']);
Route::get('/anggota/{anggota}', [DetailController::class, 'anggota'])->name('detail.anggota');
Route::get('/program-kerja/{programKerja}', [DetailController::class, 'proker'])->name('detail.proker');

// --- Absensi QR (publik dengan token harian) ---
Route::get('/absensi/check-in', [AbsensiController::class, 'checkInForm'])->name('absensi.check-in');
Route::get('/absensi/scan', fn () => redirect()->to(LayananTokenAbsensi::checkInUrl()))->name('absensi.scan');
Route::post('/absensi/check-in', [AbsensiController::class, 'store'])->middleware(['auth', 'throttle:10,1'])->name('absensi.store');

Route::middleware(['auth', 'paksa.ganti.password'])->group(function () {
    Route::get('/dashboard', [DasborController::class, 'index'])->name('dashboard');

    Route::prefix('panel')->name('panel.')->group(function () {
        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('pengaturan/akun', [PengaturanController::class, 'updateAkun'])->name('pengaturan.akun');

        // Panel operasional KKN: logbook & absensi pribadi
        Route::middleware('role:admin,koordinator,anggota')->group(function () {
            Route::get('absensi/riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
            Route::get('catatan-harian/export', [CatatanHarianController::class, 'export'])->name('catatan-harian.export');
            Route::resource('catatan-harian', CatatanHarianController::class)->except(['show'])->parameters(['catatan-harian' => 'logbook']);
            Route::patch('catatan-harian/{logbook}/review', [CatatanHarianController::class, 'review'])->name('catatan-harian.review');
        });

        // Panel absensi admin: rekap, QR, export (admin, koordinator & wakil koordinator)
        Route::middleware('can.pantau.operasional')->group(function () {
            Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
            Route::get('absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
            Route::get('absensi/qr', [AbsensiController::class, 'qrPrint'])->name('absensi.qr');
            Route::post('absensi/qr/regenerate', [AbsensiController::class, 'regenerateToken'])->name('absensi.qr.regenerate');
            Route::get('absensi/display', [AbsensiController::class, 'display'])->name('absensi.display');
        });

        // Panel konten website & data anggota — khusus admin
        Route::middleware('can.manage.cms')->group(function () {
            Route::resource('anggota', AnggotaController::class)->except(['show'])->parameters(['anggota' => 'anggota']);
            Route::resource('program-kerja', ProgramKerjaController::class)->except(['show'])->parameters(['program-kerja' => 'proker']);
            Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
            Route::put('pengaturan/absensi', [PengaturanController::class, 'updateAbsensi'])->name('pengaturan.absensi');
        });

        // Pembuatan akun login anggota — khusus admin
        Route::middleware('role:admin')->group(function () {
            Route::post('anggota/{anggota}/akun', [AnggotaController::class, 'storeAkun'])->name('anggota.akun');
        });

        // Panel keuangan (admin, koordinator, bendahara)
        Route::middleware('can.manage.keuangan')->group(function () {
            Route::get('keuangan/export', [KeuanganController::class, 'export'])->name('keuangan.export');
            Route::resource('keuangan', KeuanganController::class)->except(['show']);
        });

        // Data live (polling) — semua user login
        Route::middleware('throttle:60,1')->group(function () {
            Route::get('api/live/dasbor', [DataLiveController::class, 'dasbor'])->name('api.live.dasbor');
            Route::get('api/live/notifikasi', [DataLiveController::class, 'notifikasi'])->name('api.live.notifikasi');
            Route::post('api/notifikasi/baca', [DataLiveController::class, 'tandaiDibaca'])->name('api.notifikasi.baca');
        });

        // Laporan operasional — admin, koordinator & wakil koordinator
        Route::middleware('can.pantau.operasional')->group(function () {
            Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
            Route::get('laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
            Route::get('laporan/export', [LaporanController::class, 'exportRingkasan'])->name('laporan.export');
            Route::get('api/live/absensi-rekap', [DataLiveController::class, 'absensiRekap'])
                ->middleware('throttle:60,1')
                ->name('api.live.absensi-rekap');
        });

        // Ringkasan laporan keuangan — bendahara (via jabatan, bukan role admin/koordinator)
        Route::middleware('can.manage.keuangan')->group(function () {
            Route::get('laporan-keuangan', [LaporanController::class, 'index'])->name('laporan.keuangan');
        });
    });
});

require __DIR__.'/auth.php';
