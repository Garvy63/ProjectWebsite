<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan form login (jika diperlukan)
     */
    public function showLoginForm()
    {
        return view('login.login');
    }

    /**
     * Menangani proses login user
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tangkap status "Keep me logged in"
        $remember = $request->has('remember');

        // 2. Coba Otentikasi
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek role: Jika admin, arahkan ke /dashboard
            if ($user->role === 'admin') {
            return redirect()->route('admin');
            }

            // Jika role BUKAN admin, arahkan ke / (halaman utama)
            return redirect()->intended('/');
        }

        // 3. Login Gagal, kembalikan ke form dengan pesan error
        return back()->withErrors([
            'email' => 'Kombinasi email dan password tidak cocok.',
        ])->onlyInput('email');
    }

    /**
     * Menangani proses logout user
     */
    public function logout(Request $request)
    {
        // 1. Log out pengguna
        Auth::logout();

        // 2. Invalidate (hapus) sesi pengguna saat ini
        $request->session()->invalidate();

        // 3. Regenerate (buat ulang) token CSRF untuk keamanan
        $request->session()->regenerateToken();

        // 4. Arahkan pengguna kembali ke halaman utama atau halaman login
        return redirect('/');
    }
}
