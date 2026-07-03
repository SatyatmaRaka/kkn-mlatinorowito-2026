<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/** Arsip surat masuk & keluar (akses CMS / sekretaris). */
class SuratController extends Controller
{
    public function index(Request $request): View
    {
        $jenis = $request->query('jenis');

        $surat = Surat::with('user')
            ->when(in_array($jenis, ['masuk', 'keluar'], true), fn ($q) => $q->where('jenis', $jenis))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('panel.surat.index', compact('surat', 'jenis'));
    }

    public function create(): View
    {
        return view('panel.surat.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSurat($request);

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('surat', 'public');
        }

        $validated['user_id'] = Auth::id();

        Surat::create($validated);

        return redirect()->route('panel.surat.index')->with('success', 'Surat berhasil dicatat.');
    }

    public function edit(Surat $surat): View
    {
        return view('panel.surat.edit', compact('surat'));
    }

    public function update(Request $request, Surat $surat): RedirectResponse
    {
        $validated = $this->validateSurat($request);

        if ($request->hasFile('lampiran')) {
            if ($surat->lampiran) {
                Storage::disk('public')->delete($surat->lampiran);
            }
            $validated['lampiran'] = $request->file('lampiran')->store('surat', 'public');
        }

        $surat->update($validated);

        return redirect()->route('panel.surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(Surat $surat): RedirectResponse
    {
        if ($surat->lampiran) {
            Storage::disk('public')->delete($surat->lampiran);
        }

        $surat->delete();

        return redirect()->route('panel.surat.index')->with('success', 'Surat berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validateSurat(Request $request): array
    {
        return $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'nomor_surat' => 'nullable|string|max:100',
            'tanggal' => 'required|date',
            'asal_tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:2000',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);
    }
}
