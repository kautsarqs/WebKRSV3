<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered; // Penting untuk trigger email otomatis

class SocialiteController extends Controller
{
    /**
     * 1. Redirect ke Google
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * 2. Callback dari Google
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            // ... dalam method callback()
            if ($user) {
                // Jika user sudah ada tapi belum punya google_id
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }

                // TAMBAHAN: Jika login lewat Google, otomatis verifikasi jika belum
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }
            } else {
                // Jika user baru, langsung set email_verified_at
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'user',
                    'password' => Hash::make('password_acak_' . str()->random(16)),
                    'email_verified_at' => now(), // LANGSUNG VERIFIKASI
                ]);
            }
            // ...

            // 2. Login User
            Auth::login($user);

            // 3. PROTEKSI VERIFIKASI EMAIL
            // Jika user belum verifikasi (baik baru atau user lama yang belum verif)
            if (!$user->hasVerifiedEmail()) {
                // Opsional: Jika ingin setiap login ulang otomatis kirim email lagi, 
                // buka komentar baris di bawah ini:
                // $user->sendEmailVerificationNotification();

                return redirect()->route('verification.notice');
            }

            // 4. PENGALIHAN BERDASARKAN ROLE
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google.');
        }
    }
}
