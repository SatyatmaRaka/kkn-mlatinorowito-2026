<?php

namespace App\Http\Controllers\Panel;

use App\Enums\Jabatan;
use App\Enums\PeranPengguna;
use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** CRUD data anggota KKN & pembuatan akun login. */
class AnggotaController extends Controller
{
    public function index(): View
    {
        $anggota = Anggota::with('user')->orderBy('urutan')->get();

        return view('panel.anggota.index', compact('anggota'));
    }

    public function create(): View
    {
        return view('panel.anggota.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'jurusan' => 'required|string|max:255',
            'jabatan' => ['required', 'string', Rule::in(Jabatan::values())],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        Anggota::create($validated);

        return redirect()->route('panel.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota): View
    {
        return view('panel.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'jurusan' => 'required|string|max:255',
            'jabatan' => ['required', 'string', Rule::in(Jabatan::values())],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('foto')) {
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $validated['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        $anggota->update($validated);

        return redirect()->route('panel.anggota.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota): RedirectResponse
    {
        if ($anggota->logbooks()->exists() || $anggota->absensi()->exists()) {
            return back()->withErrors([
                'anggota' => 'Anggota tidak dapat dihapus karena masih memiliki riwayat logbook atau absensi.',
            ]);
        }

        if ($anggota->foto) {
            Storage::disk('public')->delete($anggota->foto);
        }

        if ($anggota->user) {
            $anggota->user->delete();
        }

        $anggota->delete();

        return redirect()->route('panel.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }

    public function storeAkun(Request $request, Anggota $anggota): RedirectResponse
    {
        if ($anggota->user) {
            return back()->withErrors(['username' => 'Anggota ini sudah memiliki akun login.']);
        }

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'role' => ['required', Rule::in([PeranPengguna::Koordinator->value, PeranPengguna::Anggota->value])],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        User::create([
            'name' => $anggota->nama,
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => PeranPengguna::from($validated['role']),
            'anggota_id' => $anggota->id,
        ]);

        return back()->with('success', "Akun login untuk {$anggota->nama} berhasil dibuat.");
    }
}
