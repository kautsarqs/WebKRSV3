<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered; // <-- TAMBAHKAN BARIS INI

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        // ... dalam method callback()
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // --- LOGIKA: USER SUDAH ADA ---
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }

                Auth::login($user);

                // JANGAN gunakan markEmailAsVerified() di sini jika ingin tetap wajib verifikasi
                // Cek apakah user sudah benar-benar memverifikasi emailnya lewat link
                if ($user->hasVerifiedEmail()) {
                    return ($user->role === 'admin')
                        ? redirect()->route('admin.dashboard')
                        : redirect()->route('dashboard');
                }

                // Jika belum verifikasi, lempar kembali ke halaman pemberitahuan
                return redirect()->route('verification.notice');
            } else {
                // --- LOGIKA: USER BARU ---
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'user',
                    'password' => Hash::make('password_acak_' . str()->random(16)),
                    // email_verified_at tetap NULL
                ]);

                event(new Registered($user)); // Kirim email verifikasi

                Auth::login($user);

                return redirect()->route('verification.notice');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google.');
        }
    }
}
