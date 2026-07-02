<?php

use App\Http\Controllers\Autentikasi\MasukController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [MasukController::class, 'create'])->name('login');
    Route::post('login', [MasukController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [MasukController::class, 'destroy'])->name('logout');
});
