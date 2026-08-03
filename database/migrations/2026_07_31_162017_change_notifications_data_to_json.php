<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Helpers\DBDriver;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change the 'data' column from text to json/jsonb depending on driver
        Schema::table('notifications', function (Blueprint $table) {
            DBDriver::executeForMysqlAndPgsql(
                // MySQL: change column type to JSON
                "ALTER TABLE notifications MODIFY COLUMN data JSON",
                // PostgreSQL: change column type to jsonb using cast
                "ALTER TABLE notifications ALTER COLUMN data TYPE jsonb USING data::jsonb"
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            DBDriver::executeForMysqlAndPgsql(
                // MySQL: revert to TEXT
                "ALTER TABLE notifications MODIFY COLUMN data TEXT",
                // PostgreSQL: revert to TEXT using cast
                "ALTER TABLE notifications ALTER COLUMN data TYPE text USING data::text"
            );
        });
    }
};

