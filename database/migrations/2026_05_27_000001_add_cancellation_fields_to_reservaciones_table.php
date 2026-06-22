<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('reservaciones')) {
            return;
        }

        Schema::table('reservaciones', function (Blueprint $table) {
            if (! $this->columnExists('reservaciones', 'cancelado_at')) {
                $table->timestamp('cancelado_at')->nullable()->after('pago_error_mensaje');
            }

            if (! $this->columnExists('reservaciones', 'cancelado_por')) {
                $table->foreignId('cancelado_por')->nullable()->after('cancelado_at')->constrained('users')->nullOnDelete();
            }

            if (! $this->columnExists('reservaciones', 'motivo_cancelacion')) {
                $table->string('motivo_cancelacion', 500)->nullable()->after('cancelado_por');
            }

            if (! $this->columnExists('reservaciones', 'reembolso_estado')) {
                $table->string('reembolso_estado', 50)->nullable()->after('motivo_cancelacion')->index();
            }

            if (! $this->columnExists('reservaciones', 'reembolso_monto')) {
                $table->decimal('reembolso_monto', 10, 2)->nullable()->after('reembolso_estado');
            }

            if (! $this->columnExists('reservaciones', 'reembolso_solicitado_at')) {
                $table->timestamp('reembolso_solicitado_at')->nullable()->after('reembolso_monto');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('reservaciones')) {
            return;
        }

        Schema::table('reservaciones', function (Blueprint $table) {
            foreach (['reembolso_solicitado_at', 'reembolso_monto', 'reembolso_estado', 'motivo_cancelacion', 'cancelado_por', 'cancelado_at'] as $column) {
                if ($this->columnExists('reservaciones', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function columnExists(string $table, string $column): bool
    {
        return Schema::hasColumn($table, $column);
    }
};
