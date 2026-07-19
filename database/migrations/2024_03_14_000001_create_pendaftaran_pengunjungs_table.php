<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pendaftaran_pengunjungs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('nama_lengkap');
            $table->string('no_identitas');
            $table->string('nomor_hp');
            $table->date('tanggal_kunjungan');
            $table->integer('jumlah_rombongan')->default(1);
            $table->text('keperluan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pengunjungs');
    }
};