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
           Schema::create('promociones', function (Blueprint $table) {
                       $table->id();
                       $table->string('nombre')->unique();
                       $table->text('descripcion')->nullable();
                       $table->decimal('descuento', 5, 2);
                       $table->dateTime('fecha_inicio');
                       $table->dateTime('fecha_fin');
                       $table->boolean('activa')->default(true);
                       $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
