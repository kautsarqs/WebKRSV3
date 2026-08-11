<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        if ($request->has('email')) {
            $request->merge(['email' => strtolower(trim($request->email))]);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('password.request')->withErrors($validator)->withInput();
        }

        $user = User::whereRaw('LOWER(email) = ?', [strtolower(trim($request->email))])->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Email tersebut tidak terdaftar dalam sistem.'])->withInput();
        }

        // Pass exact case of email in DB to Password broker
        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('password.request')->with('status', 'Tautan atur ulang kata sandi telah dikirim ke email Anda! Silakan cek kotak masuk (atau folder Spam) Anda.');
        }

        return redirect()->route('password.request')->withErrors(['email' => 'Gagal mengirim tautan atur ulang kata sandi. Silakan coba lagi nanti.'])->withInput();
    }

    public function edit(Request $request, $token)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function update(Request $request)
    {
        if ($request->has('email')) {
            $request->merge(['email' => strtolower(trim($request->email))]);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed', 'regex:/[a-zA-Z]/', 'regex:/[0-9]/'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.regex' => 'Password harus berupa kombinasi huruf dan angka.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('password.reset', ['token' => $request->token])->withErrors($validator)->withInput();
        }

        $user = User::whereRaw('LOWER(email) = ?', [strtolower(trim($request->email))])->first();
        if ($user) {
            $request->merge(['email' => $user->email]);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Kata sandi Anda berhasil diperbarui! Anda kini dapat masuk menggunakan kata sandi baru Anda.');
        }

        $errorMsg = 'Gagal memperbarui kata sandi.';
        if ($status === Password::INVALID_TOKEN) {
            $errorMsg = 'Tautan atur ulang kata sandi tidak valid atau telah kedaluwarsa. Silakan minta tautan baru.';
        } elseif ($status === Password::INVALID_USER) {
            $errorMsg = 'Pengguna dengan email tersebut tidak ditemukan.';
        }

        return redirect()->route('password.reset', ['token' => $request->token])->withErrors(['email' => $errorMsg])->withInput();
    }
}
