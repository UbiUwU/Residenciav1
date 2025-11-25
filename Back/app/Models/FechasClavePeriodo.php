<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FechasClavePeriodo extends Model
{
    protected $table = 'fechas_clave_periodo';

    protected $primaryKey = 'id_fecha_clave';

    public $timestamps = true;

    protected $fillable = [
        'periodo_escolar_id',
        'id_tipo_fecha',
        'nombre_fecha',
        'descripcion',
        'fecha',
        'fecha_limite',
        'es_obligatoria',
    ];

    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class, 'periodo_escolar_id', 'id_periodo_escolar');
    }

    public function tipoFecha(): BelongsTo // Nombre más descriptivo
    {
        return $this->belongsTo(CatalogoTiposFecha::class, 'id_tipo_fecha', 'id_tipo_fecha');
    }
}
