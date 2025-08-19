<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'idusuario';
    public $incrementing = true; // Porque usa secuencia
    public $timestamps = false;

    protected $fillable = [
        'correo',
        'password',
        'token',
        'idrol',
    ];

    // Relación con rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idrol', 'idrol');
    }
}
