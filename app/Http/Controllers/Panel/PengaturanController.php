<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Layanan\LayananPengaturan;
use App\Penunjang\SematanPeta;
use App\Penunjang\TautanSosial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Pengaturan website, jam absensi, dan keamanan akun admin.
 */
class PengaturanController extends Controller
{
    public function index(): View
    {
        $pengaturan = LayananPengaturan::get();

        return view('panel.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->canManageWebsiteKonten(), 403);

        $validated = $request->validate([
            'nama_dpl' => 'nullable|string|max:255',
            'nama_kelompok' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'instagram' => [
                'nullable',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! TautanSosial::isValidInstagramInput(is_string($value) ? $value : null)) {
                        $fail('Instagram harus berupa username atau URL instagram.com yang valid.');
                    }
                },
            ],
            'tiktok' => [
                'nullable',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! TautanSosial::isValidTiktokInput(is_string($value) ? $value : null)) {
                        $fail('TikTok harus berupa username atau URL tiktok.com yang valid.');
                    }
                },
            ],
            'periode_kkn' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'nidn_dpl' => 'nullable|string|max:50',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'tanggal_mulai_kkn' => 'nullable|date',
            'tanggal_selesai_kkn' => 'nullable|date|after_or_equal:tanggal_mulai_kkn',
            'maps_embed_url' => [
                'nullable',
                'string',
                'max:2000',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! SematanPeta::isValidEmbedInput(is_string($value) ? $value : null)) {
                        $fail('URL embed harus dari Google Maps (maps.google.com atau google.com/maps).');
                    }
                },
            ],
        ]);

        foreach ($validated as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        LayananPengaturan::forget();

        return redirect()->route('panel.pengaturan.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function updateAbsensi(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->canManageWebsiteKonten(), 403);

        $validated = $request->validate([
            'absensi_jam_mulai' => 'required|date_format:H:i',
            'absensi_jam_selesai' => 'required|date_format:H:i|after:absensi_jam_mulai',
        ]);

        foreach ($validated as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        LayananPengaturan::forget();

        return redirect()->route('panel.pengaturan.index')->with('success', 'Pengaturan absensi berhasil disimpan.');
    }

    public function updateAkun(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'current_password' => 'required|string|current_password',
            'password' => [
                $user->wajib_ganti_password ? 'required' : 'nullable',
                Password::defaults(),
                'confirmed',
            ],
        ]);

        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = $request->password;
            $user->wajib_ganti_password = false;
        }

        $user->save();

        return redirect()->route('panel.pengaturan.index')->with('success', 'Akun berhasil diperbarui.');
    }
}
