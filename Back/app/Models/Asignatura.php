<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
            ->with([
                'caracterizaciones' => function ($query) {
                    $query->orderBy('Orden');
                },
                'intenciones' => function ($query) {
                    $query->orderBy('Orden');
                }
            ]);
    }

    public function temasConSubtemas()
    {
        return $this->hasMany(Tema::class, 'Clave_Asignatura', 'ClaveAsignatura')
            ->with('subtemasRecursivos');
    }
    public function practias(): HasMany
    {
        return $this->hasMany(Practiasasignatura::class, 'ClaveAsignatura', 'ClaveAsignatura')
            ->orderBy('orden');
    }

    public function evaluacionesCompetencias(): HasMany
    {
        return $this->hasMany(EvaluacionCompetencias::class, 'ClaveAsignatura', 'ClaveAsignatura')
            ->orderBy('orden');
    }

    public function fuentesInformacion(): HasMany
    {
        return $this->hasMany(FuentesInformacion::class, 'ClaveAsignatura', 'ClaveAsignatura')
            ->orderBy('orden');
    }

     public function competencias(): HasMany
    {
        return $this->hasMany(Competencia::class, 'ClaveAsignatura', 'ClaveAsignatura');
    }

    

    public function competenciasEspecificas(): HasMany
    {
        return $this->competencias()->where('Tipo_Competencia', 'Específica');
    }

    public function competenciasPrevias(): HasMany
    {
        return $this->competencias()->where('Tipo_Competencia', 'Previas');
    }

    public function competenciasGenericas(): HasMany
    {
        return $this->competencias()->where('Tipo_Competencia', 'Generica');
    }

}
