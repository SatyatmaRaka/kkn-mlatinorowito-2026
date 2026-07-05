<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Layanan\LayananPengaturan;
use App\Models\Anggota;
use App\Models\BukuTamu;
use App\Penunjang\EksporCsv;
use App\Penunjang\FilterPencarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BukuTamuController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', BukuTamu::class);

        $q = FilterPencarian::kataKunci($request->query('q'));
        $tanggal = $request->query('tanggal');

        $tamu = BukuTamu::query()
            ->with(['anggota', 'pencatat'])
            ->when($tanggal, fn ($query) => $query->whereDate('tanggal', $tanggal))
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, ['nama_tamu', 'alamat_jabatan', 'keperluan']))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('panel.buku-tamu.index', compact('tamu', 'q', 'tanggal'));
    }

    public function create(): View
    {
        $this->authorize('create', BukuTamu::class);

        $anggotaList = Anggota::query()->orderBy('urutan')->get();

        return view('panel.buku-tamu.create', compact('anggotaList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', BukuTamu::class);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_tamu' => 'required|string|max:255',
            'alamat_jabatan' => 'nullable|string|max:255',
            'keperluan' => 'required|string',
            'anggota_id' => 'nullable|exists:anggota,id',
        ]);

        BukuTamu::create([
            ...$validated,
            'dicatat_oleh' => Auth::id(),
        ]);

        return redirect()->route('panel.buku-tamu.index')->with('success', 'Tamu berhasil dicatat.');
    }

    public function edit(BukuTamu $bukuTamu): View
    {
        $this->authorize('update', $bukuTamu);

        $anggotaList = Anggota::query()->orderBy('urutan')->get();

        return view('panel.buku-tamu.edit', compact('bukuTamu', 'anggotaList'));
    }

    public function update(Request $request, BukuTamu $bukuTamu): RedirectResponse
    {
        $this->authorize('update', $bukuTamu);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_tamu' => 'required|string|max:255',
            'alamat_jabatan' => 'nullable|string|max:255',
            'keperluan' => 'required|string',
            'anggota_id' => 'nullable|exists:anggota,id',
        ]);

        $bukuTamu->update($validated);

        return redirect()->route('panel.buku-tamu.index')->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(BukuTamu $bukuTamu): RedirectResponse
    {
        $this->authorize('delete', $bukuTamu);

        $bukuTamu->delete();

        return redirect()->route('panel.buku-tamu.index')->with('success', 'Data tamu berhasil dihapus.');
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', BukuTamu::class);

        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $rows = BukuTamu::query()
            ->with(['anggota', 'pencatat'])
            ->when($dari, fn ($q) => $q->whereDate('tanggal', '>=', $dari))
            ->when($sampai, fn ($q) => $q->whereDate('tanggal', '<=', $sampai))
            ->orderByDesc('tanggal')
            ->get()
            ->values()
            ->map(fn (BukuTamu $item, int $i) => [
                $i + 1,
                $item->tanggal->format('d/m/Y'),
                $item->nama_tamu,
                $item->alamat_jabatan ?? '',
                $item->keperluan,
                $item->anggota?->nama ?? '',
                $item->pencatat?->name ?? '',
            ]);

        return EksporCsv::download(
            'buku-tamu-'.now()->format('Y-m-d').'.csv',
            ['No', 'Tanggal', 'Nama Tamu', 'Alamat/Jabatan', 'Keperluan', 'Mahasiswa yang Menemui', 'Dicatat Oleh'],
            $rows
        );
    }

    public function cetak(Request $request): View
    {
        $this->authorize('viewAny', BukuTamu::class);

        $dari = $request->query('dari');
        $sampai = $request->query('sampai');
        $pengaturan = LayananPengaturan::get();

        $tamu = BukuTamu::query()
            ->with('anggota')
            ->when($dari, fn ($q) => $q->whereDate('tanggal', '>=', $dari))
            ->when($sampai, fn ($q) => $q->whereDate('tanggal', '<=', $sampai))
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        return view('panel.buku-tamu.cetak', [
            'tamu' => $tamu,
            'dari' => $dari,
            'sampai' => $sampai,
            'desa' => $pengaturan->get('desa', ''),
            'kecamatan' => $pengaturan->get('kecamatan', ''),
            'kabupaten' => $pengaturan->get('kabupaten', ''),
        ]);
    }
}
