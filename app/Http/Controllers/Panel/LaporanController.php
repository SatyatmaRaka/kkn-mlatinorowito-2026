<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Layanan\LayananRingkasan;
use App\Penunjang\DaftarHadirMingguan;
use App\Penunjang\EksporCsv;
use App\Penunjang\LogbookLaporan;
use App\Penunjang\PembagiMingguKkn;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Laporan operasional KKN: ringkasan periode, cetak, export CSV. */
class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $mulai = $request->query('tanggal_mulai', now()->startOfMonth()->toDateString());
        $selesai = $request->query('tanggal_selesai', now()->toDateString());

        $ringkasan = $this->ringkasanUntukUser($mulai, $selesai);

        return view('panel.laporan.index', compact('ringkasan', 'mulai', 'selesai'));
    }

    public function cetak(Request $request): View
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $mulai = $request->query('tanggal_mulai', now()->startOfMonth()->toDateString());
        $selesai = $request->query('tanggal_selesai', now()->toDateString());

        $ringkasan = $this->ringkasanUntukUser($mulai, $selesai);

        return view('panel.laporan.cetak', compact('ringkasan', 'mulai', 'selesai'));
    }

    public function exportRingkasan(Request $request): StreamedResponse
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $mulai = $request->query('tanggal_mulai', now()->startOfMonth()->toDateString());
        $selesai = $request->query('tanggal_selesai', now()->toDateString());

        $ringkasan = $this->ringkasanUntukUser($mulai, $selesai);

        $rows = collect([
            ['Periode', $ringkasan['periode']['label']],
            ['Total Absensi', $ringkasan['absensi']['total']],
            ['Logbook Draft', $ringkasan['logbook']['draft']],
            ['Logbook Menunggu Review', $ringkasan['logbook']['submitted']],
            ['Logbook Disetujui', $ringkasan['logbook']['approved']],
            ['Logbook Ditolak', $ringkasan['logbook']['rejected']],
        ]);

        if (isset($ringkasan['keuangan'])) {
            $rows = $rows->concat([
                ['Pemasukan (Rp)', $ringkasan['keuangan']['pemasukan']],
                ['Pengeluaran (Rp)', $ringkasan['keuangan']['pengeluaran']],
                ['Saldo Periode (Rp)', $ringkasan['keuangan']['saldo']],
            ]);
        }

        return EksporCsv::download(
            'laporan-kkn-'.$mulai.'-'.$selesai.'.csv',
            ['Indikator', 'Nilai'],
            $rows
        );
    }

    public function daftarHadirMingguan(Request $request): View|RedirectResponse
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $data = $this->dataDaftarHadirMingguan($request);

        if ($data instanceof RedirectResponse) {
            return $data;
        }

        return view('panel.laporan.daftar-hadir-mingguan', $data);
    }

    public function daftarHadirMingguanPdf(Request $request)
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $data = $this->dataDaftarHadirMingguan($request);

        if ($data instanceof RedirectResponse) {
            return $data;
        }

        $nomor = $data['mingguDipilih'];

        return Pdf::loadView('panel.laporan.daftar-hadir-mingguan', array_merge($data, ['untukPdf' => true]))
            ->setPaper('a4', 'landscape')
            ->download("daftar-hadir-minggu-{$nomor}.pdf");
    }

    /** @return array<string, mixed>|RedirectResponse */
    private function dataDaftarHadirMingguan(Request $request): array|RedirectResponse
    {
        $mingguDiminta = max(1, (int) $request->query('minggu', 1));
        $jumlahMinggu = PembagiMingguKkn::jumlahMinggu();

        if ($jumlahMinggu === 0) {
            return [
                'mingguDipilih' => 1,
                'jumlahMinggu' => 0,
                'daftar' => null,
                'peringatan' => 'Periode KKN belum diatur. Isi tanggal mulai dan selesai KKN di Pengaturan.',
            ];
        }

        if ($mingguDiminta > $jumlahMinggu) {
            return redirect()
                ->route('panel.laporan.daftar-hadir-mingguan', ['minggu' => 1])
                ->with('warning', 'Minggu tidak tersedia. Menampilkan minggu ke-1.');
        }

        $minggu = PembagiMingguKkn::mingguByNomor($mingguDiminta);
        $daftar = DaftarHadirMingguan::susun($minggu);

        return [
            'mingguDipilih' => $mingguDiminta,
            'jumlahMinggu' => $jumlahMinggu,
            'daftar' => $daftar,
            'peringatan' => null,
        ];
    }

    public function logbookHarian(Request $request): View
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        [$defaultDari, $defaultSampai] = LogbookLaporan::rentangDefault();
        $dari = $request->query('dari', $defaultDari);
        $sampai = $request->query('sampai', $defaultSampai);
        $anggotaId = $request->filled('anggota_id') ? (int) $request->query('anggota_id') : null;

        $data = LogbookLaporan::dataLogbookHarian($dari, $sampai, $anggotaId);

        return view('panel.laporan.logbook-harian', $data);
    }

    public function logbookHarianPdf(Request $request)
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        [$defaultDari, $defaultSampai] = LogbookLaporan::rentangDefault();
        $dari = $request->query('dari', $defaultDari);
        $sampai = $request->query('sampai', $defaultSampai);
        $anggotaId = $request->filled('anggota_id') ? (int) $request->query('anggota_id') : null;

        $data = LogbookLaporan::dataLogbookHarian($dari, $sampai, $anggotaId);

        return Pdf::loadView('panel.laporan.logbook-harian', array_merge($data, ['untukPdf' => true]))
            ->setPaper('a4', 'landscape')
            ->download("logbook-harian-{$dari}-{$sampai}.pdf");
    }

    public function rekapKeaktifan(Request $request): View
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $data = LogbookLaporan::dataRekapKeaktifan();

        return view('panel.laporan.rekap-keaktifan', $data);
    }

    public function rekapKeaktifanPdf(Request $request)
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $data = LogbookLaporan::dataRekapKeaktifan();

        return Pdf::loadView('panel.laporan.rekap-keaktifan', array_merge($data, ['untukPdf' => true]))
            ->setPaper('a4', 'portrait')
            ->download('rekap-keaktifan-'.now()->format('Y-m-d').'.pdf');
    }

    private function bolehAksesLaporan(): bool
    {
        $user = Auth::user();

        return $user && (
            $user->canReviewLogbook()
            || $user->canManageKeuangan()
        );
    }

    /** @return array<string, mixed> */
    private function ringkasanUntukUser(string $mulai, string $selesai): array
    {
        return LayananRingkasan::ringkasanPeriode(
            $mulai,
            $selesai,
            Auth::user()->canManageKeuangan()
        );
    }
}
