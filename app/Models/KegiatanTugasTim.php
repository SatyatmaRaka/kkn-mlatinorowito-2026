<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanTugasTim extends Model
{
    protected $table = 'kegiatan_tugas_tim';

    protected $fillable = [
        'kegiatan_pelaksanaan_id',
        'anggota_id',
        'tugas',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(KegiatanPelaksanaan::class, 'kegiatan_pelaksanaan_id');
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }
}
