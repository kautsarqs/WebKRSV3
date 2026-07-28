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
        Schema::table('koleksis', function (Blueprint $table) {
            $table->foreignId('map_marker_id')->nullable()->constrained('map_markers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->dropForeign(['map_marker_id']);
            $table->dropColumn('map_marker_id');
        });
    }
};
