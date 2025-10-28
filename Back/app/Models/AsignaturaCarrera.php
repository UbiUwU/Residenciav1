<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignaturaCarrera extends Model
{
    protected $table = 'asignatura_carrera';
    protected $primaryKey = 'idAsig_Carrera';
    public $incrementing = true; // usa secuencia
    public $timestamps = false;

    protected $fillable = [
        'Clave_Asignatura',
        'Clave_Carrera',
        'Semestre',
        'Posicion'
    ];

    // Relación con la carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'Clave_Carrera', 'clavecarrera');
    }

    // Relación con la asignatura (asumiendo que tienes modelo Asignatura)
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'Clave_Asignatura', 'ClaveAsignatura');
    }
}
