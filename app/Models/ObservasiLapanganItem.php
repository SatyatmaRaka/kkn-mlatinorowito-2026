<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservasiLapanganItem extends Model
{
    protected $table = 'observasi_lapangan_item';

    protected $fillable = [
        'observasi_lapangan_id',
        'nama_kelembagaan',
        'status',
        'permasalahan',
        'rencana_pemecahan_masalah',
        'urutan',
    ];

    public function observasiLapangan(): BelongsTo
    {
        return $this->belongsTo(ObservasiLapangan::class, 'observasi_lapangan_id');
    }
}
