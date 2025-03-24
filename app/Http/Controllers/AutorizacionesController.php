<?php
namespace App\Http\Controllers;

use App\Models\Autorizaciones;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AutorizacionesController extends Controller 
{
    public function index()
    {
        $autorizaciones = Autorizaciones::with('user')->get();
        return view('dashboard', compact('autorizaciones'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('autorizaciones', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'motivo' => 'required|string|max:255',
            'horas_autorizadas' => 'required|integer|min:1',
        ]);

        $horaInicio = Carbon::now();
        $horasAutorizadas = (int) $request->horas_autorizadas; 
        $horaExpiracion = $horaInicio->copy()->addHours($horasAutorizadas);

        Autorizaciones::create([
            'user_id' => $request->user_id,
            'motivo' => $request->motivo,
            'fecha' => now(),
            'hora_inicio' => $horaInicio,
            'hora_expiracion' => $horaExpiracion,
            'estado' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Autorización creada exitosamente.');
    }
}