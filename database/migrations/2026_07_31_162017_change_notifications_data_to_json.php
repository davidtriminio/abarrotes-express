<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change the 'data' column from text to json in PostgreSQL
        Schema::table('notifications', function (Blueprint $table) {
            // For PostgreSQL, we need to cast the text column to jsonb
            DB::statement('ALTER TABLE notifications ALTER COLUMN data TYPE jsonb USING data::jsonb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Revert back to text
            DB::statement('ALTER TABLE notifications ALTER COLUMN data TYPE text USING data::text');
        });
    }
};

