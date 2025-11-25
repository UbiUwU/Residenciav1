<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetenciaGenericoInstrumentacion extends Model
{
    protected $table = 'competencia_generico_instrumentacion';

    protected $primaryKey = 'id_competencia_generico_instrumentacion';

    public $timestamps = false;

    protected $fillable = [
        'id_competencia',
        'descripcion',
        'orden',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(CompetenciaInstrumentacion::class, 'id_competencia', 'id_competencia');
    }
}
