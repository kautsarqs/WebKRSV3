<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique();
            $table->string('google_token')->nullable();
            $table->string('avatar')->nullable();
            // Ubah password jadi nullable (perlu instal dbal jika error, tapi di L11/12 biasanya aman)
            // Jika gagal mengubah column, hapus baris change() dan biarkan password required tapi kita isi random saat register google
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'google_token', 'avatar']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
