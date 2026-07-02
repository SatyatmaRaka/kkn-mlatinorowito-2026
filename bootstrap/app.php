<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\PastikanPeran::class,
            'can.manage.cms' => \App\Http\Middleware\IzinKelolaCms::class,
            'can.manage.keuangan' => \App\Http\Middleware\IzinKelolaKeuangan::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        // Rotasi token QR absensi setiap hari (wajib diaktifkan via cron di shared hosting)
        $schedule->command('absensi:rotate-token')->dailyAt('00:05');
    })->create();
