<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarizacionEvaluacionInstrumentacion extends Model
{
    protected $table = 'calendarizacion_evaluacion_instrumentacion';
    protected $primaryKey = 'id_calendarizacion';
    public $timestamps = false;

    protected $fillable = [
        'id_instrumentacion',
        'semana',
        'tiempo_planeado',
        'tiempo_real',
        'seguimiento_departamental',
        'descripcion'
    ];

    protected $casts = [
        'seguimiento_departamental' => 'boolean',
        'fecha_creacion' => 'datetime'
    ];

    public function instrumentacion(): BelongsTo
    {
        return $this->belongsTo(Instrumentacion::class, 'id_instrumentacion', 'id_instrumentacion');
    }
}