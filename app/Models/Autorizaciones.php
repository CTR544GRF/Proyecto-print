<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizaciones extends Model
{
    use HasFactory;

    protected $table = 'autorizaciones';
    protected $primaryKey = 'id_autorisacion';
    protected $fillable = [
        'user_id',
        'motivo',
        'fecha',
        'hora_inicio',
        'hora_expiracion',
        'estado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}