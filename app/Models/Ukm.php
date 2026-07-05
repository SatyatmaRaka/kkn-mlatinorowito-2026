<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ukm extends Model
{
    use SoftDeletes;

    protected $table = 'ukm';

    protected $fillable = [
        'nama_usaha',
        'jenis_usaha',
        'rata_rata_omzet',
        'jangkauan_pemasaran',
        'keterangan',
        'urutan',
    ];
}
