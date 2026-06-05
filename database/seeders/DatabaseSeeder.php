<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@andrufar.com'],
            [
                'name'     => 'Administrador',
                'ci'       => '12345678',
                'telefono' => '70000000',
                'password' => Hash::make('admin123'),
                'rol'      => 'admin',
                'activo'   => true,
            ]
        );
    }
}