<?php

namespace App\Http\Controllers;

use App\Models\Autorizaciones;
use Illuminate\Http\Request;

class AutorizacionController extends Controller
{
    public function index()
    {
        // Obtener todas las autorizaciones con la relación con usuarios
        $autorizaciones = Autorizaciones::with('user')->get();
        
        // Retornar la vista y pasar las autorizaciones
        return view('dashboard', compact('autorizaciones'));
    }
}
