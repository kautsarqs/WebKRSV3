<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE map_markers ALTER COLUMN type TYPE VARCHAR(255)');
    }

    public function down(): void
    {

    }
};
