<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran_pengunjungs', function (Blueprint $table) {
            $table->id();
            // Menyimpan user_id jika user sudah login (agar muncul di dashboard user nanti)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->string('nama_lengkap');
            $table->string('no_identitas'); // KTP/SIM/NIK
            $table->string('nomor_hp');
            $table->date('tanggal_kunjungan');
            $table->integer('jumlah_rombongan')->default(1);
            $table->text('keperluan')->nullable(); // Wisata/Edukasi/Lainnya
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pengunjungs');
    }
};