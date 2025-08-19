<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    protected $table = 'comision';
    protected $primaryKey = 'id_comision';
    public $timestamps = false;

    protected $fillable = [
        'folio',
        'nombre_evento',
        'id_tipo_evento',
        'id_periodo_escolar',
        'estado',
        'remitente_nombre',
        'remitente_puesto',
        'lugar',
        'motivo',
        'tipo_comision',
        'permiso_constancia',
    ];

    // Relaciones
    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class, 'id_tipo_evento');
    }

    public function periodoEscolar()
    {
        return $this->belongsTo(PeriodoEscolar::class, 'id_periodo_escolar');
    }

    public function fechas()
    {
        return $this->hasMany(ComisionFecha::class, 'id_comision');
    }

    public function maestros()
    {
        return $this->belongsToMany(
            Maestro::class,
            'comision_maestro',
            'id_comision',
            'tarjeta_maestro',
            'id_comision',
            'tarjeta'
        );
    }
}
