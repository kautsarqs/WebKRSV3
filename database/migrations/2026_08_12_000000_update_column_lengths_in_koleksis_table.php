<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->string('title', 150)->change();
            $table->string('kerajaan', 50)->nullable()->change();
            $table->string('divisi', 50)->nullable()->change();
            $table->string('kelas', 50)->nullable()->change();
            $table->string('order', 50)->nullable()->change();
            $table->string('famili', 100)->nullable()->change();
            $table->string('genus', 100)->nullable()->change();
            $table->string('spesies', 100)->nullable()->change();
            $table->string('otoritas_1', 100)->nullable()->change();
            $table->string('otoritas_2', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->string('title', 255)->change();
            $table->string('kerajaan', 255)->nullable()->change();
            $table->string('divisi', 255)->nullable()->change();
            $table->string('kelas', 255)->nullable()->change();
            $table->string('order', 255)->nullable()->change();
            $table->string('famili', 255)->nullable()->change();
            $table->string('genus', 255)->nullable()->change();
            $table->string('spesies', 255)->nullable()->change();
            $table->string('otoritas_1', 255)->nullable()->change();
            $table->string('otoritas_2', 255)->nullable()->change();
        });
    }
};
