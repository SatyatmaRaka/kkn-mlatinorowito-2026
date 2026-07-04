<?php

namespace App\Http\Controllers\Panel;

use App\Enums\PeranPengguna;
use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\User;
use App\Notifications\NotifikasiLogbookDikirim;
use App\Notifications\NotifikasiLogbookDireview;
use App\Penunjang\EksporCsv;
use App\Penunjang\FilterPencarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Catatan harian KKN (logbook): tulis, kirim review, setujui/tolak, ekspor CSV.
 * Alur: draft → submitted → approved/rejected
 */
class CatatanHarianController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $q = FilterPencarian::kataKunci($request->query('q'));
        $status = in_array($request->query('status'), ['draft', 'submitted', 'approved', 'rejected'], true)
            ? $request->query('status')
            : null;

        $logbooks = Logbook::with(['anggota', 'user'])
            ->when(! $user->canReviewLogbook(), fn ($query) => $query->where('user_id', $user->id))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, [
                'judul',
                'deskripsi',
                'lokasi',
                fn ($sub, $term) => $sub->orWhereHas('anggota', fn ($a) => $a->where('nama', 'like', '%'.$term.'%')),
            ]))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $notifikasiReview = $user->unreadNotifications;

        return view('panel.catatan-harian.index', compact('logbooks', 'notifikasiReview', 'q', 'status'));
    }

    public function create(): View
    {
        $this->authorize('create', Logbook::class);

        return view('panel.catatan-harian.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Logbook::class);

        $user = Auth::user();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:5000',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'lokasi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'submit' => 'nullable|boolean',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('logbook', 'public');
        }

        $validated['user_id'] = $user->id;
        $validated['anggota_id'] = $user->anggota_id;
        $validated['status'] = $request->boolean('submit') ? Logbook::STATUS_SUBMITTED : Logbook::STATUS_DRAFT;
        unset($validated['submit']);

        $logbook = Logbook::create($validated);

        if ($logbook->status === Logbook::STATUS_SUBMITTED) {
            $this->beritahuReviewerLogbookBaru($logbook);
        }

        return redirect()->route('panel.catatan-harian.index')->with('success', 'Logbook berhasil disimpan.');
    }

    public function edit(Logbook $logbook): View
    {
        $this->authorize('update', $logbook);

        return view('panel.catatan-harian.edit', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook): RedirectResponse
    {
        $this->authorize('update', $logbook);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:5000',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'lokasi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'submit' => 'nullable|boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($logbook->foto) {
                Storage::disk('public')->delete($logbook->foto);
            }
            $validated['foto'] = $request->file('foto')->store('logbook', 'public');
        }

        if ($request->boolean('submit') && in_array($logbook->status, [Logbook::STATUS_DRAFT, Logbook::STATUS_REJECTED], true)) {
            $validated['status'] = Logbook::STATUS_SUBMITTED;
            $validated['catatan_reviewer'] = null;
            $validated['reviewed_by'] = null;
            $validated['reviewed_at'] = null;
        }

        unset($validated['submit']);

        $wasSubmitted = ($validated['status'] ?? $logbook->status) === Logbook::STATUS_SUBMITTED
            && $logbook->status !== Logbook::STATUS_SUBMITTED;

        $logbook->update($validated);

        if ($wasSubmitted) {
            $this->beritahuReviewerLogbookBaru($logbook->fresh());
        }

        return redirect()->route('panel.catatan-harian.index')->with('success', 'Logbook berhasil diperbarui.');
    }

    public function destroy(Logbook $logbook): RedirectResponse
    {
        $this->authorize('delete', $logbook);

        $logbook->delete();

        return redirect()->route('panel.catatan-harian.index')->with('success', 'Logbook berhasil dihapus.');
    }

    /** Review oleh koordinator/admin — hanya status submitted. */
    public function review(Request $request, Logbook $logbook): RedirectResponse
    {
        if (! $logbook->isReviewable()) {
            return back()->withErrors(['status' => 'Hanya logbook berstatus "menunggu review" yang bisa disetujui atau ditolak.']);
        }

        $this->authorize('review', $logbook);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_reviewer' => 'nullable|string|max:1000',
        ]);

        $logbook->update([
            'status' => $validated['status'],
            'catatan_reviewer' => $validated['catatan_reviewer'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $statusLabel = $validated['status'] === 'approved' ? 'disetujui' : 'ditolak';

        if ($logbook->user) {
            $logbook->user->notify(new NotifikasiLogbookDireview($logbook->fresh(), $statusLabel));
        }

        return back()->with('success', 'Status logbook berhasil diperbarui.');
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('export', Logbook::class);

        $request->validate([
            'status' => 'nullable|in:draft,submitted,approved,rejected',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $query = Logbook::with('anggota')->orderByDesc('tanggal');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $rows = $query->lazy()->map(fn (Logbook $l) => [
            $l->tanggal->format('Y-m-d'),
            $l->anggota->nama,
            $l->judul,
            $l->lokasi ?? '',
            $l->jam_mulai ?? '',
            $l->jam_selesai ?? '',
            $l->status,
            $l->catatan_reviewer ?? '',
        ]);

        return EksporCsv::download(
            'export-logbook-'.now()->format('Y-m-d').'.csv',
            ['Tanggal', 'Nama', 'Judul', 'Lokasi', 'Jam Mulai', 'Jam Selesai', 'Status', 'Catatan Reviewer'],
            $rows
        );
    }

    /** Kirim notifikasi database ke admin & koordinator. */
    private function beritahuReviewerLogbookBaru(Logbook $logbook): void
    {
        $logbook->loadMissing('anggota');

        User::query()
            ->whereIn('role', [PeranPengguna::Admin, PeranPengguna::Koordinator])
            ->each(fn ($user) => $user->notify(new NotifikasiLogbookDikirim($logbook)));
    }
}
