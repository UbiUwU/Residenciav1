<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicadorAlcanceEvaluacionInstrumentacion extends Model
{
    protected $table = 'indicadores_alcance_evaluacion_instrumentacion';

    protected $primaryKey = 'id_indicador_alcance';

    public $timestamps = false;

    protected $fillable = [
        'id_evaluacion_competencia',
        'letra_indicador',
        'porcentaje',
        'descripcion',
        'orden',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(EvaluacionCompetenciaInstrumentacion::class, 'id_evaluacion_competencia', 'id_evaluacion_competencia');
    }
}
