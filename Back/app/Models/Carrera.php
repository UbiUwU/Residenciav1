<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carreras';
    protected $primaryKey = 'clavecarrera';
    public $incrementing = false; // La PK es varchar, no autoincremental
    public $timestamps = false;

    protected $fillable = [
        'clavecarrera',
        'nombre',
        'descripcion',
        'generacion',
    ];

    // Ejemplo de relación: si tienes alumnos relacionados
    // public function alumnos() {
    //     return $this->hasMany(Alumno::class, 'clavecarrera');
    // }
}
