<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'email' => 'admin@antiguatransfers.com',
        ], [
            'name' => 'Oliver Admin',
            'password' => Hash::make('password123'),
        ]);

        $admin->assignRole('admin');

        $this->command->info('Usuario administrador creado/actualizado con éxito.');
    }
}
