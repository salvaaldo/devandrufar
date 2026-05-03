<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrador',
            'ci'       => '12345678',
            'telefono' => '70000000',
            'email'    => 'admin@andrufar.com',
            'password' => Hash::make('admin123'),
            'rol'      => 'admin',
            'activo'   => true,
        ]);
    }
}