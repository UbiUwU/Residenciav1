<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $primaryKey = 'clavegrupo';

    public $incrementing = false;       // No es autoincremental

    protected $keyType = 'string';      // IMPORTANTE: clave primaria es string

    public $timestamps = false;

    protected $fillable = [
        'clavegrupo',
        'nombre',
        'descripcion',
    ];

    // Relación con HorarioAsignaturaMaestro
    public function horarios()
    {
        return $this->hasMany(HorarioAsignaturaMaestro::class, 'clavegrupo', 'clavegrupo');
    }
}
