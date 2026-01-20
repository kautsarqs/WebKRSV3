<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Kautsar Qaris Septyawan',
            'email' => 'kaustar1777@gmail.com',
            'role' => 'admin',
            'email_verified_at' => now(), // 🔥 PENTING
            'password' => Hash::make('password'), // Password: password
        ]);
    }
}
