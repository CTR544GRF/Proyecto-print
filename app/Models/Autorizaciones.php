<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'motivo',
        'fecha',
        'hora_inicio',
        'hora_expiracion',
        'estado',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
