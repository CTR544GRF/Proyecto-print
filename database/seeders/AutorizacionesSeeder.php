<?php

namespace Database\Seeders;

use App\Models\autorizaciones;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AutorizacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtener el usuario con el número de documento dado (Juan Diaz)
        $user = User::where('numero_documento', '1003510437')->first();

        // Verificar si el usuario existe
        if ($user) {
            // Crear 10 registros de autorizaciones
            for ($i = 1; $i <= 10; $i++) {
                autorizaciones::create([
                    'user_id' => $user->id,  // Relacionamos con el usuario creado
                    'motivo' => 'Motivo de autorización ' . $i,  // Motivo para cada autorización
                    'fecha' => Carbon::now()->subDays(rand(1, 30)),  // Fecha aleatoria en los últimos 30 días
                    'hora_inicio' => Carbon::now()->subHours(rand(1, 5)),  // Hora de inicio aleatoria en las últimas 5 horas
                    'hora_expiracion' => Carbon::now()->addHours(rand(1, 3)),  // Hora de expiración aleatoria en las próximas 1-3 horas
                    'estado' => rand(0, 1) ? 'autorizado' : 'no_autorizado',  // Estado aleatorio entre 'autorizado' y 'no_autorizado'
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            // Si no se encuentra el usuario, lanzar un mensaje
            echo "Usuario con número de documento 1003510437 no encontrado.\n";
        }
    }
}
