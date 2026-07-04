<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Penunjang\EksporCsv;
use App\Penunjang\FilterPencarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Pencatatan pemasukan & pengeluaran dana KKN. */
class KeuanganController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Keuangan::class);

        $q = FilterPencarian::kataKunci($request->query('q'));
        $jenis = in_array($request->query('jenis'), ['pemasukan', 'pengeluaran'], true) ? $request->query('jenis') : null;
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $filter = function () use ($q, $jenis, $tanggalMulai, $tanggalSelesai) {
            return Keuangan::query()
                ->when($jenis, fn ($query) => $query->where('jenis', $jenis))
                ->when($tanggalMulai, fn ($query) => $query->whereDate('tanggal', '>=', $tanggalMulai))
                ->when($tanggalSelesai, fn ($query) => $query->whereDate('tanggal', '<=', $tanggalSelesai))
                ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, ['keterangan']));
        };

        $keuangans = $filter()
            ->with(['user', 'diubahOleh'])
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $totalPemasukan = $filter()->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $filter()->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('panel.keuangan.index', compact('keuangans', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'q', 'jenis', 'tanggalMulai', 'tanggalSelesai'));
    }

    public function create(): View
    {
        $this->authorize('create', Keuangan::class);

        return view('panel.keuangan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Keuangan::class);

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

        return redirect()->route('panel.keuangan.index')->with('success', 'Catatan keuangan berhasil ditambahkan.');
    }

    public function edit(Keuangan $keuangan): View
    {
        $this->authorize('update', $keuangan);

        return view('panel.keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, Keuangan $keuangan): RedirectResponse
    {
        $this->authorize('update', $keuangan);

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

        $validated['diubah_oleh'] = Auth::id();

        $keuangan->update($validated);

        return redirect()->route('panel.keuangan.index')->with('success', 'Catatan keuangan berhasil diperbarui.');
    }

    public function destroy(Keuangan $keuangan): RedirectResponse
    {
        $this->authorize('delete', $keuangan);

        if ($keuangan->bukti) {
            Storage::disk('public')->delete($keuangan->bukti);
        }

        $keuangan->delete();

        return redirect()->route('panel.keuangan.index')->with('success', 'Catatan keuangan berhasil dihapus.');
    }

    /** Ekspor riwayat transaksi ke CSV. */
    public function export(Request $request): StreamedResponse
    {
        $this->authorize('export', Keuangan::class);

        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jenis' => 'nullable|in:pemasukan,pengeluaran',
        ]);

        $query = Keuangan::with(['user', 'diubahOleh'])->orderBy('tanggal')->orderBy('created_at');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $rows = $query->lazy()->map(fn (Keuangan $k) => [
            $k->tanggal->format('Y-m-d'),
            $k->jenis,
            $k->keterangan,
            (string) $k->nominal,
            $k->user->name ?? '',
            $k->diubahOleh->name ?? '',
            $k->updated_at?->format('Y-m-d H:i') ?? '',
        ]);

        return EksporCsv::download(
            'export-keuangan-'.now()->format('Y-m-d').'.csv',
            ['Tanggal', 'Jenis', 'Keterangan', 'Nominal', 'Pencatat', 'Terakhir Diubah Oleh', 'Diubah Pada'],
            $rows
        );
    }
}
