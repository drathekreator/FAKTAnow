<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * CheckUserRole Middleware
 * 
 * Middleware ini digunakan untuk memvalidasi role/peran user sebelum mengakses route tertentu.
 * Middleware ini memastikan hanya user dengan role yang sesuai yang bisa mengakses halaman.
 * 
 * Cara penggunaan di route:
 * Route::middleware('role:admin')->group(function () {
 *     // Route yang hanya bisa diakses admin
 * });
 * 
 * Route::middleware('role:editor')->group(function () {
 *     // Route yang hanya bisa diakses editor
 * });
 * 
 * Middleware ini harus didaftarkan di bootstrap/app.php atau Kernel.php
 * dengan alias 'role'
 */
class CheckUserRole
{
    /**
     * Handle incoming request dan validasi role user
     * 
     * Method ini melakukan 3 pengecekan:
     * 1. Apakah user sudah login?
     * 2. Apakah role user sesuai dengan yang diminta?
     * 3. Jika tidak sesuai, redirect dengan pesan error
     * 
     * SUPPORT MULTIPLE ROLES:
     * Middleware ini sekarang mendukung multiple roles dengan pemisah koma
     * Contoh: Route::middleware('role:admin,editor')
     * 
     * @param Request $request Request yang masuk
     * @param Closure $next Closure untuk melanjutkan request ke controller
     * @param string $roles Role yang diizinkan (bisa single atau multiple dengan koma)
     *                      Contoh: 'admin' atau 'admin,editor' atau 'admin,editor,user'
     * @return Response Response yang akan dikembalikan
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // STEP 1: Cek apakah user sudah login
        // Auth::check() return true jika user sudah login, false jika belum
        if (!Auth::check()) {
            // Jika belum login, redirect ke halaman login
            return redirect('/login');
        }

        // STEP 2: Parse multiple roles (jika ada)
        // Pisahkan string roles berdasarkan koma menjadi array
        // Contoh: 'admin,editor' menjadi ['admin', 'editor']
        $allowedRoles = explode(',', $roles);
        
        // Ambil role user yang sedang login
        $userRole = Auth::user()->role;

        // STEP 3: Cek apakah role user ada di dalam daftar role yang diizinkan
        // in_array() mengecek apakah $userRole ada di dalam array $allowedRoles
        // 
        // Contoh:
        // - Route: Route::middleware('role:admin,editor')
        // - Maka $allowedRoles = ['admin', 'editor']
        // - User role = 'editor'
        // - in_array('editor', ['admin', 'editor']) = true ✓
        if (in_array($userRole, $allowedRoles)) {
            // Jika role cocok, izinkan request melanjutkan ke controller
            return $next($request);
        }

        // STEP 4: Jika role tidak cocok (Akses Ditolak)
        // Redirect kembali ke homepage dengan pesan error
        return redirect('/')->with('error', 'Akses Ditolak. Anda tidak memiliki izin untuk melihat halaman ini.');
        
        // Alternatif: Tampilkan halaman error 403 Forbidden
        // Uncomment baris di bawah jika ingin menggunakan error page
        // abort(403, 'Akses Dilarang');
    }
}