<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request
     * 
     * Method ini memproses registrasi user baru:
     * 1. Validasi input (name, email, password)
     * 2. Buat user baru di database
     * 3. Trigger event Registered (untuk email verification jika diaktifkan)
     * 4. Login otomatis user yang baru register
     * 5. Redirect ke homepage (daftar berita)
     * 
     * User baru akan memiliki role default 'member' (diatur di migration atau model)
     * 
     * @param Request $request Request registrasi
     * @return RedirectResponse Redirect ke homepage
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // STEP 1: Validasi input registrasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // STEP 2: Buat user baru di database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Role default 'member' akan diatur di migration atau model
        ]);

        // STEP 3: Trigger event Registered (untuk email verification)
        event(new Registered($user));

        // STEP 4: Login otomatis user yang baru register
        Auth::login($user);

        // STEP 5: Redirect ke homepage (bukan dashboard kosong)
        // User baru langsung melihat daftar berita
        return redirect(route('home', absolute: false));
    }
}
