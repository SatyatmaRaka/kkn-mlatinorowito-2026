<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Anggota;
use App\Layanan\LayananPengaturan;
use App\Layanan\LayananTokenAbsensi;
use App\Penunjang\EksporCsv;
use App\Penunjang\FilterPencarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Absensi QR di posko KKN: scan, check-in, rekap, cetak QR, mode tablet.
 */
class AbsensiController extends Controller
{
    /** Form check-in setelah scan QR (validasi token harian). */
    public function checkInForm(Request $request): View|RedirectResponse
    {
        $token = $request->query('token');

        if (! LayananTokenAbsensi::isValid($token)) {
            return view('absensi.invalid-token');
        }

        if (! Auth::check()) {
            session()->put('url.intended', route('absensi.check-in', ['token' => $token]));

            return view('absensi.login-required');
        }

        $user = Auth::user();

        if (! $user->canCheckInAbsensi()) {
            abort(403, 'Akun Anda tidak terhubung ke data anggota untuk absensi.');
        }

        $today = now()->toDateString();
        $sudahAbsen = Absensi::where('user_id', $user->id)->whereDate('tanggal', $today)->exists();
        $windowOpen = LayananPengaturan::absensiWindowOpen();
        $windowLabel = LayananPengaturan::absensiWindowLabel();

        return view('absensi.check-in', compact('user', 'sudahAbsen', 'windowOpen', 'windowLabel', 'token'));
    }

