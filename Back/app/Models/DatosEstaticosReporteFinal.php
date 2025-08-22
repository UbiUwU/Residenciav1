<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatosEstaticosReporteFinal extends Model
{
    protected $table = 'datos_estaticos_reportefinal';
    protected $primaryKey = 'id_datos_estaticos';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    protected $fillable = [
        'id_reportefinal',
        'numero_grupos_atendidos',
        'numero_estudiantes',
        'numero_asignaturas_diferentes'
    ];

    protected $attributes = [
        'numero_grupos_atendidos' => 0,
        'numero_estudiantes' => 0,
        'numero_asignaturas_diferentes' => 0
    ];

    protected $casts = [
        'numero_grupos_atendidos' => 'integer',
        'numero_estudiantes' => 'integer',
        'numero_asignaturas_diferentes' => 'integer',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime'
    ];

    /**
     * Relación con el reporte final
     */
    public function reporteFinal(): BelongsTo
    {
        return $this->belongsTo(ReporteFinal::class, 'id_reportefinal', 'id_reportefinal');
    }

    /**
     * Hook para actualizar la fecha de actualización antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            $model->fecha_actualizacion = now();
        });

        static::creating(function ($model) {
            $model->fecha_creacion = now();
            $model->fecha_actualizacion = now();
        });
    }
}