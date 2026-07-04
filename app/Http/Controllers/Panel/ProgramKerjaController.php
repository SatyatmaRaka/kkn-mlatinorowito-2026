<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ProgramKerja;
use App\Penunjang\FilterPencarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** CRUD program kerja kelompok KKN di halaman publik. */
class ProgramKerjaController extends Controller
{
    public function index(Request $request): View
    {
        $q = FilterPencarian::kataKunci($request->query('q'));
        $status = FilterPencarian::kataKunci($request->query('status'));

        $programKerja = ProgramKerja::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, [
                'judul', 'tema', 'deskripsi', 'pic', 'status',
            ]))
            ->orderBy('urutan')
            ->get();

        $statusList = ProgramKerja::query()->distinct()->orderBy('status')->pluck('status');

        return view('panel.program-kerja.index', compact('programKerja', 'q', 'status', 'statusList'));
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
