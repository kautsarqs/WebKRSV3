<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // Menampilkan Form Login
    public function index()
    {
        return view('auth.login');
    }

    // Memproses Login
    public function store(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Mencegah Session Fixation

            // 3. Cek Role & Redirect
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        // 4. Jika Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Tampilkan Form Register
    public function register()
    {
        return view('auth.register');
    }

    public function storeRegister(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => 'user',
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
    ]);

    // Login user terlebih dahulu
    \Illuminate\Support\Facades\Auth::login($user);

    // Kirim email secara manual langsung ke objek $user
    $user->sendEmailVerificationNotification();

    return redirect()->route('verification.notice');
}

    // Proses Logout
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}