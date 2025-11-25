<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioAsignaturaMaestro extends Model
{
    protected $table = 'horarioasignatura_maestro';

    protected $primaryKey = 'clavehorario';

    public $incrementing = true;

    protected $keyType = 'integer';

    public $timestamps = false;

    protected $fillable = [
        'tarjeta', 'claveaula', 'clavegrupo', 'claveasignatura', 'idperiodoescolar',
        'lunes_hi', 'lunes_hf', 'martes_hi', 'martes_hf', 'miercoles_hi', 'miercoles_hf',
        'jueves_hi', 'jueves_hf', 'viernes_hi', 'viernes_hf', 'sabado_hi', 'sabado_hf',
    ];

    public function maestro()
    {
        return $this->belongsTo(Maestro::class, 'tarjeta', 'tarjeta');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'claveaula', 'claveaula');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'clavegrupo', 'clavegrupo');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'claveasignatura', 'ClaveAsignatura');
    }

    public function periodoEscolar()
    {
        return $this->belongsTo(PeriodoEscolar::class, 'idperiodoescolar', 'id_periodo_escolar');
    }

    public function cargaDetalles()
    {
        return $this->hasMany(CargaAcademicaDetalle::class, 'clavehorario', 'clavehorario');
    }
}
