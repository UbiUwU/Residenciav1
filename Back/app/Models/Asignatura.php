<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    protected $table = 'asignatura';
    protected $primaryKey = 'ClaveAsignatura';
    public $incrementing = false; // NO es autoincremental
    protected $keyType = 'string'; // varchar
    public $timestamps = false;

    protected $fillable = [
        'ClaveAsignatura',
        'NombreAsignatura',
        'Creditos',
        'Satca_Practicas',
        'Satca_Teoricas',
        'Satca_Total'
    ];

    public function carreras()
    {
        return $this->belongsToMany(
            Carrera::class,
            'asignatura_carrera',
            'Clave_Asignatura',
            'Clave_Carrera',
            'ClaveAsignatura',
            'clavecarrera'
        )->withPivot('Semestre', 'Posicion');
    }
}
