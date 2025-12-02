<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Wajib di-import untuk cek otentikasi

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Peran yang diizinkan (e.g., 'admin', 'editor'). Parameter ini otomatis diisi dari route.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika belum login, arahkan ke halaman login
            return redirect('/login');
        }

        // 2. Cek apakah peran pengguna sesuai dengan yang diminta di route
        // Auth::user() mengakses Model User yang sedang login
        // Misalnya, jika route menggunakan middleware('role:admin'), maka $role adalah 'admin'
        if (Auth::user()->role === $role) {
            // Jika peran cocok, izinkan permintaan untuk melanjutkan ke Controller
            return $next($request);
        }

        // 3. Jika peran tidak cocok (Akses Ditolak)
        // Arahkan kembali ke halaman utama dengan pesan error
        return redirect('/')->with('error', 'Akses Ditolak. Anda tidak memiliki izin untuk melihat halaman ini.');
        
        // Alternatif: Tampilkan halaman error 403 Forbidden
        // abort(403, 'Akses Dilarang');
    }
}