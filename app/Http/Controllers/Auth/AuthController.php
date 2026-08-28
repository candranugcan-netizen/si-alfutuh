<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses data login yang dikirim
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah checkbox 'remember' dicentang
        $remember = $request->has('remember');

        // Parameter kedua $remember mengaktifkan fitur "Ingat Saya"
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 1. Pengalihan untuk Wali Murid / Orang Tua
            if ($user->hasRole('Orang Tua')) {
                return redirect()->route('wali.dashboard');
            }

            // 2. Pengalihan untuk Komite (langsung ke kas komite)
            if ($user->hasRole('Komite')) {
                return redirect()->route('keuangan-komite.index');
            }

            // 3. Pengalihan default untuk Super Admin, Guru, & Role lainnya
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Mencegah CSRF attack saat logout
        return redirect('/login');
    }
}
