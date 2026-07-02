<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Services\PengaturanService;
use App\Support\SocialLinks;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function index(): View
    {
        $pengaturan = PengaturanService::get();

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request): RedirectResponse
    {
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
                    if (! SocialLinks::isValidInstagramInput(is_string($value) ? $value : null)) {
                        $fail('Instagram harus berupa username atau URL instagram.com yang valid.');
                    }
                },
            ],
            'periode_kkn' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:500',
            'maps_embed_url' => 'nullable|string|max:2000',
        ]);

        foreach ($validated as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        PengaturanService::forget();

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function updateAbsensi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'absensi_jam_mulai' => 'required|date_format:H:i',
            'absensi_jam_selesai' => 'required|date_format:H:i|after:absensi_jam_mulai',
        ]);

        foreach ($validated as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        PengaturanService::forget();

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan absensi berhasil disimpan.');
    }

    public function updateAkun(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.Auth::id(),
            'current_password' => 'required|string|current_password',
            'password' => ['nullable', Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::user();
        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        $user->save();

        return redirect()->route('admin.pengaturan.index')->with('success', 'Akun berhasil diperbarui.');
    }
}