    /** Simpan absensi setelah konfirmasi pengguna. */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['token' => 'required|string']);

        if (! LayananTokenAbsensi::isValid($request->input('token'))) {
            return back()->withErrors(['absensi' => 'Token QR tidak valid. Scan QR resmi di posko KKN.']);
        }

        $user = Auth::user();

        if (! $user || ! $user->canCheckInAbsensi()) {
            abort(403);
        }

        if (! LayananPengaturan::absensiWindowOpen()) {
            return back()->withErrors([
                'absensi' => 'Absensi hanya bisa dilakukan pada jam yang ditentukan ('.LayananPengaturan::absensiWindowLabel().').',
            ]);
        }

        $today = now()->toDateString();

        if (Absensi::where('user_id', $user->id)->whereDate('tanggal', $today)->exists()) {
            return back()->withErrors(['absensi' => 'Anda sudah melakukan absensi hari ini.']);
        }

        Absensi::create([
            'user_id' => $user->id,
            'anggota_id' => $user->anggota_id,
            'tanggal' => $today,
            'status' => Absensi::STATUS_HADIR,
            'check_in_at' => now(),
            'metode' => 'qr',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('absensi.check-in', ['token' => $request->input('token')])
            ->with('success', 'Absensi berhasil dicatat. Selamat berkegiatan!');
    }

    /** Catat izin/sakit manual oleh admin/koordinator. */
    public function catatIzinSakit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'status' => 'required|in:'.Absensi::STATUS_IZIN.','.Absensi::STATUS_SAKIT,
            'keterangan' => 'required|string|max:500',
        ]);

        $anggota = Anggota::with('user')->findOrFail($validated['anggota_id']);

        if (! $anggota->user) {
            return back()->withErrors(['anggota_id' => 'Anggota tidak punya akun login.']);
        }

        if (Absensi::where('user_id', $anggota->user->id)->whereDate('tanggal', $validated['tanggal'])->exists()) {
            return back()->withErrors([
                'absensi' => 'Anggota ini sudah punya catatan absensi pada tanggal tersebut. Hapus catatan lama dulu jika ingin mengubah.',
            ]);
        }

        Absensi::create([
            'user_id' => $anggota->user->id,
            'anggota_id' => $anggota->id,
            'tanggal' => $validated['tanggal'],
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'],
            'dicatat_oleh' => Auth::id(),
            'check_in_at' => null,
            'metode' => 'manual',
        ]);

        $label = $validated['status'] === Absensi::STATUS_IZIN ? 'Izin' : 'Sakit';

        return redirect()
            ->route('panel.absensi.rekap', ['tanggal' => $validated['tanggal']])
            ->with('success', "{$label} berhasil dicatat untuk {$anggota->nama}.");
    }

    /** Hapus catatan izin/sakit manual yang salah input. */
    public function hapusCatatan(Absensi $absensi): RedirectResponse
    {
        if ($absensi->metode !== 'manual') {
            abort(403, 'Catatan hasil check-in QR tidak bisa dihapus dari sini.');
        }

        $tanggal = $absensi->tanggal->toDateString();
        $absensi->delete();

        return redirect()
            ->route('panel.absensi.rekap', ['tanggal' => $tanggal])
            ->with('success', 'Catatan izin/sakit berhasil dihapus.');
    }

    /** Riwayat absensi pribadi atau semua (koordinator). */
    public function riwayat(Request $request): View
    {
        $user = Auth::user();
        $tanggal = $request->query('tanggal');
        $q = FilterPencarian::kataKunci($request->query('q'));

        $absensi = Absensi::with('anggota')
            ->when(! $user->canReviewLogbook(), fn ($query) => $query->where('user_id', $user->id))
            ->when($tanggal, fn ($query) => $query->whereDate('tanggal', $tanggal))
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, [
                'metode',
                'status',
                fn ($sub, $term) => $sub->orWhereHas('anggota', fn ($a) => $a->where('nama', 'like', '%'.$term.'%')),
            ]))
            ->orderByDesc('tanggal')
            ->orderByDesc('check_in_at')
            ->paginate(20)
            ->withQueryString();

        return view('absensi.riwayat', compact('absensi', 'tanggal', 'q'));
    }

    /** Rekap kehadiran harian untuk koordinator/admin. */
    public function rekap(Request $request): View
    {
        $tanggal = $request->query('tanggal', now()->toDateString());
        $q = FilterPencarian::kataKunci($request->query('q'));
        $filterStatus = in_array($request->query('status'), ['hadir', 'izin', 'sakit', 'belum'], true)
            ? $request->query('status')
            : null;

        $anggotaDenganAkun = Anggota::with('user')
            ->whereHas('user', fn ($query) => $query->whereIn('role', ['anggota', 'koordinator']))
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, [
                'nama', 'jabatan', 'jurusan',
            ]))
            ->orderBy('urutan')
            ->get();

        $recordsByAnggota = Absensi::whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('anggota_id');

        $hadir = $this->kelompokAnggota($anggotaDenganAkun, $recordsByAnggota, Absensi::STATUS_HADIR);
        $izin = $this->kelompokAnggota($anggotaDenganAkun, $recordsByAnggota, Absensi::STATUS_IZIN);
        $sakit = $this->kelompokAnggota($anggotaDenganAkun, $recordsByAnggota, Absensi::STATUS_SAKIT);
        $belum = $anggotaDenganAkun->filter(fn (Anggota $a) => ! $recordsByAnggota->has($a->id));

        if ($filterStatus === 'hadir') {
            $izin = collect();
            $sakit = collect();
            $belum = collect();
        } elseif ($filterStatus === 'izin') {
            $hadir = collect();
            $sakit = collect();
            $belum = collect();
        } elseif ($filterStatus === 'sakit') {
            $hadir = collect();
            $izin = collect();
            $belum = collect();
        } elseif ($filterStatus === 'belum') {
            $hadir = collect();
            $izin = collect();
            $sakit = collect();
        }

        $absensiHari = Absensi::with(['anggota', 'pencatat'])
            ->whereDate('tanggal', $tanggal)
            ->when($q, fn ($query) => $query->whereHas('anggota', fn ($a) => $a->where('nama', 'like', '%'.$q.'%')))
            ->orderBy('check_in_at')
            ->get();

        return view('absensi.rekap', compact(
            'tanggal',
            'hadir',
            'izin',
            'sakit',
            'belum',
            'absensiHari',
            'recordsByAnggota',
            'anggotaDenganAkun',
            'q',
            'filterStatus'
        ));
    }

    /** @param  Collection<int, Absensi>  $recordsByAnggota */
    private function kelompokAnggota(Collection $anggota, Collection $recordsByAnggota, string $status): Collection
    {
        return $anggota->filter(function (Anggota $a) use ($recordsByAnggota, $status) {
            $record = $recordsByAnggota->get($a->id);

            return $record && $record->status === $status;
        });
    }

    public function export(Request $request): StreamedResponse
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $query = Absensi::with('anggota')
            ->orderBy('tanggal')
            ->orderBy('check_in_at');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $rows = $query->lazy()->map(fn (Absensi $a) => [
            $a->tanggal->format('Y-m-d'),
            $a->anggota->nama,
            $a->anggota->jabatan,
            $a->status,
            $a->keterangan ?? '',
            $a->check_in_at?->format('H:i:s') ?? '-',
            $a->metode,
            $a->ip_address ?? '',
        ]);

        return EksporCsv::download(
            'rekap-absensi-'.now()->format('Y-m-d').'.csv',
            ['Tanggal', 'Nama', 'Jabatan', 'Status', 'Keterangan', 'Jam Check-in', 'Metode', 'IP'],
            $rows
        );
    }

    /** Halaman cetak QR absensi posko. */
    public function qrPrint(): View
    {
        $tokenModel = LayananTokenAbsensi::getActive();
        $checkInUrl = LayananTokenAbsensi::checkInUrl($tokenModel);
        $windowLabel = LayananPengaturan::absensiWindowLabel();

        return view('panel.absensi.qr', compact('checkInUrl', 'windowLabel', 'tokenModel'));
    }

    /** Buat ulang token QR (admin/koordinator, jika QR bocor). */
    public function regenerateToken(): RedirectResponse
    {
        LayananTokenAbsensi::regenerate();

        return redirect()
            ->route('panel.absensi.qr')
            ->with('success', 'Token QR baru dibuat. QR lama tidak valid — cetak atau tampilkan QR yang baru.');
    }

    /** Mode tablet — tampilan QR fullscreen di posko. */
    public function display(): View
    {
        $tokenModel = LayananTokenAbsensi::getActive();
        $checkInUrl = LayananTokenAbsensi::checkInUrl($tokenModel);
        $windowLabel = LayananPengaturan::absensiWindowLabel();

        return view('absensi.display', compact('checkInUrl', 'windowLabel', 'tokenModel'));
    }
}
