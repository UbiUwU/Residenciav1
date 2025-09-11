<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrumentacion extends Model
{
    protected $table = 'instrumentacion';
    protected $primaryKey = 'id_instrumentacion';
    public $timestamps = false;

    protected $fillable = [
        'ClaveAsignatura',
        'tarjeta_profesor',
        'clavecarrera',
        'id_periodo_escolar',
        'nombre_jefe_academico',
        'id_departamento',
        'estado'
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
        'fecha_ultima_actualizacion' => 'datetime'
    ];

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'ClaveAsignatura', 'ClaveAsignatura');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'clavecarrera', 'clavecarrera');
    }

    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class, 'id_periodo_escolar', 'id_periodo_escolar');
    }

    public function maestro(): BelongsTo
    {
        return $this->belongsTo(Maestro::class, 'tarjeta_profesor', 'tarjeta');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    public function competencias(): HasMany
    {
        return $this->hasMany(CompetenciaInstrumentacion::class, 'id_instrumentacion', 'id_instrumentacion');
    }

    public function apoyosDidacticos(): HasMany
    {
        return $this->hasMany(ApoyoDidacticoInstrumentacion::class, 'id_instrumentacion', 'id_instrumentacion');
    }

    public function calendarizaciones(): HasMany
    {
        return $this->hasMany(CalendarizacionEvaluacionInstrumentacion::class, 'id_instrumentacion', 'id_instrumentacion');
    }
}