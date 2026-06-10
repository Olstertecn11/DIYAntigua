<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reservaciones')) {
            $this->addIndex('reservaciones', 'reservaciones_user_estado_fecha_idx', ['user_id', 'estado_viaje', 'fecha_viaje']);
            $this->addIndex('reservaciones', 'reservaciones_socio_pago_viaje_idx', ['socio_id', 'estado_pago', 'estado_viaje']);
            $this->addIndex('reservaciones', 'reservaciones_pago_viaje_fecha_idx', ['estado_pago', 'estado_viaje', 'fecha_viaje']);
        }

        if (Schema::hasTable('payment_transactions')) {
            $this->addIndex('payment_transactions', 'payment_transactions_reserva_latest_idx', ['reservacion_id', 'created_at']);
        }

        if (Schema::hasTable('role_user')) {
            $this->addIndex('role_user', 'role_user_role_user_idx', ['role_id', 'user_id']);
        }
    }

    public function down(): void
    {
        foreach ([
            ['reservaciones', 'reservaciones_user_estado_fecha_idx'],
            ['reservaciones', 'reservaciones_socio_pago_viaje_idx'],
            ['reservaciones', 'reservaciones_pago_viaje_fecha_idx'],
            ['payment_transactions', 'payment_transactions_reserva_latest_idx'],
            ['role_user', 'role_user_role_user_idx'],
        ] as [$table, $index]) {
            if (Schema::hasTable($table) && $this->indexExists($table, $index)) {
                DB::statement("alter table {$table} drop index {$index}");
            }
        }
    }

    private function addIndex(string $table, string $name, array $columns): void
    {
        foreach ($columns as $column) {
            if (! $this->columnExists($table, $column)) {
                return;
            }
        }

        if ($this->indexExists($table, $name)) {
            return;
        }

        DB::statement(sprintf(
            'create index %s on %s (%s)',
            $name,
            $table,
            implode(', ', $columns)
        ));
    }

    private function indexExists(string $table, string $index): bool
    {
        return (bool) DB::selectOne(
            'select 1 from information_schema.statistics where table_schema = database() and table_name = ? and index_name = ? limit 1',
            [$table, $index]
        );
    }

    private function columnExists(string $table, string $column): bool
    {
        return (bool) DB::selectOne(
            'select 1 from information_schema.columns where table_schema = database() and table_name = ? and column_name = ? limit 1',
            [$table, $column]
        );
    }
};
