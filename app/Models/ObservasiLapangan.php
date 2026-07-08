<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ObservasiLapangan extends Model
{
    use SoftDeletes;

    protected $table = 'observasi_lapangan';

    protected $fillable = [
        'ringkasan_permasalahan',
        'rencana_pemecahan',
        'dibuat_oleh',
    ];

    public const KELEMBAGAAN_WAJIB = [
        'Posyandu',
        'Posyandu Lansia',
        'Koperasi',
        'Karang Taruna',
        'BumDes',
        'Perpustakaan Desa',
        'PKK',
        'UMKM',
        'Bank Sampah',
        'Sistem/aplikasi Administrasi Desa',
        'Potensi Desa Wisata / Ekonomi kreatif',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ObservasiLapanganItem::class)->orderBy('urutan');
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public static function ambilOrBuatDefault(): self
    {
        $existing = static::query()
            ->with(['items' => fn ($q) => $q->orderBy('urutan')])
            ->first();

        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () {
            $observasi = static::create([]);

            foreach (self::KELEMBAGAAN_WAJIB as $i => $nama) {
                $observasi->items()->create([
                    'nama_kelembagaan' => $nama,
                    'status' => 'tidak',
                    'urutan' => $i + 1,
                ]);
            }

            return $observasi->load(['items' => fn ($q) => $q->orderBy('urutan')]);
        });
    }
}
