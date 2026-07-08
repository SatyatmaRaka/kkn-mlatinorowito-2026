<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'tema_kegiatan',
        'tanggal',
        'tempat',
        'latar_belakang',
        'kondisi_mendukung',
        'manfaat_tujuan',
        'sumber_dana_masyarakat',
        'sumber_dana_mahasiswa',
        'sumber_dana_donatur',
        'sumber_dana_donatur_keterangan',
        'waktu_mulai',
        'waktu_selesai',
        'pic_anggota_id',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'sumber_dana_masyarakat' => 'integer',
            'sumber_dana_mahasiswa' => 'integer',
            'sumber_dana_donatur' => 'integer',
        ];
    }

    public function totalAnggaran(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->sumber_dana_masyarakat + $this->sumber_dana_mahasiswa + $this->sumber_dana_donatur,
        );
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
