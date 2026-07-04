<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Layanan\LayananRingkasan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Endpoint JSON untuk pembaruan live (polling) di panel. */
class DataLiveController extends Controller
{
    public function dasbor(): JsonResponse
    {
        $user = Auth::user();
        $ringkasan = LayananRingkasan::ringkasanPeriode(
            now()->startOfMonth()->toDateString(),
            now()->toDateString()
        );

        $data = [
            'waktu' => now()->toIso8601String(),
            'logbook_menunggu' => $ringkasan['logbook']['menunggu_review'],
            'absensi_hari_ini' => $ringkasan['absensi']['hari_ini'],
        ];

        if ($user->canKelolaSurat()) {
            $data['total_anggota'] = $ringkasan['absensi']['total_anggota_aktif'];
        }

        if ($user->canManageKeuangan()) {
            $data['saldo_bulan_ini'] = $ringkasan['keuangan']['saldo'];
        }

        return response()->json($data);
    }

    public function absensiRekap(Request $request): JsonResponse
    {
        abort_unless(Auth::user()?->canReviewLogbook(), 403);

        $tanggal = $request->query('tanggal', now()->toDateString());

        return response()->json(
            LayananRingkasan::rekapAbsensiHarian($tanggal)
        );
    }

    public function notifikasi(): JsonResponse
    {
        $user = Auth::user();

        $items = $user->unreadNotifications()
            ->take(10)
            ->map(fn ($n) => [
                'id' => $n->id,
                'pesan' => $n->data['pesan'] ?? '',
                'tipe' => $n->data['tipe'] ?? 'umum',
                'url' => $n->data['url'] ?? route('panel.catatan-harian.index'),
                'waktu' => $n->created_at->diffForHumans(),
            ])
            ->values();

        return response()->json([
            'jumlah' => $user->unreadNotifications()->count(),
            'items' => $items,
        ]);
    }

    public function tandaiDibaca(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($request->filled('id')) {
            $user->unreadNotifications()->where('id', $request->input('id'))->first()?->markAsRead();
        } else {
            $user->unreadNotifications->markAsRead();
        }

        return response()->json(['ok' => true]);
    }
}
