<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FechasClavePeriodo extends Model
{
    protected $table = 'fechas_clave_periodo';
    protected $primaryKey = 'id_fecha_clave';
    public $timestamps = true; // created_at y updated_at

    protected $fillable = [
        'periodo_escolar_id',
        'tipo_fecha_clave',
        'nombre_fecha',
        'descripcion',
        'fecha',
        'fecha_limite',
        'es_obligatoria',
    ];

    /**
     * Relación con Periodo Escolar
     */
    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class, 'periodo_escolar_id', 'id_periodo_escolar');
    }

    /**
     * Relación opcional con el catálogo de tipos de fecha
     * (si después quieres usar clave en lugar de texto libre).
     */
    public function tipoCatalogo(): BelongsTo
    {
        return $this->belongsTo(CatalogoTiposFecha::class, 'tipo_fecha_clave', 'clave');
    }

    public function scopeFechasClave($query)
    {
        return $query->select('id_fecha_clave','periodo_escolar_id','tipo_fecha_clave', 'nombre_fecha');
    }
}
