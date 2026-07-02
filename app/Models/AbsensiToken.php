<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiToken extends Model
{
    protected $fillable = [
        'tanggal',
        'token',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }
}
