<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Display the public welcome page.
     */
    public function index(): View
    {
        $anggota = Anggota::orderBy('urutan')->get();

        return view('welcome', compact('anggota'));
    }
}
