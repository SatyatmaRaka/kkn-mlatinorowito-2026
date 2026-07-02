<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\View\View;

class KegiatanArchiveController extends Controller
{
    public function index(): View
    {
        $kegiatan = Kegiatan::orderBy('tanggal', 'desc')->paginate(9);

        return view('kegiatan.index', compact('kegiatan'));
    }
}
