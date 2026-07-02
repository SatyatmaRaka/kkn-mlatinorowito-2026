<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\LogbookController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\ProkerController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\KegiatanArchiveController;
use App\Http\Controllers\WelcomeController;
use App\Services\AbsensiTokenService;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/kegiatan', [KegiatanArchiveController::class, 'index'])->name('kegiatan.index');

Route::get('/anggota/{anggota}', [DetailController::class, 'anggota'])->name('detail.anggota');
Route::get('/program-kerja/{programKerja}', [DetailController::class, 'proker'])->name('detail.proker');
Route::get('/kegiatan/{kegiatan}', [DetailController::class, 'kegiatan'])->name('detail.kegiatan');

Route::get('/absensi/check-in', [AbsensiController::class, 'checkInForm'])->name('absensi.check-in');
Route::get('/absensi/scan', fn () => redirect()->to(AbsensiTokenService::checkInUrl()))->name('absensi.scan');
Route::post('/absensi/check-in', [AbsensiController::class, 'store'])->middleware('auth')->name('absensi.store');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin,koordinator,anggota')->prefix('admin')->name('admin.')->group(function () {
        Route::get('absensi/riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
        Route::get('logbook/export', [LogbookController::class, 'export'])->name('logbook.export');

        Route::resource('logbook', LogbookController::class)->except(['show'])->parameters(['logbook' => 'logbook']);
        Route::patch('logbook/{logbook}/review', [LogbookController::class, 'review'])->name('logbook.review');
    });

    Route::middleware('role:admin,koordinator')->prefix('admin')->name('admin.')->group(function () {
        Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
        Route::get('absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
        Route::get('absensi/qr', [AbsensiController::class, 'qrPrint'])->name('absensi.qr');
        Route::get('absensi/display', [AbsensiController::class, 'display'])->name('absensi.display');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('anggota', AnggotaController::class)->except(['show'])->parameters(['anggota' => 'anggota']);
        Route::post('anggota/{anggota}/akun', [AnggotaController::class, 'storeAkun'])->name('anggota.akun');

        Route::resource('proker', ProkerController::class)->except(['show'])->parameters(['proker' => 'proker']);
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
});

require __DIR__.'/auth.php';
