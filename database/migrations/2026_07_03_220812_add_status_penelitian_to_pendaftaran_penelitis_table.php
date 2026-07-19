<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('pendaftaran_penelitis', function (Blueprint $table) {
            $table->string('status_penelitian')->default('sedang');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_penelitis', function (Blueprint $table) {
            $table->dropColumn('status_penelitian');
        });
    }
};
