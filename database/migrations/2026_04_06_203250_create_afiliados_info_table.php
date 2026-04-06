<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('afiliados_info', function (Blueprint $table) {
            $table->id();
            // Relación con la tabla users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Datos del negocio
            $table->string('nombre_comercial');
            $table->string('nit')->nullable(); // Para facturación en Guatemala
            $table->string('telefono_negocio')->nullable();
            $table->text('direccion')->nullable();

            // Configuración de negocio
            $table->decimal('comision_porcentaje', 5, 2)->default(10.00); // Ejemplo: 10.00%
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('afiliados_info');
    }
};
