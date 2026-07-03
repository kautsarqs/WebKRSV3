<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran_pengunjungs', function (Blueprint $table) {
            $table->string('instansi')->nullable();
            $table->json('rombongan_details')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_pengunjungs', function (Blueprint $table) {
            $table->dropColumn(['instansi', 'rombongan_details']);
        });
    }
};
