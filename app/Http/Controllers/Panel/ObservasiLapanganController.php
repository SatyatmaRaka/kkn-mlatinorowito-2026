<?php

namespace App\Http\Controllers\Panel;

use App\Enums\Jabatan;
use App\Http\Controllers\Controller;
use App\Layanan\LayananPengaturan;
use App\Models\Anggota;
use App\Models\ObservasiLapangan;
use App\Models\ObservasiLapanganItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObservasiLapanganController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', ObservasiLapangan::class);

        $observasi = ObservasiLapangan::ambilOrBuatDefault();

        return view('panel.observasi-lapangan.index', compact('observasi'));
    }

    public function update(Request $request): RedirectResponse
    {
        $observasi = ObservasiLapangan::ambilOrBuatDefault();
        $this->authorize('update', $observasi);

        $validated = $request->validate([
            'ringkasan_permasalahan' => 'nullable|string|max:2000',
            'rencana_pemecahan' => 'nullable|string|max:2000',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:observasi_lapangan_item,id',
            'items.*.status' => 'required|in:ada,tidak',
            'items.*.permasalahan' => 'nullable|string|max:1000',
            'items.*.rencana_pemecahan_masalah' => 'nullable|string|max:1000',
        ]);

        $observasi->update([
            'ringkasan_permasalahan' => $validated['ringkasan_permasalahan'] ?? null,
            'rencana_pemecahan' => $validated['rencana_pemecahan'] ?? null,
        ]);

        foreach ($validated['items'] as $itemData) {
            $item = ObservasiLapanganItem::query()
                ->where('observasi_lapangan_id', $observasi->id)
                ->whereKey($itemData['id'])
                ->firstOrFail();

            $item->update([
                'status' => $itemData['status'],
                'permasalahan' => $itemData['permasalahan'] ?? null,
                'rencana_pemecahan_masalah' => $itemData['rencana_pemecahan_masalah'] ?? null,
            ]);
        }

        return redirect()->route('panel.observasi-lapangan.index')
            ->with('success', 'Observasi lapangan berhasil disimpan.');
    }

    public function tambahItem(Request $request): RedirectResponse
    {
        $observasi = ObservasiLapangan::ambilOrBuatDefault();
        $this->authorize('update', $observasi);

        $validated = $request->validate([
            'nama_kelembagaan' => 'required|string|max:255',
        ]);

        $urutanMax = $observasi->items()->max('urutan') ?? 0;

        $observasi->items()->create([
            'nama_kelembagaan' => $validated['nama_kelembagaan'],
            'status' => 'tidak',
            'urutan' => $urutanMax + 1,
        ]);

        return back()->with('success', 'Kelembagaan tambahan berhasil ditambahkan.');
    }

    public function hapusItem(ObservasiLapanganItem $item): RedirectResponse
    {
        $observasi = $item->observasiLapangan;
        $this->authorize('delete', $observasi);

        if (in_array($item->nama_kelembagaan, ObservasiLapangan::KELEMBAGAAN_WAJIB, true)) {
            abort(403, 'Baris kelembagaan wajib tidak dapat dihapus.');
        }

        $item->delete();

        return back()->with('success', 'Kelembagaan tambahan berhasil dihapus.');
    }

    public function cetak(): View
    {
        $this->authorize('viewAny', ObservasiLapangan::class);

        $observasi = ObservasiLapangan::ambilOrBuatDefault();
        $pengaturan = LayananPengaturan::get();

        $kordes = Anggota::query()
            ->where('jabatan', Jabatan::KoordinatorDesa->value)
            ->first();

        return view('panel.observasi-lapangan.cetak', [
            'observasi' => $observasi,
            'desa' => $pengaturan->get('desa', ''),
            'kecamatan' => $pengaturan->get('kecamatan', ''),
            'kabupaten' => $pengaturan->get('kabupaten', ''),
            'nama_dpl' => $pengaturan->get('nama_dpl', ''),
            'nidn_dpl' => $pengaturan->get('nidn_dpl', ''),
            'kordes_nama' => $kordes?->nama ?? '',
            'kordes_nim' => $kordes?->nim ?? '',
        ]);
    }
}
