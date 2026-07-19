<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('map_markers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->enum('type', ['area_koleksi', 'fasilitas_umum', 'kantor_pengelola', 'pos_keamanan']);
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3b82f6');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_markers');
    }
};
