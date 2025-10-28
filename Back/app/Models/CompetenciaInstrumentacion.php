<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetenciaInstrumentacion extends Model
{
    protected $table = 'competencias_instrumentacion';
    protected $primaryKey = 'id_competencia';
    public $timestamps = false;

    protected $fillable = [
        'id_instrumentacion',
        'id_tema',
        'horas_dedicadas'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime'
    ];

    public function instrumentacion(): BelongsTo
    {
        return $this->belongsTo(Instrumentacion::class, 'id_instrumentacion', 'id_instrumentacion');
    }

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'id_tema', 'id_Tema');
    }

    public function competenciasGenericas(): HasMany
    {
        return $this->hasMany(CompetenciaGenericoInstrumentacion::class, 'id_competencia', 'id_competencia');
    }

    public function actividadesEnsenanza(): HasMany
    {
        return $this->hasMany(ActividadEnsenanzaInstrumentacion::class, 'id_competencia', 'id_competencia');
    }

    public function indicadoresAlcance(): HasMany
    {
        return $this->hasMany(IndicadorAlcanceInstrumentacion::class, 'id_competencia', 'id_competencia');
    }

    public function nivelesDesempeno(): HasMany
    {
        return $this->hasMany(NivelDesempenoInstrumentacion::class, 'id_competencia', 'id_competencia');
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(EvaluacionCompetenciaInstrumentacion::class, 'id_competencia', 'id_competencia');
    }
}