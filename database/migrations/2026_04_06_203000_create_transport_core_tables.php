<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('lugares')) {
            Schema::create('lugares', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();

            $table->index(['nombre', 'ciudad']);
            });
        }

        if (! Schema::hasTable('vehiculos')) {
            Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80);
            $table->unsignedTinyInteger('min_pasajeros')->default(1);
            $table->unsignedTinyInteger('max_pasajeros');
            $table->string('icono')->default('fa-car');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['activo', 'max_pasajeros']);
            });
        }

        if (! Schema::hasTable('conductores')) {
            Schema::create('conductores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono', 30)->nullable();
            $table->string('vehiculo_modelo')->nullable();
            $table->string('placa')->unique();
            $table->enum('estado', ['activo', 'inactivo', 'en_viaje'])->default('activo')->index();
            $table->timestamps();
            });
        }

        if (! Schema::hasTable('rutas')) {
            Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origen_id')->constrained('lugares')->restrictOnDelete();
            $table->foreignId('destino_id')->constrained('lugares')->restrictOnDelete();
            $table->decimal('kilometraje', 8, 2)->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->unique(['origen_id', 'destino_id']);
            $table->index(['activa', 'origen_id', 'destino_id']);
            });
        }

        if (! Schema::hasTable('ruta_vehiculo')) {
            Schema::create('ruta_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained('rutas')->cascadeOnDelete();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
            $table->decimal('precio_tarifa', 10, 2);
            $table->timestamps();

            $table->unique(['ruta_id', 'vehiculo_id']);
            });
        }

        if (! Schema::hasTable('reservaciones')) {
            Schema::create('reservaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_reserva', 40)->unique();
            $table->foreignId('ruta_id')->constrained('rutas')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('socio_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_viaje');
            $table->time('hora_viaje');
            $table->unsignedTinyInteger('pasajeros');
            $table->string('tipo_vehiculo', 80);
            $table->string('nombre_cliente', 150);
            $table->string('correo_cliente', 150);
            $table->string('telefono_cliente', 40);
            $table->text('notas_adicionales')->nullable();
            $table->decimal('precio_total', 10, 2);
            $table->decimal('comision_socio', 10, 2)->default(0);
            $table->enum('estado_pago', ['pendiente', 'procesando', 'pagado', 'rechazado', 'fallido', 'reembolso_pendiente'])->default('pendiente');
            $table->enum('estado_viaje', ['programado', 'en_progreso', 'completado', 'cancelado'])->default('programado');
            $table->string('pago_provider', 50)->nullable();
            $table->string('pago_referencia')->nullable();
            $table->timestamp('pagado_at')->nullable();
            $table->string('pago_error_mensaje', 500)->nullable();
            $table->timestamp('cancelado_at')->nullable();
            $table->foreignId('cancelado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('motivo_cancelacion', 500)->nullable();
            $table->enum('reembolso_estado', ['no_aplica', 'pendiente', 'revision', 'aprobado', 'rechazado', 'procesado'])->nullable();
            $table->decimal('reembolso_monto', 10, 2)->nullable();
            $table->timestamp('reembolso_solicitado_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'estado_viaje', 'fecha_viaje'], 'reservaciones_user_estado_fecha_idx');
            $table->index(['socio_id', 'estado_pago', 'estado_viaje'], 'reservaciones_socio_pago_viaje_idx');
            $table->index(['estado_pago', 'estado_viaje', 'fecha_viaje'], 'reservaciones_pago_viaje_fecha_idx');
            $table->index(['pago_provider', 'pago_referencia']);
            $table->index('reembolso_estado');
            });
        }

        if (! Schema::hasTable('email_verification_codes')) {
            Schema::create('email_verification_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('code_hash');
            $table->string('purpose', 50)->index();
            $table->string('verification_token_hash')->nullable()->index();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['email', 'purpose', 'expires_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('email_verification_codes');
        Schema::dropIfExists('reservaciones');
        Schema::dropIfExists('ruta_vehiculo');
        Schema::dropIfExists('rutas');
        Schema::dropIfExists('conductores');
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('lugares');
    }
};
