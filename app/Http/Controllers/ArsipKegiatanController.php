<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\View\View;

/**
 * Arsip kegiatan publik — daftar semua dokumentasi kegiatan KKN.
 */
class ArsipKegiatanController extends Controller
{
    public function index(): View
    {
        $kegiatan = Kegiatan::orderBy('tanggal', 'desc')->paginate(12);

        return view('kegiatan.index', compact('kegiatan'));
    }
}
