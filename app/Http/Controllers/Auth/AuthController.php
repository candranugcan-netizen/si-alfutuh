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

        // --- TAMBAHKAN LOGIKA INI ---
        // Penulisan 'Orang Tua' harus sama persis dengan yang ada di database tabel roles
        if ($user->hasRole('Orang Tua')) {
            return redirect()->route('wali.dashboard'); // Arahkan ke dashboard Wali Murid
        }
            return redirect()->intended('dashboard');
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
