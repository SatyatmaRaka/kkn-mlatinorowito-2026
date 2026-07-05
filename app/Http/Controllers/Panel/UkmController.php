<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Layanan\LayananPengaturan;
use App\Models\Ukm;
use App\Penunjang\EksporCsv;
use App\Penunjang\FilterPencarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UkmController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Ukm::class);

        $q = FilterPencarian::kataKunci($request->query('q'));

        $ukm = Ukm::query()
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, [
                'nama_usaha', 'jenis_usaha', 'rata_rata_omzet', 'jangkauan_pemasaran', 'keterangan',
            ]))
            ->orderBy('urutan')
            ->orderBy('nama_usaha')
            ->paginate(20)
            ->withQueryString();

        return view('panel.ukm.index', compact('ukm', 'q'));
    }

    public function create(): View
    {
        $this->authorize('create', Ukm::class);

        return view('panel.ukm.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Ukm::class);

        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'rata_rata_omzet' => 'nullable|string|max:255',
            'jangkauan_pemasaran' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'urutan' => 'required|integer|min:0',
        ]);

        Ukm::create($validated);

        return redirect()->route('panel.ukm.index')->with('success', 'Data UKM berhasil ditambahkan.');
    }

    public function edit(Ukm $ukm): View
    {
        $this->authorize('update', $ukm);

        return view('panel.ukm.edit', compact('ukm'));
    }

    public function update(Request $request, Ukm $ukm): RedirectResponse
    {
        $this->authorize('update', $ukm);

        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'rata_rata_omzet' => 'nullable|string|max:255',
            'jangkauan_pemasaran' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'urutan' => 'required|integer|min:0',
        ]);

        $ukm->update($validated);

        return redirect()->route('panel.ukm.index')->with('success', 'Data UKM berhasil diperbarui.');
    }

    public function destroy(Ukm $ukm): RedirectResponse
    {
        $this->authorize('delete', $ukm);

        $ukm->delete();

        return redirect()->route('panel.ukm.index')->with('success', 'Data UKM berhasil dihapus.');
    }

    public function export(): StreamedResponse
    {
        $this->authorize('viewAny', Ukm::class);

        $rows = Ukm::query()
            ->orderBy('urutan')
            ->orderBy('nama_usaha')
            ->get()
            ->values()
            ->map(fn (Ukm $item, int $i) => [
                $i + 1,
                $item->nama_usaha,
                $item->jenis_usaha,
                $item->rata_rata_omzet ?? '',
                $item->jangkauan_pemasaran ?? '',
                $item->keterangan ?? '',
            ]);

        return EksporCsv::download(
            'pemetaan-ukm-'.now()->format('Y-m-d').'.csv',
            ['No', 'Nama UKM/Usaha', 'Jenis Usaha', 'Rata-rata Omzet/bulan', 'Jangkauan Pemasaran', 'Keterangan'],
            $rows
        );
    }

    public function cetak(): View
    {
        $this->authorize('viewAny', Ukm::class);

        $pengaturan = LayananPengaturan::get();

        $ukm = Ukm::query()->orderBy('urutan')->orderBy('nama_usaha')->get();

        return view('panel.ukm.cetak', [
            'ukm' => $ukm,
            'desa' => $pengaturan->get('desa', ''),
            'kecamatan' => $pengaturan->get('kecamatan', ''),
            'kabupaten' => $pengaturan->get('kabupaten', ''),
        ]);
    }
}
