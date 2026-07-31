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
        Schema::create('quejas_sugerencias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('tipo', ['Queja', 'Sugerencia']);
            $table->longtext('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queja_sugerencias');
    }
};
