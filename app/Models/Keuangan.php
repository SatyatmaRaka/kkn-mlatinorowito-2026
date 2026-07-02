<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jenis',
        'keterangan',
        'nominal',
        'bukti',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
