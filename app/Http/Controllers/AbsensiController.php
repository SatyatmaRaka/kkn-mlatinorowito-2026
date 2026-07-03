<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Anggota;
use App\Layanan\LayananPengaturan;
use App\Layanan\LayananTokenAbsensi;
use App\Penunjang\EksporCsv;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'check_in_at' => now(),
            'metode' => 'qr',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('absensi.check-in', ['token' => $request->input('token')])
            ->with('success', 'Absensi berhasil dicatat. Selamat berkegiatan!');
    }

    /** Riwayat absensi pribadi atau semua (koordinator). */
    public function riwayat(Request $request): View
    {
        $user = Auth::user();
        $tanggal = $request->query('tanggal');

        $absensi = Absensi::with('anggota')
            ->when(! $user->canReviewLogbook(), fn ($q) => $q->where('user_id', $user->id))
            ->when($tanggal, fn ($q) => $q->whereDate('tanggal', $tanggal))
            ->orderByDesc('tanggal')
            ->orderByDesc('check_in_at')
            ->paginate(20)
            ->withQueryString();

        return view('absensi.riwayat', compact('absensi', 'tanggal'));
    }

    /** Rekap kehadiran harian untuk koordinator/admin. */
    public function rekap(): View
    {
        $tanggal = request()->query('tanggal', now()->toDateString());

        $anggotaDenganAkun = Anggota::with('user')
            ->whereHas('user', fn ($q) => $q->whereIn('role', ['anggota', 'koordinator']))
            ->orderBy('urutan')
            ->get();

        $hadirIds = Absensi::whereDate('tanggal', $tanggal)->pluck('anggota_id');

        $hadir = $anggotaDenganAkun->filter(fn ($a) => $hadirIds->contains($a->id));
        $belum = $anggotaDenganAkun->filter(fn ($a) => ! $hadirIds->contains($a->id));

        $absensiHari = Absensi::with('anggota')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('check_in_at')
            ->get();

        return view('absensi.rekap', compact('tanggal', 'hadir', 'belum', 'absensiHari', 'anggotaDenganAkun'));
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
            $a->check_in_at->format('H:i:s'),
            $a->metode,
            $a->ip_address ?? '',
        ]);

        return EksporCsv::download(
            'rekap-absensi-'.now()->format('Y-m-d').'.csv',
            ['Tanggal', 'Nama', 'Jabatan', 'Jam Check-in', 'Metode', 'IP'],
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
