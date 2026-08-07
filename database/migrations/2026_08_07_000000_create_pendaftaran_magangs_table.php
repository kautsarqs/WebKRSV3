<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_magangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('no_identitas')->default('0000000000000000');
            $table->string('nomor_hp');
            $table->string('institusi');
            $table->string('program_studi')->nullable();
            $table->string('jenjang');
            $table->string('judul_magang', 500);
            $table->string('bidang_magang', 500);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_anggota')->default(1);
            $table->text('tujuan_magang');
            $table->text('surat_pengantar');
            $table->string('status')->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->string('status_magang')->default('sedang');
            $table->foreignId('parent_id')->nullable()->constrained('pendaftaran_magangs')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_magangs');
    }
};
