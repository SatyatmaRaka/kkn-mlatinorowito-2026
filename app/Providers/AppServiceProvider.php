<?php

namespace App\Providers;

use App\Layanan\LayananPengaturan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Kebijakan password minimal 12 karakter (huruf + angka)
        Password::defaults(fn () => Password::min(12)->letters()->numbers());

        // Bagikan pengaturan situs ke halaman publik
        View::composer(['beranda', 'components.navbar', 'components.footer'], function ($view) {
            $view->with('pengaturan', LayananPengaturan::get());
        });
    }
}
