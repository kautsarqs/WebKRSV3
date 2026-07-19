<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->string('kerajaan')->nullable();
            $table->string('divisi')->nullable();
            $table->string('kelas')->nullable();
            $table->string('order')->nullable();
            $table->string('famili')->nullable();
            $table->string('genus')->nullable();
            $table->string('spesies')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->dropColumn(['kerajaan', 'divisi', 'kelas', 'order', 'famili', 'genus', 'spesies']);
        });
    }
};
