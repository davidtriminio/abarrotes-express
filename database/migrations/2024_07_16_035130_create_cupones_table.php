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
        Schema::create('cupones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->enum('tipo_descuento', ['porcentaje', 'dinero'])->default('porcentaje');
            $table->decimal('descuento_porcentaje', 5, 2)->nullable();
            $table->decimal('descuento_dinero', 7, 2)->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_expiracion');
            $table->boolean('estado')->default(true);
            $table->decimal('compra_minima', 10, 2)->nullable();
            $table->integer('compra_cantidad')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('restrict');
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('restrict');
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupones');
    }
};
