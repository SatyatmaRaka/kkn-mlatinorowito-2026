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
use App\Http\Controllers\Panel\BukuTamuController;
use App\Http\Controllers\Panel\KegiatanPelaksanaanController;
use App\Http\Controllers\Panel\ObservasiLapanganController;
use App\Http\Controllers\Panel\UkmController;
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

            Route::get('buku-tamu/export', [BukuTamuController::class, 'export'])->name('buku-tamu.export');
            Route::get('buku-tamu/cetak', [BukuTamuController::class, 'cetak'])->name('buku-tamu.cetak');
            Route::resource('buku-tamu', BukuTamuController::class)->except(['show'])->parameters(['buku-tamu' => 'bukuTamu']);

            Route::get('kegiatan-pelaksanaan/{kegiatanPelaksanaan}/cetak-masyarakat', [KegiatanPelaksanaanController::class, 'cetakMasyarakat'])->name('kegiatan-pelaksanaan.cetak-masyarakat');
            Route::get('kegiatan-pelaksanaan/{kegiatanPelaksanaan}/cetak-tim', [KegiatanPelaksanaanController::class, 'cetakTim'])->name('kegiatan-pelaksanaan.cetak-tim');
            Route::get('kegiatan-pelaksanaan/{kegiatanPelaksanaan}/cetak-operasional', [KegiatanPelaksanaanController::class, 'cetakOperasional'])->name('kegiatan-pelaksanaan.cetak-operasional');
            Route::post('kegiatan-pelaksanaan/{kegiatanPelaksanaan}/peserta', [KegiatanPelaksanaanController::class, 'tambahPeserta'])->name('kegiatan-pelaksanaan.peserta.store');
            Route::delete('kegiatan-pelaksanaan/{kegiatanPelaksanaan}/peserta/{peserta}', [KegiatanPelaksanaanController::class, 'hapusPeserta'])->name('kegiatan-pelaksanaan.peserta.destroy');
            Route::post('kegiatan-pelaksanaan/{kegiatanPelaksanaan}/tugas', [KegiatanPelaksanaanController::class, 'tambahTugas'])->name('kegiatan-pelaksanaan.tugas.store');
            Route::delete('kegiatan-pelaksanaan/{kegiatanPelaksanaan}/tugas/{tugas}', [KegiatanPelaksanaanController::class, 'hapusTugas'])->name('kegiatan-pelaksanaan.tugas.destroy');
            Route::resource('kegiatan-pelaksanaan', KegiatanPelaksanaanController::class)->parameters(['kegiatan-pelaksanaan' => 'kegiatanPelaksanaan']);

            Route::get('observasi-lapangan', [ObservasiLapanganController::class, 'index'])->name('observasi-lapangan.index');
            Route::put('observasi-lapangan', [ObservasiLapanganController::class, 'update'])->name('observasi-lapangan.update');
            Route::post('observasi-lapangan/item', [ObservasiLapanganController::class, 'tambahItem'])->name('observasi-lapangan.item.store');
            Route::delete('observasi-lapangan/item/{item}', [ObservasiLapanganController::class, 'hapusItem'])->name('observasi-lapangan.item.destroy');
            Route::get('observasi-lapangan/cetak', [ObservasiLapanganController::class, 'cetak'])->name('observasi-lapangan.cetak');
        });

        // Panel absensi admin: rekap, QR, export (admin, koordinator & wakil koordinator)
        Route::middleware('can.pantau.operasional')->group(function () {
            Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
            Route::get('absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
            Route::post('absensi/izin-sakit', [AbsensiController::class, 'catatIzinSakit'])->name('absensi.catat-izin-sakit');
            Route::delete('absensi/{absensi}/hapus-catatan', [AbsensiController::class, 'hapusCatatan'])->name('absensi.hapus-catatan');
            Route::get('absensi/qr', [AbsensiController::class, 'qrPrint'])->name('absensi.qr');
            Route::post('absensi/qr/regenerate', [AbsensiController::class, 'regenerateToken'])->name('absensi.qr.regenerate');
            Route::get('absensi/display', [AbsensiController::class, 'display'])->name('absensi.display');
        });

        // Panel konten website & data anggota — khusus admin
        Route::middleware('can.manage.cms')->group(function () {
            Route::resource('anggota', AnggotaController::class)->except(['show'])->parameters(['anggota' => 'anggota']);
            Route::resource('program-kerja', ProgramKerjaController::class)->except(['show'])->parameters(['program-kerja' => 'proker']);
            Route::get('ukm/export', [UkmController::class, 'export'])->name('ukm.export');
            Route::get('ukm/cetak', [UkmController::class, 'cetak'])->name('ukm.cetak');
            Route::resource('ukm', UkmController::class)->except(['show']);
            Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
            Route::put('pengaturan/absensi', [PengaturanController::class, 'updateAbsensi'])->name('pengaturan.absensi');
        });

        // Pembuatan akun login anggota — khusus admin
        Route::middleware('role:admin')->group(function () {
            Route::post('anggota/{anggota}/akun', [AnggotaController::class, 'storeAkun'])->name('anggota.akun');
            Route::post('anggota/{anggota}/reset-password', [AnggotaController::class, 'resetPassword'])->name('anggota.reset-password');
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
            Route::get('laporan/daftar-hadir-mingguan', [LaporanController::class, 'daftarHadirMingguan'])->name('laporan.daftar-hadir-mingguan');
            Route::get('laporan/daftar-hadir-mingguan/pdf', [LaporanController::class, 'daftarHadirMingguanPdf'])->name('laporan.daftar-hadir-mingguan-pdf');
            Route::get('laporan/logbook-harian', [LaporanController::class, 'logbookHarian'])->name('laporan.logbook-harian');
            Route::get('laporan/logbook-harian/pdf', [LaporanController::class, 'logbookHarianPdf'])->name('laporan.logbook-harian-pdf');
            Route::get('laporan/rekap-keaktifan', [LaporanController::class, 'rekapKeaktifan'])->name('laporan.rekap-keaktifan');
            Route::get('laporan/rekap-keaktifan/pdf', [LaporanController::class, 'rekapKeaktifanPdf'])->name('laporan.rekap-keaktifan-pdf');
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
