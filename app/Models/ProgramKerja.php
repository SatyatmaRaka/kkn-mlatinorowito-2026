<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $table = 'program_kerja';

    protected $fillable = ['judul', 'tema', 'deskripsi', 'icon', 'pic', 'status', 'urutan'];

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
}
