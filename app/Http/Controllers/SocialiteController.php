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
    public function redirect()
    {
        return Socialite::driver('google')
            ->redirectUrl(route('auth.google.callback'))
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('auth.google.callback'))
                ->user();

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {

                $updateData = [];
                if (!$user->google_id) {
                    $updateData['google_id'] = $googleUser->id;
                }
                if (!$user->avatar) {
                    $updateData['avatar'] = $googleUser->avatar;
                }
                if (!empty($updateData)) {
                    $user->update($updateData);
                }

                Auth::login($user);

                return ($user->role === 'admin')
                    ? redirect()->route('admin.dashboard')
                    : redirect()->route('dashboard');
            } else {

                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'user',
                    'password' => Hash::make('password_acak_' . str()->random(16)),
                    'email_verified_at' => now(),
                ]);

                Auth::login($user);

                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google.');
        }
    }
}
