<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE map_markers DROP CONSTRAINT IF EXISTS map_markers_type_check');
    }

    public function down(): void
    {

    }
};
