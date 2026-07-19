<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->string('otoritas_1')->nullable();
            $table->string('otoritas_2')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->dropColumn(['otoritas_1', 'otoritas_2']);
        });
    }
};
