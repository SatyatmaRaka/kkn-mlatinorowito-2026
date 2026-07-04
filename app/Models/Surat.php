<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Arsip surat masuk & keluar KKN. */
class Surat extends Model
{
    /** @use HasFactory<\Database\Factories\SuratFactory> */
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
        'jenis',
        'kategori_tujuan',
        'nomor_surat',
        'tanggal',
        'asal_tujuan',
        'nomor_rt',
        'nomor_rw',
        'perihal',
        'keterangan',
        'lampiran',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isMasuk(): bool
    {
        return $this->jenis === 'masuk';
    }

    public function labelJenis(): string
    {
        return $this->jenis === 'masuk' ? 'Surat Masuk' : 'Surat Keluar';
    }

    public function teksPenerima(): string
    {
        return \App\Penunjang\PenerimaSurat::fromSurat($this);
    }

    public function labelKategoriTujuan(): ?string
    {
        if (! $this->kategori_tujuan) {
            return null;
        }

        return \App\Enums\KategoriTujuanSurat::from($this->kategori_tujuan)->label();
    }
}
