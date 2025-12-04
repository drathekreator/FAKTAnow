<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\Router; // <-- Wajib di-import
use App\Http\Middleware\CheckUserRole; // <-- Middleware kustom Anda
use App\Http\Models\Category;

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
        // Mendapatkan instance Router
        $router = $this->app->make(Router::class);

        // =======================================================
        // PENDAFTARAN MIDDLEWARE ROLE (SOLUSI DARURAT)
        // =======================================================
        
        // Mendaftarkan alias 'role' agar bisa digunakan di route: middleware('role:admin')
        $router->aliasMiddleware('role', CheckUserRole::class);
        
        // ====================================================
        // 
        // View::composer('*', function($view){
        //     $view->with('categories', Categories::all());
        // });
        // Ignore HTTPS
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

    }
}