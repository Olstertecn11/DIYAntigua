<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! $this->columnExists('users', 'avatar_base64')) {
                $table->longText('avatar_base64')->nullable()->after('direccion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if ($this->columnExists('users', 'avatar_base64')) {
                $table->dropColumn('avatar_base64');
            }
        });
    }

    private function columnExists(string $table, string $column): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::selectOne(
            'select count(*) as total from information_schema.columns where table_schema = ? and table_name = ? and column_name = ?',
            [$database, $table, $column],
        );

        return (int) ($result->total ?? 0) > 0;
    }
};
