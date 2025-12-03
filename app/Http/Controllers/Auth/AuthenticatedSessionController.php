<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request
     * 
     * Method ini memproses login dan mengarahkan user ke halaman yang sesuai
     * berdasarkan role mereka:
     * - Admin -> Dashboard Admin
     * - Editor -> Dashboard Editor
     * - Member/User -> Homepage (daftar berita)
     * 
     * @param LoginRequest $request Request login yang sudah divalidasi
     * @return RedirectResponse Redirect ke halaman sesuai role
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi user (cek kredensial)
        $request->authenticate();

        // Regenerate session untuk keamanan (mencegah session fixation)
        $request->session()->regenerate();

        // Ambil data user yang baru login
        $user = Auth::user();

        // Redirect berdasarkan role user
        if ($user->role === 'admin') {
            // Admin -> Dashboard Admin (manajemen user & artikel)
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'editor') {
            // Editor -> Dashboard Editor (manajemen artikel sendiri)
            return redirect()->intended(route('editor.dashboard'));
        } else {
            // Member/User -> Homepage (daftar berita)
            // Diubah dari route('dashboard') ke route('home')
            return redirect()->intended(route('home'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}