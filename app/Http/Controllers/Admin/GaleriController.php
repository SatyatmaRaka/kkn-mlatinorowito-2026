<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

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
            'foto.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        $storedPaths = [];

        try {
            DB::transaction(function () use ($request, &$storedPaths) {
                foreach ($request->file('foto') as $index => $file) {
                    $path = $file->store('galeri', 'public');
                    $storedPaths[] = $path;

                    Galeri::create([
                        'foto' => $path,
                        'keterangan' => $request->keterangan[$index] ?? null,
                    ]);
                }
            });
        } catch (Throwable $e) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
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
