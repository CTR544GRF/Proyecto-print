<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        return view('usuarios.crear_usuarios');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:20|unique:users', // Asegúrate que coincida con el nombre de tu tabla
            'email' => 'required|email|unique:users',
            'numero_telefono' => 'required|string|max:15',
            'direccion_residencia' => 'required|string|max:255',
            'tipo_usuario' => 'required|string',
            'password' => 'required_if:tipo_usuario,administrador',
            'fingerprint_data' => 'required|string', // Se debe capturar la huella
        ]);

        $usuario = new User();
        $usuario->nombre = $request->nombre;
        $usuario->numero_documento = $request->numero_documento;
        $usuario->email = $request->email;
        $usuario->numero_telefono = $request->numero_telefono;
        $usuario->direccion_residencia = $request->direccion_residencia;
        $usuario->tipo_usuario = $request->tipo_usuario;
        $usuario->password = $request->tipo_usuario == 'administrador' ? Hash::make($request->password) : null;
        $usuario->fingerprint_data = $request->fingerprint_data; // Guarda la huella

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado con éxito.');
    }
}