<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->decimal('sub_total', 10,2)->default(0);
            $table->decimal('total_final', 10,2);
            $table->string('metodo_pago');
                $table->enum('estado_pago', ['pagado', 'procesando', 'error'])->nullable();
            $table->enum('estado_entrega', ['nuevo', 'procesado', 'enviado', 'entregado', 'cancelado']);
            $table->dateTime('fecha_entrega')->nullable();
            $table->string('costos_envio')->default(0);
            $table->longText('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
