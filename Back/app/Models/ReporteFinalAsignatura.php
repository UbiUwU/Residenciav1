<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReporteFinalAsignatura extends Model
{
    protected $table = 'reportefinal_asignatura';
    protected $primaryKey = 'id_reportefinal_asignatura';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    protected $fillable = [
        'id_reportefinal',
        'clave_asignatura',
        'clave_carrera',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'
    ];

    protected $attributes = [
        'a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 
        'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0,
        'clave_carrera' => null
    ];

    protected $casts = [
    'clave_carrera' => 'string',
    'clave_asignatura' => 'string',
    'id_reportefinal' => 'integer',
    'a' => 'integer',
    'b' => 'integer',
    'c' => 'decimal:2',
    'd' => 'integer',
    'e' => 'decimal:2',
    'f' => 'integer',
    'g' => 'decimal:2',
    'h' => 'decimal:2',
    'fecha_registro' => 'datetime',
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
     * Relación con la asignatura
     */
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'clave_asignatura', 'ClaveAsignatura');
    }

    /**
     * Relación con la carrera
     */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'clave_carrera', 'clavecarrera');
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
            $model->fecha_registro = now();
            $model->fecha_actualizacion = now();
        });
    }

    // REMOVER TODOS LOS ACCESSORS Y MUTATORS
    // Estos están causando que Laravel piense que 'a', 'b', 'c' son relaciones
}