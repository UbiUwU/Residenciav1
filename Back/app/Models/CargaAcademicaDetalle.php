<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargaAcademicaDetalle extends Model
{
    protected $table = 'carga_academica_detalles';

    protected $primaryKey = 'idcargadetalle';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'clavehorario',
        'idcargageneral',
    ];

    /**
     * Relación: El detalle pertenece a una carga académica general
     */
    public function cargaGeneral()
    {
        return $this->belongsTo(CargaAcademicaGeneral::class, 'idcargageneral', 'idcargageneral');
    }

    /**
     * Relación: El detalle está ligado a un horario de asignatura maestro
     */
    public function horarioAsignatura()
    {
        return $this->belongsTo(HorarioAsignaturaMaestro::class, 'clavehorario', 'clavehorario');
    }
}
