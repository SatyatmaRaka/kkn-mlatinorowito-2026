<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    public const STATUS_HADIR = 'hadir';

    public const STATUS_IZIN = 'izin';

    public const STATUS_SAKIT = 'sakit';

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'anggota_id',
        'tanggal',
        'status',
        'keterangan',
        'dicatat_oleh',
        'check_in_at',
        'metode',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'check_in_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function isHadir(): bool
    {
        return $this->status === self::STATUS_HADIR;
    }

    public function isIzin(): bool
    {
        return $this->status === self::STATUS_IZIN;
    }

    public function isSakit(): bool
    {
        return $this->status === self::STATUS_SAKIT;
    }
}
