<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kegiatan;
use App\Models\ProgramKerja;
use Illuminate\View\View;

class DetailController extends Controller
{
    public function anggota(Anggota $anggota): View
    {
        return view('detail.anggota', compact('anggota'));
    }

    public function proker(ProgramKerja $programKerja): View
    {
        return view('detail.proker', compact('programKerja'));
    }

    public function kegiatan(Kegiatan $kegiatan): View
    {
        return view('detail.kegiatan', compact('kegiatan'));
    }
}
