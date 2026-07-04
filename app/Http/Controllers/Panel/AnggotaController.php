<?php

namespace App\Http\Controllers\Panel;

use App\Enums\Jabatan;
use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use App\Penunjang\AkunAnggota;
use App\Penunjang\FilterPencarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** CRUD data anggota KKN & pembuatan akun login. */
class AnggotaController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Anggota::class);

        $q = FilterPencarian::kataKunci($request->query('q'));
        $jabatan = $request->query('jabatan');

        $anggota = Anggota::with('user')
            ->when($jabatan && in_array($jabatan, Jabatan::values(), true), fn ($query) => $query->where('jabatan', $jabatan))
            ->when($q, fn ($query) => FilterPencarian::terapkan($query, $q, [
                'nama', 'nim', 'jurusan', 'jabatan', 'deskripsi',
            ]))
            ->orderBy('urutan')
            ->get();

        return view('panel.anggota.index', compact('anggota', 'q', 'jabatan'));
    }

    public function create(): View
    {
        $this->authorize('create', Anggota::class);

        return view('panel.anggota.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Anggota::class);

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
        $this->authorize('update', $anggota);

        return view('panel.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota): RedirectResponse
    {
        $this->authorize('update', $anggota);

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

        if ($anggota->user && ! $anggota->user->isAdmin()) {
            $roleBaru = AkunAnggota::peranDariJabatan($anggota->jabatan);
            if ($anggota->user->role !== $roleBaru) {
                $anggota->user->update(['role' => $roleBaru]);
            }
        }

        return redirect()->route('panel.anggota.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota): RedirectResponse
    {
        $this->authorize('delete', $anggota);

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
        abort_unless($request->user()?->isAdmin(), 403, 'Hanya admin yang dapat membuat akun login anggota.');

        if ($anggota->user) {
            return back()->withErrors(['username' => 'Anggota ini sudah memiliki akun login.']);
        }

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        User::create([
            'name' => $anggota->nama,
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => AkunAnggota::peranDariJabatan($anggota->jabatan),
            'anggota_id' => $anggota->id,
            'wajib_ganti_password' => true,
        ]);

        return back()->with('success', "Akun login untuk {$anggota->nama} berhasil dibuat.");
    }

    public function resetPassword(Request $request, Anggota $anggota): RedirectResponse
    {
        abort_unless($request->user()?->isAdmin(), 403, 'Hanya admin yang dapat mereset password anggota.');

        $anggota->loadMissing('user');

        if (! $anggota->user) {
            return back()->withErrors(['anggota' => 'Anggota ini belum memiliki akun login.']);
        }

        $passwordBaru = AkunAnggota::passwordAcak();

        $anggota->user->update([
            'password' => $passwordBaru,
            'wajib_ganti_password' => true,
        ]);

        if (config('session.driver') === 'database') {
            DB::table('sessions')
                ->where('user_id', $anggota->user->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }
        // Driver session selain database (file, redis, array, dll.) tidak bisa di-invalidate
        // lewat tabel sessions — password baru tetap berlaku saat login berikutnya.

        return back()
            ->with('password_baru', $passwordBaru)
            ->with('password_baru_untuk', $anggota->nama);
    }
}
