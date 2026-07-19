<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{

    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $request->user()->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'avatar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $user = $request->user();
        $emailChanged = $user->email !== $request->email;

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($emailChanged) {
            $data['email_verified_at'] = null;
        }

        if ($request->hasFile('avatar')) {

            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('verification.notice')->with('message', 'Email Anda berhasil diperbarui! Silakan verifikasi email baru Anda melalui tautan yang dikirimkan.');
        }

        return back()->with('status', 'profile-updated');
    }

    public function show(Request $request)
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
