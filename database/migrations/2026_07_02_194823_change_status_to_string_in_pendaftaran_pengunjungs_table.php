<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {

        try {
            DB::statement('ALTER TABLE pendaftaran_pengunjungs DROP CONSTRAINT IF EXISTS pendaftaran_pengunjungs_status_check');
        } catch (\Exception $e) {

        }

        Schema::table('pendaftaran_pengunjungs', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        try {
            DB::table('pendaftaran_pengunjungs')->where('status', 'approved')->update(['status' => 'disetujui']);
            DB::table('pendaftaran_pengunjungs')->where('status', 'rejected')->update(['status' => 'ditolak']);
        } catch (\Exception $e) {

        }
    }

    public function down(): void
    {
        Schema::table('pendaftaran_pengunjungs', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
};
