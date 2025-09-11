<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicadorAlcanceInstrumentacion extends Model
{
    protected $table = 'indicadores_alcance_instrumentacion';
    protected $primaryKey = 'id_indicador';
    public $timestamps = false;

    protected $fillable = [
        'id_competencia',
        'indicador_alcance',
        'valor_indicador'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime'
    ];

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(CompetenciaInstrumentacion::class, 'id_competencia', 'id_competencia');
    }
}