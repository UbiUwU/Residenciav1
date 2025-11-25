<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicadorAlcance extends Model
{
    protected $table = 'indicadores_alcance';

    protected $primaryKey = 'id_indicador_alcance';

    public $timestamps = false;

    protected $fillable = [
        'id_nivel_desempeno',
        'descripcion',
        'orden',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function nivelDesempeno(): BelongsTo
    {
        return $this->belongsTo(NivelDesempenoInstrumentacion::class, 'id_nivel_desempeno', 'id_nivel_desempeno');
    }
}
