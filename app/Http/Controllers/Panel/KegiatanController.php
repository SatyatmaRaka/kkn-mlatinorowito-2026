<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Penunjang\FilterPencarian;
use App\Penunjang\SanitizerHtml;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/** CRUD dokumentasi kegiatan KKN untuk halaman publik. */
class KegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $q = FilterPencarian::kataKunci($request->query('q'));

        $kegiatan = Kegiatan::query()
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, [
                'judul', 'deskripsi_singkat', 'konten',
            ]))
            ->orderByDesc('tanggal')
            ->get();

        return view('panel.kegiatan.index', compact('kegiatan', 'q'));
    }

    public function create(): View
    {
        return view('panel.kegiatan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi_singkat' => 'nullable|string',
            'konten' => 'nullable|string|max:20000',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kegiatan', 'public');
        }

        if (array_key_exists('konten', $validated)) {
            $validated['konten'] = SanitizerHtml::sanitize($validated['konten']);
        }

        Kegiatan::create($validated);

        return redirect()->route('panel.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan): View
    {
        return view('panel.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi_singkat' => 'nullable|string',
            'konten' => 'nullable|string|max:20000',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($kegiatan->foto) {
                Storage::disk('public')->delete($kegiatan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('kegiatan', 'public');
        }

        if (array_key_exists('konten', $validated)) {
            $validated['konten'] = SanitizerHtml::sanitize($validated['konten']);
        }

        $kegiatan->update($validated);

        return redirect()->route('panel.kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        if ($kegiatan->foto) {
            Storage::disk('public')->delete($kegiatan->foto);
        }

        $kegiatan->delete();

        return redirect()->route('panel.kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
