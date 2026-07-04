<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keuangan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'diubah_oleh',
        'tanggal',
        'jenis',
        'keterangan',
        'nominal',
        'bukti',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /** Pengguna yang pertama kali mencatat transaksi. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Pengguna yang terakhir mengubah transaksi (audit trail). */
    public function diubahOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
