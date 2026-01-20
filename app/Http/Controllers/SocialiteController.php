<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;

class SocialiteController extends Controller
{
    // 1. Redirect ke Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Callback dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Cari user (Apakah email admin sudah ada di DB?)
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Jika user sudah ada (misal: Admin yang Anda seed)
                // Kita update google_id-nya agar terhubung
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
            } else {
                // Jika benar-benar user baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'user', // Default user biasa
                    'password' => Hash::make('password_acak_' . str()->random(16)),
                    'email_verified_at' => now(),
                ]);
            }

            // 2. Login User
            Auth::login($user);

            // 3. CEK ROLE (INI BAGIAN PENTINGNYA)
            // Jika role admin, lempar ke Admin Dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Jika user biasa, lempar ke User Dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google.');
        }
    }
}
