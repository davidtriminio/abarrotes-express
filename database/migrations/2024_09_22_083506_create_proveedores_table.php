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
        Schema::create('proveedores', function (Blueprint $table) {
                    $table->id();
                    $table->string('nombre');
                    $table->string('email')->unique();
                    $table->string('telefono')->nullable();
                    $table->string('direccion')->nullable();
                    $table->string('ciudad')->nullable();
                    $table->enum('estado_proveedor', ['activo', 'inactivo'])
                        ->default('activo');
                    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
