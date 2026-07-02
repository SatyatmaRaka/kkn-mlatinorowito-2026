<?php

namespace App\Providers;

use App\Services\PengaturanService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['welcome', 'components.navbar', 'components.footer'], function ($view) {
            $view->with('pengaturan', PengaturanService::get());
        });
    }
}
