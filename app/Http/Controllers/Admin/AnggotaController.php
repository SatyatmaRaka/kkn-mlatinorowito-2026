<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function index(): View
    {
        $anggota = Anggota::orderBy('urutan')->get();

        return view('admin.anggota.index', compact('anggota'));
    }

    public function create(): View
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'jurusan' => 'required|string|max:255',
            'jabatan' => ['required', 'string', Rule::in(['Koordinator Desa', 'Wakil Koordinator', 'PDD', 'Perlengkapan', 'Humas', 'Sekretaris', 'Bendahara'])],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        Anggota::create($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota): View
    {
        return view('admin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'jurusan' => 'required|string|max:255',
            'jabatan' => ['required', 'string', Rule::in(['Koordinator Desa', 'Wakil Koordinator', 'PDD', 'Perlengkapan', 'Humas', 'Sekretaris', 'Bendahara'])],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('foto')) {
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $validated['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        $anggota->update($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota): RedirectResponse
    {
        if ($anggota->foto) {
            Storage::disk('public')->delete($anggota->foto);
        }

        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
