<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! $this->columnExists('users', 'telefono')) {
                    $table->string('telefono', 30)->nullable()->after('email');
                }

                if (! $this->columnExists('users', 'direccion')) {
                    $table->string('direccion')->nullable()->after('telefono');
                }
            });
        }

        if (Schema::hasTable('afiliados_info')) {
            Schema::table('afiliados_info', function (Blueprint $table) {
                if (! $this->columnExists('afiliados_info', 'codigo_referido')) {
                    $table->string('codigo_referido', 80)->nullable()->unique()->after('user_id');
                }

                if (! $this->columnExists('afiliados_info', 'metodo_pago')) {
                    $table->string('metodo_pago')->nullable()->after('activo');
                }

                if (! $this->columnExists('afiliados_info', 'titular_pago')) {
                    $table->string('titular_pago')->nullable()->after('metodo_pago');
                }

                if (! $this->columnExists('afiliados_info', 'cuenta_pago')) {
                    $table->string('cuenta_pago')->nullable()->after('titular_pago');
                }
            });
        }

        if (Schema::hasTable('reservaciones')) {
            Schema::table('reservaciones', function (Blueprint $table) {
                if (! $this->columnExists('reservaciones', 'socio_id')) {
                    $table->foreignId('socio_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
                }

                if (! $this->columnExists('reservaciones', 'comision_socio')) {
                    $table->decimal('comision_socio', 10, 2)->default(0)->after('precio_total');
                }
            });
        }

        if (Schema::hasTable('users') && Schema::hasTable('afiliados_info')) {
            DB::table('users')
                ->whereIn('id', DB::table('afiliados_info')->select('user_id'))
                ->update(['role_id' => config('constantes.idAffiliate')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('reservaciones')) {
            Schema::table('reservaciones', function (Blueprint $table) {
                foreach (['comision_socio', 'socio_id'] as $column) {
                    if ($this->columnExists('reservaciones', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('afiliados_info')) {
            Schema::table('afiliados_info', function (Blueprint $table) {
                foreach (['cuenta_pago', 'titular_pago', 'metodo_pago', 'codigo_referido'] as $column) {
                    if ($this->columnExists('afiliados_info', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                foreach (['direccion', 'telefono'] as $column) {
                    if ($this->columnExists('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    private function columnExists(string $table, string $column): bool
    {
        return (bool) DB::selectOne(
            'select 1 from information_schema.columns where table_schema = database() and table_name = ? and column_name = ? limit 1',
            [$table, $column]
        );
    }
};
