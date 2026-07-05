<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Layanan\LayananPengaturan;
use App\Models\Anggota;
use App\Models\KegiatanPelaksanaan;
use App\Models\KegiatanPesertaMasyarakat;
use App\Models\KegiatanTugasTim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KegiatanPelaksanaanController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', KegiatanPelaksanaan::class);

        $tanggal = $request->query('tanggal');

        $kegiatan = KegiatanPelaksanaan::query()
            ->with(['pic', 'dibuatOleh'])
            ->withCount(['pesertaMasyarakat', 'tugasTim'])
            ->when($tanggal, fn ($q) => $q->whereDate('tanggal', $tanggal))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('panel.kegiatan-pelaksanaan.index', compact('kegiatan', 'tanggal'));
    }

    public function create(): View
    {
        $this->authorize('create', KegiatanPelaksanaan::class);

        $anggotaList = Anggota::query()->orderBy('urutan')->get();

        return view('panel.kegiatan-pelaksanaan.create', compact('anggotaList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', KegiatanPelaksanaan::class);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tempat' => 'required|string|max:255',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'pic_anggota_id' => 'nullable|exists:anggota,id',
            'peserta_nama' => 'nullable|array',
            'peserta_nama.*' => 'nullable|string|max:255',
            'peserta_alamat' => 'nullable|array',
            'peserta_alamat.*' => 'nullable|string|max:255',
            'tugas_anggota_id' => 'nullable|array',
            'tugas_anggota_id.*' => 'nullable|exists:anggota,id',
            'tugas_deskripsi' => 'nullable|array',
            'tugas_deskripsi.*' => 'nullable|string|max:255',
        ]);

        $kegiatan = KegiatanPelaksanaan::create([
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tanggal' => $validated['tanggal'],
            'tempat' => $validated['tempat'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'pic_anggota_id' => $validated['pic_anggota_id'] ?? null,
            'dibuat_oleh' => Auth::id(),
        ]);

        $this->simpanPeserta($kegiatan, $validated['peserta_nama'] ?? [], $validated['peserta_alamat'] ?? []);
        $this->simpanTugas($kegiatan, $validated['tugas_anggota_id'] ?? [], $validated['tugas_deskripsi'] ?? []);

        return redirect()->route('panel.kegiatan-pelaksanaan.show', $kegiatan)
            ->with('success', 'Kegiatan pelaksanaan berhasil ditambahkan.');
    }

    public function show(KegiatanPelaksanaan $kegiatanPelaksanaan): View
    {
        $this->authorize('view', $kegiatanPelaksanaan);

        $kegiatanPelaksanaan->load(['pic', 'pesertaMasyarakat', 'tugasTim.anggota']);
        $anggotaList = Anggota::query()->orderBy('urutan')->get();

        return view('panel.kegiatan-pelaksanaan.show', [
            'kegiatan' => $kegiatanPelaksanaan,
            'anggotaList' => $anggotaList,
        ]);
    }

    public function edit(KegiatanPelaksanaan $kegiatanPelaksanaan): View
    {
        $this->authorize('update', $kegiatanPelaksanaan);

        $anggotaList = Anggota::query()->orderBy('urutan')->get();

        return view('panel.kegiatan-pelaksanaan.edit', [
            'kegiatan' => $kegiatanPelaksanaan,
            'anggotaList' => $anggotaList,
        ]);
    }

    public function update(Request $request, KegiatanPelaksanaan $kegiatanPelaksanaan): RedirectResponse
    {
        $this->authorize('update', $kegiatanPelaksanaan);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tempat' => 'required|string|max:255',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'pic_anggota_id' => 'nullable|exists:anggota,id',
        ]);

        $kegiatanPelaksanaan->update($validated);

        return redirect()->route('panel.kegiatan-pelaksanaan.show', $kegiatanPelaksanaan)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(KegiatanPelaksanaan $kegiatanPelaksanaan): RedirectResponse
    {
        $this->authorize('delete', $kegiatanPelaksanaan);

        $kegiatanPelaksanaan->delete();

        return redirect()->route('panel.kegiatan-pelaksanaan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function tambahPeserta(Request $request, KegiatanPelaksanaan $kegiatanPelaksanaan): RedirectResponse
    {
        $this->authorize('view', $kegiatanPelaksanaan);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
        ]);

        $kegiatanPelaksanaan->pesertaMasyarakat()->create($validated);

        return back()->with('success', 'Peserta masyarakat ditambahkan.');
    }

    public function hapusPeserta(KegiatanPelaksanaan $kegiatanPelaksanaan, KegiatanPesertaMasyarakat $peserta): RedirectResponse
    {
        abort_unless(Auth::user()?->canReviewLogbook(), 403);
        abort_unless($peserta->kegiatan_pelaksanaan_id === $kegiatanPelaksanaan->id, 404);

        $peserta->delete();

        return back()->with('success', 'Peserta dihapus.');
    }

    public function tambahTugas(Request $request, KegiatanPelaksanaan $kegiatanPelaksanaan): RedirectResponse
    {
        $this->authorize('view', $kegiatanPelaksanaan);

        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'tugas' => 'required|string|max:255',
        ]);

        if ($kegiatanPelaksanaan->tugasTim()->where('anggota_id', $validated['anggota_id'])->exists()) {
            return back()->withErrors(['anggota_id' => 'Anggota sudah memiliki tugas di kegiatan ini.']);
        }

        $kegiatanPelaksanaan->tugasTim()->create($validated);

        return back()->with('success', 'Tugas tim ditambahkan.');
    }

    public function hapusTugas(KegiatanPelaksanaan $kegiatanPelaksanaan, KegiatanTugasTim $tugas): RedirectResponse
    {
        abort_unless(Auth::user()?->canReviewLogbook(), 403);
        abort_unless($tugas->kegiatan_pelaksanaan_id === $kegiatanPelaksanaan->id, 404);

        $tugas->delete();

        return back()->with('success', 'Tugas tim dihapus.');
    }

    public function cetakMasyarakat(KegiatanPelaksanaan $kegiatanPelaksanaan): View
    {
        $this->authorize('view', $kegiatanPelaksanaan);

        $kegiatanPelaksanaan->load(['pic', 'pesertaMasyarakat']);
        $pengaturan = LayananPengaturan::get();

        return view('panel.kegiatan-pelaksanaan.cetak-masyarakat', [
            'kegiatan' => $kegiatanPelaksanaan,
            'desa' => $pengaturan->get('desa', ''),
            'kecamatan' => $pengaturan->get('kecamatan', ''),
            'kabupaten' => $pengaturan->get('kabupaten', ''),
            'nama_dpl' => $pengaturan->get('nama_dpl', ''),
            'nidn_dpl' => $pengaturan->get('nidn_dpl', ''),
        ]);
    }

    public function cetakTim(KegiatanPelaksanaan $kegiatanPelaksanaan): View
    {
        $this->authorize('view', $kegiatanPelaksanaan);

        $kegiatanPelaksanaan->load(['pic', 'tugasTim.anggota']);
        $pengaturan = LayananPengaturan::get();

        return view('panel.kegiatan-pelaksanaan.cetak-tim', [
            'kegiatan' => $kegiatanPelaksanaan,
            'desa' => $pengaturan->get('desa', ''),
            'kecamatan' => $pengaturan->get('kecamatan', ''),
            'kabupaten' => $pengaturan->get('kabupaten', ''),
            'nama_dpl' => $pengaturan->get('nama_dpl', ''),
            'nidn_dpl' => $pengaturan->get('nidn_dpl', ''),
        ]);
    }

    /** @param  list<string|null>  $namaList */
    private function simpanPeserta(KegiatanPelaksanaan $kegiatan, array $namaList, array $alamatList): void
    {
        foreach ($namaList as $i => $nama) {
            if (! $nama) {
                continue;
            }

            $kegiatan->pesertaMasyarakat()->create([
                'nama' => $nama,
                'alamat' => $alamatList[$i] ?? null,
            ]);
        }
    }

    /** @param  list<int|null>  $anggotaIds */
    private function simpanTugas(KegiatanPelaksanaan $kegiatan, array $anggotaIds, array $tugasList): void
    {
        $sudah = [];

        foreach ($anggotaIds as $i => $anggotaId) {
            if (! $anggotaId || ! ($tugasList[$i] ?? null) || in_array($anggotaId, $sudah, true)) {
                continue;
            }

            $kegiatan->tugasTim()->create([
                'anggota_id' => $anggotaId,
                'tugas' => $tugasList[$i],
            ]);

            $sudah[] = $anggotaId;
        }
    }
}
