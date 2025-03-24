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

        if ($user) {
            for ($i = 1; $i <= 4; $i++) {
                autorizaciones::create([
                    'user_id' => $user->id,  
                    'motivo' => 'Motivo de autorización ' . $i,  
                    'fecha' => Carbon::now()->subDays(rand(1, 30)), 
                    'hora_inicio' => Carbon::now()->subHours(rand(1, 5)), 
                    'hora_expiracion' => Carbon::now()->addHours(rand(1, 3)), 
                    'estado' => false, 
                    'created_at' => now(),
                    'updated_at' => now() 
                ]);
            }
        } else {
            
            echo "Usuario con número de documento 1003510437 no encontrado.\n";
        }
    }
}
