<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nombre' => 'Juan Diaz',
            'numero_documento' => '1003510437',
            'email' => '1003camilodiaz@gmail.com',
            'numero_telefono' => '3013623860',
            'direccion_residencia' => 'Calle 123, Ciudad, País',
            'tipo_usuario' => 'admin',
            'password' => Hash::make('1003510437'), 
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
