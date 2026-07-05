<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanPelaksanaan extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan_pelaksanaan';

    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'tempat',
        'waktu_mulai',
        'waktu_selesai',
        'pic_anggota_id',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'pic_anggota_id');
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function pesertaMasyarakat(): HasMany
    {
        return $this->hasMany(KegiatanPesertaMasyarakat::class);
    }

    public function tugasTim(): HasMany
    {
        return $this->hasMany(KegiatanTugasTim::class);
    }
}
