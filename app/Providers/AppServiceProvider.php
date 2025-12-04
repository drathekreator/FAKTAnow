<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Routing\Router;
use App\Http\Middleware\CheckUserRole;

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
     * Bootstrap any application services
     * 
     * Method ini dijalankan setelah semua service provider terdaftar.
     * Digunakan untuk:
     * - Mendaftarkan middleware custom
     * - Force HTTPS di production
     * - View composers (jika diperlukan)
     */
    public function boot(): void
    {
        // STEP 1: Mendaftarkan middleware role custom
        // Mendapatkan instance Router
        $router = $this->app->make(Router::class);
        
        // Mendaftarkan alias 'role' agar bisa digunakan di route
        // Contoh: Route::middleware('role:admin')->group(...)
        $router->aliasMiddleware('role', CheckUserRole::class);
        
        // STEP 2: Force HTTPS di production
        // Zeabur menyediakan HTTPS gratis, jadi kita force semua URL menggunakan HTTPS
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}