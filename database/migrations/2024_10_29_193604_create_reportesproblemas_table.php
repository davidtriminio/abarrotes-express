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
        Schema::create('reportesproblemas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('seccion', ['Inicio', 'Productos', 'Categorias', 'Marcas', 'Cupones', 'Carrito', 'Perfil', 'Panel Administrativo']);
            $table->longtext('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportesproblemas');
    }
};
