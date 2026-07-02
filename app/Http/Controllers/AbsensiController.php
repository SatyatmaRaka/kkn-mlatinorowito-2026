<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Anggota;
use App\Services\AbsensiTokenService;
use App\Services\PengaturanService;
use App\Support\CsvExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AbsensiController extends Controller
{
    public function checkInForm(Request $request): View|RedirectResponse
    {
        $token = $request->query('token');

        if (! AbsensiTokenService::isValid($token)) {
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
        $windowOpen = PengaturanService::absensiWindowOpen();
        $windowLabel = PengaturanService::absensiWindowLabel();

        return view('absensi.check-in', compact('user', 'sudahAbsen', 'windowOpen', 'windowLabel', 'token'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['token' => 'required|string']);

        if (! AbsensiTokenService::isValid($request->input('token'))) {
            return back()->withErrors(['absensi' => 'Token QR tidak valid atau sudah kedaluwarsa. Scan QR hari ini di posko.']);
        }

        $user = Auth::user();

        if (! $user || ! $user->canCheckInAbsensi()) {
            abort(403);
        }

        if (! PengaturanService::absensiWindowOpen()) {
            return back()->withErrors([
                'absensi' => 'Absensi hanya bisa dilakukan pada jam yang ditentukan ('.PengaturanService::absensiWindowLabel().').',
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

        return CsvExporter::download(
            'rekap-absensi-'.now()->format('Y-m-d').'.csv',
            ['Tanggal', 'Nama', 'Jabatan', 'Jam Check-in', 'Metode', 'IP'],
            $rows
        );
    }

    public function qrPrint(): View
    {
        $tokenModel = AbsensiTokenService::getOrCreateForToday();
        $checkInUrl = AbsensiTokenService::checkInUrl($tokenModel);
        $windowLabel = PengaturanService::absensiWindowLabel();
        $tanggalLabel = now()->locale('id')->translatedFormat('d F Y');

        return view('admin.absensi.qr', compact('checkInUrl', 'windowLabel', 'tanggalLabel', 'tokenModel'));
    }

    public function display(): View
    {
        $tokenModel = AbsensiTokenService::getOrCreateForToday();
        $checkInUrl = AbsensiTokenService::checkInUrl($tokenModel);
        $windowLabel = PengaturanService::absensiWindowLabel();

        return view('absensi.display', compact('checkInUrl', 'windowLabel', 'tokenModel'));
    }
}
