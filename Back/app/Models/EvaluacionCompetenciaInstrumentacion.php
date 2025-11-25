<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluacionCompetenciaInstrumentacion extends Model
{
    protected $table = 'evaluacion_competencias_instrumentacion';

    protected $primaryKey = 'id_evaluacion_competencia';

    public $timestamps = false;

    protected $fillable = [
        'id_competencia',
        'evidencia_aprendizaje',
        'porcentaje_valor',
        'evaluacion_formativa',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(CompetenciaInstrumentacion::class, 'id_competencia', 'id_competencia');
    }

    public function indicadoresAlcance(): HasMany
    {
        return $this->hasMany(IndicadorAlcanceEvaluacionInstrumentacion::class, 'id_evaluacion_competencia', 'id_evaluacion_competencia');
    }
}
