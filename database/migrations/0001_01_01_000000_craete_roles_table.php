<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Insertamos los roles de una vez
        DB::table('roles')->insert([
            ['name' => 'Administrador', 'slug' => 'admin'],
            ['name' => 'Cliente', 'slug' => 'cliente'],
            ['name' => 'Conductor', 'slug' => 'conductor'],
            ['name' => 'Afiliado', 'slug' => 'afiliado'],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('roles');
    }
};
