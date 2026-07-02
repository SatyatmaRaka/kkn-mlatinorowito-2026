<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** CRUD program kerja kelompok KKN di halaman publik. */
class ProgramKerjaController extends Controller
{
    public function index(): View
    {
        $programKerja = ProgramKerja::orderBy('urutan')->get();

        return view('panel.program-kerja.index', compact('programKerja'));
    }

    public function create(): View
    {
        return view('panel.program-kerja.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tema' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'pic' => 'nullable|string|max:255',
            'status' => 'required|in:Coming Soon,Aktif',
            'urutan' => 'required|integer|min:1',
        ]);

        ProgramKerja::create($validated);

        return redirect()->route('panel.program-kerja.index')->with('success', 'Program kerja berhasil ditambahkan.');
    }

    public function edit(ProgramKerja $proker): View
    {
        return view('panel.program-kerja.edit', ['programKerja' => $proker]);
    }

    public function update(Request $request, ProgramKerja $proker): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tema' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'pic' => 'nullable|string|max:255',
            'status' => 'required|in:Coming Soon,Aktif',
            'urutan' => 'required|integer|min:1',
        ]);

        $proker->update($validated);

        return redirect()->route('panel.program-kerja.index')->with('success', 'Program kerja berhasil diperbarui.');
    }

    public function destroy(ProgramKerja $proker): RedirectResponse
    {
        $proker->delete();

        return redirect()->route('panel.program-kerja.index')->with('success', 'Program kerja berhasil dihapus.');
    }
}
