<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanPesertaMasyarakat extends Model
{
    protected $table = 'kegiatan_peserta_masyarakat';

    protected $fillable = [
        'kegiatan_pelaksanaan_id',
        'nama',
        'alamat',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(KegiatanPelaksanaan::class, 'kegiatan_pelaksanaan_id');
    }
}
