<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        return view('usuarios.crear_usuarios');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'numero_telefono' => 'required|string|max:15',
            'direccion_residencia' => 'required|string|max:255',
            'tipo_usuario' => 'required|in:administrador,usuario',
            'password' => [
                'required_if:tipo_usuario,administrador',
                'nullable',
                'min:8',
                'confirmed'
            ],
            'fingerprint_data' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[A-F0-9]{400,800}$/i', $value)) {
                        $fail('El formato de la huella dactilar es inv�lido.');
                    }
                },
            ],
        ]);

        try {
            $user = new User();
            $user->nombre = $validated['nombre'];
            $user->numero_documento = $validated['numero_documento'];
            $user->email = $validated['email'];
            $user->numero_telefono = $validated['numero_telefono'];
            $user->direccion_residencia = $validated['direccion_residencia'];
            $user->tipo_usuario = $validated['tipo_usuario'];
            
            // Encriptar contrase�a solo para administradores
            if ($validated['tipo_usuario'] === 'administrador') {
                $user->password = Hash::make($validated['password']);
            }

            // Encriptar y guardar la huella dactilar
            $user->fingerprint_data = encrypt($validated['fingerprint_data']);

            $user->save();

            return redirect()
                ->route('usuarios.index')
                ->with('success', 'Usuario registrado con huella dactilar correctamente.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al registrar el usuario: ' . $e->getMessage()]);
        }
    }

    // M�todo para verificar huellas 
    public function captureFingerprint(Request $request)
    {
        try {
            // Ruta al script Python
            $pythonScript = env('PYTHON_SCRIPT_PATH', '/home/usuario/Desktop/Proyecto-print/scripts/capture_fingerprint.py');
            
            // Ejecutar el script Python
            $output = shell_exec("python3 {$pythonScript} 2>&1");
            
            // Decodificar la salida JSON
            $result = json_decode($output, true);
            
            if (!$result) {
                throw new \Exception("No se pudo decodificar la respuesta del sensor");
            }
            
            if (isset($result['success']) && $result['success']) {
                return response()->json([
                    'success' => true,
                    'fingerprint' => $result['fingerprint'],
                    'filename' => $result['filename']
                ]);
            } else {
                throw new \Exception($result['error'] ?? "Error desconocido al capturar huella");
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}