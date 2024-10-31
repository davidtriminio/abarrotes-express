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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('recovery_key', 30)->unique();
            $table->timestamp('recovery_key_created_at')->nullable();
            $table->string('telefono', 8)->unique()->nullable();
            $table->string('password_reset_token')->nullable();
            $table->timestamp('token_expires_at')->nullable(); // Para manejar la expiración del token
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
