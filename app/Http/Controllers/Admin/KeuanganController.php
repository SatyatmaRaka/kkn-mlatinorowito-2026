<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KeuanganController extends Controller
{
    public function index(): View
    {
        $keuangans = Keuangan::with('user')->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();
        
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('admin.keuangan.index', compact('keuangans', 'totalPemasukan', 'totalPengeluaran', 'saldo'));
    }

    public function create(): View
    {
        return view('admin.keuangan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|integer|min:0',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('bukti')) {
            $validated['bukti'] = $request->file('bukti')->store('keuangan', 'public');
        }

        $validated['user_id'] = Auth::id();

        Keuangan::create($validated);

        return redirect()->route('admin.keuangan.index')->with('success', 'Catatan keuangan berhasil ditambahkan.');
    }

    public function edit(Keuangan $keuangan): View
    {
        return view('admin.keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, Keuangan $keuangan): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|integer|min:0',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('bukti')) {
            if ($keuangan->bukti) {
                Storage::disk('public')->delete($keuangan->bukti);
            }
            $validated['bukti'] = $request->file('bukti')->store('keuangan', 'public');
        }

        $keuangan->update($validated);

        return redirect()->route('admin.keuangan.index')->with('success', 'Catatan keuangan berhasil diperbarui.');
    }

    public function destroy(Keuangan $keuangan): RedirectResponse
    {
        if ($keuangan->bukti) {
            Storage::disk('public')->delete($keuangan->bukti);
        }

        $keuangan->delete();

        return redirect()->route('admin.keuangan.index')->with('success', 'Catatan keuangan berhasil dihapus.');
    }
}
