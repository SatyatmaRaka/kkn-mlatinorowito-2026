<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        $galeri = Galeri::latest()->get();

        return view('admin.galeri.index', compact('galeri'));
    }

    public function create(): View
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'foto' => 'required|array|min:1',
            'foto.*' => 'image|max:2048',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        foreach ($request->file('foto') as $index => $file) {
            $path = $file->store('galeri', 'public');
            Galeri::create([
                'foto' => $path,
                'keterangan' => $request->keterangan[$index] ?? null,
            ]);
        }

        return redirect()->route('admin.galeri.index')->with('success', 'Foto berhasil diupload.');
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        Storage::disk('public')->delete($galeri->foto);
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto berhasil dihapus.');
    }
}
