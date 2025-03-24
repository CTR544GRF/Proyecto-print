<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FingerprintController extends Controller
{
    /**
     * Verifica si el sensor de huella está disponible.
     */
    public function check(): JsonResponse
    {
        $env = env('FINGERPRINT_ENV', 'production');
        
        // En Raspberry Pi se espera que el sensor esté conectado
        if ($env === 'raspberry') {
            // Aquí se podría llamar a una función que verifique el sensor real
            $sensorAvailable = true; // Simulación o integración real
        } else {
            // En el entorno de producción (o pruebas sin hardware) se simula que no hay sensor
            $sensorAvailable = false;
        }

        return response()->json([
            'sensorAvailable' => $sensorAvailable,
            'message' => $sensorAvailable ? 'Sensor detectado' : 'Sensor no disponible'
        ]);
    }

    /**
     * Captura la huella del usuario.
     */
    public function capture(Request $request): JsonResponse
    {
        $env = env('FINGERPRINT_ENV', 'production');
        
        if ($env === 'raspberry') {
            // Aquí se integraría la lógica real de captura con el sensor DY50.
            // Por ejemplo, ejecutando un script en Python o utilizando una librería.
            // Para efectos de prueba, se simula la captura:
            $fingerprintData = "fingerprint_data_real_" . time();
        } else {
            // En producción sin sensor (o para pruebas), se puede simular la captura:
            $fingerprintData = "fingerprint_data_simulada_" . time();
        }

        return response()->json([
            'success' => true,
            'fingerprintData' => $fingerprintData,
            'message' => 'Huella capturada correctamente'
        ]);
    }
}