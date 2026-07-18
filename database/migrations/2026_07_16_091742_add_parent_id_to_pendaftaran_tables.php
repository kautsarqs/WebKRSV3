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
        Schema::table('pendaftaran_pengunjungs', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('pendaftaran_pengunjungs')->onDelete('set null');
        });

        Schema::table('pendaftaran_penelitis', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('pendaftaran_penelitis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_pengunjungs', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::table('pendaftaran_penelitis', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
