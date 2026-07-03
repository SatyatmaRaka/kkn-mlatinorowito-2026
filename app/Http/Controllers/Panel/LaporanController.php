<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Layanan\LayananRingkasan;
use App\Penunjang\EksporCsv;
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

        $ringkasan = LayananRingkasan::ringkasanPeriode($mulai, $selesai);

        return view('panel.laporan.index', compact('ringkasan', 'mulai', 'selesai'));
    }

    public function cetak(Request $request): View
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $mulai = $request->query('tanggal_mulai', now()->startOfMonth()->toDateString());
        $selesai = $request->query('tanggal_selesai', now()->toDateString());

        $ringkasan = LayananRingkasan::ringkasanPeriode($mulai, $selesai);

        return view('panel.laporan.cetak', compact('ringkasan', 'mulai', 'selesai'));
    }

    public function exportRingkasan(Request $request): StreamedResponse
    {
        abort_unless($this->bolehAksesLaporan(), 403);

        $mulai = $request->query('tanggal_mulai', now()->startOfMonth()->toDateString());
        $selesai = $request->query('tanggal_selesai', now()->toDateString());

        $ringkasan = LayananRingkasan::ringkasanPeriode($mulai, $selesai);

        $rows = collect([
            ['Periode', $ringkasan['periode']['label']],
            ['Total Absensi', $ringkasan['absensi']['total']],
            ['Logbook Draft', $ringkasan['logbook']['draft']],
            ['Logbook Menunggu Review', $ringkasan['logbook']['submitted']],
            ['Logbook Disetujui', $ringkasan['logbook']['approved']],
            ['Logbook Ditolak', $ringkasan['logbook']['rejected']],
            ['Pemasukan (Rp)', $ringkasan['keuangan']['pemasukan']],
            ['Pengeluaran (Rp)', $ringkasan['keuangan']['pengeluaran']],
            ['Saldo Periode (Rp)', $ringkasan['keuangan']['saldo']],
        ]);

        return EksporCsv::download(
            'laporan-kkn-'.$mulai.'-'.$selesai.'.csv',
            ['Indikator', 'Nilai'],
            $rows
        );
    }

    private function bolehAksesLaporan(): bool
    {
        $user = Auth::user();

        return $user && (
            $user->canReviewLogbook()
            || $user->canManageKeuangan()
        );
    }
}
