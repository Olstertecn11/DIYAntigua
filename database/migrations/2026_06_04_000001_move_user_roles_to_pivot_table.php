<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'role_id']);
            });
        }

        if ($this->columnExists('users', 'role_id')) {
            DB::table('users')
                ->whereNotNull('role_id')
                ->select(['id', 'role_id', 'created_at', 'updated_at'])
                ->orderBy('id')
                ->get()
                ->each(function ($user) {
                    DB::table('role_user')->insertOrIgnore([
                        'user_id' => $user->id,
                        'role_id' => $user->role_id,
                        'created_at' => $user->created_at ?? now(),
                        'updated_at' => $user->updated_at ?? now(),
                    ]);
                });
        }

        if (Schema::hasTable('afiliados_info')) {
            $affiliateRoleId = DB::table('roles')->where('slug', 'afiliado')->value('id');

            if ($affiliateRoleId) {
                DB::table('afiliados_info')
                    ->whereNotNull('user_id')
                    ->pluck('user_id')
                    ->each(function ($userId) use ($affiliateRoleId) {
                        DB::table('role_user')->insertOrIgnore([
                            'user_id' => $userId,
                            'role_id' => $affiliateRoleId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    });
            }
        }

        if (Schema::hasTable('users') && $this->columnExists('users', 'role_id')) {
            if ($this->foreignKeyExists('users', 'users_role_id_foreign')) {
                DB::statement('alter table users drop foreign key users_role_id_foreign');
            }

            if ($this->indexExists('users', 'users_role_idx')) {
                DB::statement('alter table users drop index users_role_idx');
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && ! $this->columnExists('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('role_id')->nullable()->after('password')->constrained('roles')->nullOnDelete();
            });
        }

        if (Schema::hasTable('role_user') && $this->columnExists('users', 'role_id')) {
            DB::statement('
                update users
                left join (
                    select user_id, min(role_id) as role_id
                    from role_user
                    group by user_id
                ) selected_roles on selected_roles.user_id = users.id
                set users.role_id = selected_roles.role_id
            ');
        }

        Schema::dropIfExists('role_user');
    }

    private function columnExists(string $table, string $column): bool
    {
        return Schema::hasColumn($table, $column);
    }

    private function indexExists(string $table, string $index): bool
    {
        return collect(Schema::getIndexes($table))
            ->contains(fn (array $existingIndex) => ($existingIndex['name'] ?? null) === $index);
    }

    private function foreignKeyExists(string $table, string $foreignKey): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            return false;
        }

        return (bool) DB::selectOne(
            'select 1 from information_schema.table_constraints where table_schema = database() and table_name = ? and constraint_name = ? and constraint_type = "FOREIGN KEY" limit 1',
            [$table, $foreignKey]
        );
    }
};
