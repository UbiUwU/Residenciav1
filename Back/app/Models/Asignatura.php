<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function presentacion(): HasOne
    {
        return $this->hasOne(Presentacion::class, 'Clave_Asignatura', 'ClaveAsignatura')
            ->with(['caracterizaciones' => function($query) {
                $query->orderBy('Orden');
            }, 'intenciones' => function($query) {
                $query->orderBy('Orden');
            }]);
    }

    public function temasConSubtemas()
{
    return $this->hasMany(Tema::class, 'Clave_Asignatura', 'ClaveAsignatura')
                ->with('subtemasRecursivos');
}


}
