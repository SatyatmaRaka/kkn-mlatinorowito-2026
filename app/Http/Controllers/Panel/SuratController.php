<?php

namespace App\Http\Controllers\Panel;

use App\Enums\KategoriTujuanSurat;
use App\Http\Controllers\Controller;
use App\Layanan\LayananPengaturan;
use App\Models\Surat;
use App\Penunjang\GeneratorSuratKeluar;
use App\Penunjang\PenandatanganSurat;
use App\Penunjang\PenerimaSurat;
use App\Penunjang\FilterPencarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Arsip surat masuk & keluar — surat keluar digenerate PDF oleh sistem. */
class SuratController extends Controller
{
    public function index(Request $request): View
    {
        $jenis = $request->query('jenis');
        $kategori = $request->query('kategori_tujuan');
        $q = FilterPencarian::kataKunci($request->query('q'));

        $surat = Surat::with('user')
            ->when(in_array($jenis, ['masuk', 'keluar'], true), fn ($query) => $query->where('jenis', $jenis))
            ->when(in_array($kategori, KategoriTujuanSurat::values(), true), fn ($query) => $query->where('kategori_tujuan', $kategori))
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, [
                'nomor_surat',
                'asal_tujuan',
                'perihal',
                'keterangan',
            ]))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('panel.surat.index', compact('surat', 'jenis', 'kategori', 'q'));
    }

    public function create(Request $request): View
    {
        $jenis = in_array($request->query('jenis'), ['masuk', 'keluar'], true)
            ? $request->query('jenis')
            : null;

        $kategori = in_array($request->query('kategori'), KategoriTujuanSurat::values(), true)
            ? $request->query('kategori')
            : null;

        return view('panel.surat.create', compact('jenis', 'kategori'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSurat($request);

        if ($validated['jenis'] === 'keluar') {
            $validated = PenerimaSurat::lengkapiDataKeluar($validated);
        } else {
            unset($validated['kategori_tujuan'], $validated['nomor_rt'], $validated['nomor_rw']);
        }

        if ($validated['jenis'] === 'masuk' && $request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('surat', 'public');
        }

        $validated['user_id'] = Auth::id();

        $surat = Surat::create($validated);

        if ($surat->jenis === 'keluar') {
            return $this->afterSuratKeluarSaved($surat, 'Surat keluar berhasil dibuat. Unduh PDF-nya di bawah.');
        }

        return redirect()->route('panel.surat.index')->with('success', 'Surat masuk berhasil dicatat.');
    }

    public function edit(Surat $surat): View
    {
        return view('panel.surat.edit', compact('surat'));
    }

    public function update(Request $request, Surat $surat): RedirectResponse
    {
        $validated = $this->validateSurat($request, $surat);

        if ($validated['jenis'] === 'keluar') {
            $validated = PenerimaSurat::lengkapiDataKeluar($validated);
        } else {
            unset($validated['kategori_tujuan'], $validated['nomor_rt'], $validated['nomor_rw']);
        }

        if ($surat->jenis === 'masuk' && $request->hasFile('lampiran')) {
            if ($surat->lampiran) {
                Storage::disk('public')->delete($surat->lampiran);
            }
            $validated['lampiran'] = $request->file('lampiran')->store('surat', 'public');
        }

        if ($validated['jenis'] === 'keluar') {
            unset($validated['lampiran']);
        }

        $surat->update($validated);

        if ($surat->jenis === 'keluar') {
            return $this->afterSuratKeluarSaved($surat->fresh(), 'Surat keluar diperbarui. PDF sistem telah digenerate ulang.');
        }

        return redirect()->route('panel.surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(Surat $surat): RedirectResponse
    {
        $surat->delete();

        return redirect()->route('panel.surat.index')->with('success', 'Surat berhasil dihapus.');
    }

    /** Pratinjau surat keluar di browser. */
    public function cetak(Surat $surat): View
    {
        abort_unless($surat->jenis === 'keluar', 404);

        return view('panel.surat.cetak', [
            'surat' => $surat,
            'pengaturan' => LayananPengaturan::get(),
            'penandatangan' => PenandatanganSurat::data($surat->user),
            'logoKkn' => PenandatanganSurat::logoKkn(dataUri: false),
            'logoUmk' => PenandatanganSurat::logoUmk(dataUri: false),
            'paragrafIsi' => PenandatanganSurat::paragrafIsi($surat->keterangan),
        ]);
    }

    /** Unduh PDF surat keluar yang digenerate sistem. */
    public function unduh(Surat $surat): StreamedResponse
    {
        abort_unless($surat->jenis === 'keluar' && $surat->lampiran, 404);

        return Storage::disk('public')->download(
            $surat->lampiran,
            GeneratorSuratKeluar::namaFileUnduh($surat)
        );
    }

    /** Generate ulang PDF surat keluar. */
    public function generateUlang(Surat $surat): RedirectResponse
    {
        abort_unless($surat->jenis === 'keluar', 404);

        return $this->afterSuratKeluarSaved($surat, 'PDF surat berhasil digenerate ulang.');
    }

    private function afterSuratKeluarSaved(Surat $surat, string $message): RedirectResponse
    {
        if (empty($surat->nomor_surat)) {
            $surat->update(['nomor_surat' => GeneratorSuratKeluar::nomorOtomatis($surat)]);
            $surat->refresh();
        }

        $path = GeneratorSuratKeluar::generate($surat, Auth::user());
        $surat->update(['lampiran' => $path]);

        return redirect()
            ->route('panel.surat.edit', $surat)
            ->with('success', $message);
    }

    /** @return array<string, mixed> */
    private function validateSurat(Request $request, ?Surat $surat = null): array
    {
        $rules = [
            'jenis' => 'required|in:masuk,keluar',
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:2000',
        ];

        if ($request->input('jenis') === 'masuk') {
            $rules['nomor_surat'] = [
                'nullable',
                'string',
                'max:100',
                Rule::unique('surat', 'nomor_surat')
                    ->ignore($surat?->id)
                    ->whereNull('deleted_at'),
            ];
            $rules['asal_tujuan'] = 'required|string|max:255';
            $rules['lampiran'] = 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120';
        }

        if ($request->input('jenis') === 'keluar') {
            $rules['nomor_surat'] = 'nullable|string|max:100';
            $rules['keterangan'] = 'required|string|max:2000';
            $rules['kategori_tujuan'] = ['required', Rule::in(KategoriTujuanSurat::values())];
            $rules['nomor_rt'] = [
                Rule::requiredIf($request->input('kategori_tujuan') === KategoriTujuanSurat::Rt->value),
                'nullable',
                'string',
                'max:10',
            ];
            $rules['nomor_rw'] = [
                Rule::requiredIf($request->input('kategori_tujuan') === KategoriTujuanSurat::Rw->value),
                'nullable',
                'string',
                'max:10',
            ];
            $rules['asal_tujuan'] = [
                Rule::requiredIf($request->input('kategori_tujuan') === KategoriTujuanSurat::Instansi->value),
                'nullable',
                'string',
                'max:255',
            ];
        }

        return $request->validate($rules, [
            'nomor_surat.unique' => 'Nomor surat ini sudah digunakan pada arsip lain.',
        ]);
    }
}
