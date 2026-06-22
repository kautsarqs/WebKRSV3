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
        Schema::table('map_markers', function (Blueprint $table) {
            $table->string('geometry_type')->default('point'); // point, polyline, polygon
            $table->text('geojson')->nullable(); // JSON coordinates array or GeoJSON string
            
            // Mengubah kolom latitude dan longitude menjadi nullable karena polyline/polygon tidak butuh single lat/lng
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_markers', function (Blueprint $table) {
            $table->dropColumn(['geometry_type', 'geojson']);
            $table->decimal('latitude', 10, 8)->nullable(false)->change();
            $table->decimal('longitude', 11, 8)->nullable(false)->change();
        });
    }
};
