<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NivelDesempenoInstrumentacion extends Model
{
    protected $table = 'niveles_desempeno_instrumentacion';

    protected $primaryKey = 'id_nivel_desempeno';

    public $timestamps = false;

    protected $fillable = [
        'id_competencia',
        'desempeno_alcanzado',
        'nivel_desempeno',
        'valoracion_inicial',
        'valoracion_final',
    ];

    protected $casts = [
        'desempeno_alcanzado' => 'boolean',
        'fecha_creacion' => 'datetime',
    ];

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(CompetenciaInstrumentacion::class, 'id_competencia', 'id_competencia');
    }

    public function indicadoresAlcance(): HasMany
    {
        return $this->hasMany(IndicadorAlcance::class, 'id_nivel_desempeno', 'id_nivel_desempeno');
    }
}
