<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ArsipKegiatanController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\Panel\AnggotaController;
use App\Http\Controllers\Panel\CatatanHarianController;
use App\Http\Controllers\Panel\DasborController;
use App\Http\Controllers\Panel\GaleriController;
use App\Http\Controllers\Panel\KegiatanController;
use App\Http\Controllers\Panel\KeuanganController;
use App\Http\Controllers\Panel\PengaturanController;
use App\Http\Controllers\Panel\ProgramKerjaController;
use App\Layanan\LayananTokenAbsensi;
use Illuminate\Support\Facades\Route;

// --- Halaman publik ---
Route::get('/', [BerandaController::class, 'index']);
Route::get('/kegiatan', [ArsipKegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/anggota/{anggota}', [DetailController::class, 'anggota'])->name('detail.anggota');
Route::get('/program-kerja/{programKerja}', [DetailController::class, 'proker'])->name('detail.proker');
Route::get('/kegiatan/{kegiatan}', [DetailController::class, 'kegiatan'])->name('detail.kegiatan');

// --- Absensi QR (publik dengan token harian) ---
Route::get('/absensi/check-in', [AbsensiController::class, 'checkInForm'])->name('absensi.check-in');
Route::get('/absensi/scan', fn () => redirect()->to(LayananTokenAbsensi::checkInUrl()))->name('absensi.scan');
Route::post('/absensi/check-in', [AbsensiController::class, 'store'])->middleware('auth')->name('absensi.store');

// --- Dasbor (semua peran login) ---
Route::get('/dashboard', [DasborController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Panel operasional KKN: logbook & absensi pribadi
    Route::middleware('role:admin,koordinator,anggota')->prefix('panel')->name('panel.')->group(function () {
        Route::get('absensi/riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
        Route::get('catatan-harian/export', [CatatanHarianController::class, 'export'])->name('catatan-harian.export');
        Route::resource('catatan-harian', CatatanHarianController::class)->except(['show'])->parameters(['catatan-harian' => 'logbook']);
        Route::patch('catatan-harian/{logbook}/review', [CatatanHarianController::class, 'review'])->name('catatan-harian.review');
    });

    // Panel absensi admin: rekap, QR, export (admin & koordinator)
    Route::middleware('role:admin,koordinator')->prefix('panel')->name('panel.')->group(function () {
        Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
        Route::get('absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
        Route::get('absensi/qr', [AbsensiController::class, 'qrPrint'])->name('absensi.qr');
        Route::get('absensi/display', [AbsensiController::class, 'display'])->name('absensi.display');
    });

    // Panel CMS website (admin & sekretaris)
    Route::middleware('can.manage.cms')->prefix('panel')->name('panel.')->group(function () {
        Route::resource('anggota', AnggotaController::class)->except(['show'])->parameters(['anggota' => 'anggota']);
        Route::post('anggota/{anggota}/akun', [AnggotaController::class, 'storeAkun'])->name('anggota.akun');
        Route::resource('program-kerja', ProgramKerjaController::class)->except(['show'])->parameters(['program-kerja' => 'proker']);
        Route::resource('kegiatan', KegiatanController::class)->except(['show'])->parameters(['kegiatan' => 'kegiatan']);
        Route::get('galeri', [GaleriController::class, 'index'])->name('galeri.index');
        Route::get('galeri/create', [GaleriController::class, 'create'])->name('galeri.create');
        Route::post('galeri', [GaleriController::class, 'store'])->name('galeri.store');
        Route::delete('galeri/{galeri}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
        Route::put('pengaturan/akun', [PengaturanController::class, 'updateAkun'])->name('pengaturan.akun');
        Route::put('pengaturan/absensi', [PengaturanController::class, 'updateAbsensi'])->name('pengaturan.absensi');
    });

    // Panel keuangan (admin, koordinator, bendahara)
    Route::middleware('can.manage.keuangan')->prefix('panel')->name('panel.')->group(function () {
        Route::get('keuangan/export', [KeuanganController::class, 'export'])->name('keuangan.export');
        Route::resource('keuangan', KeuanganController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
