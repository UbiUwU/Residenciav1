<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodoEscolar extends Model
{
    protected $table = 'periodo_escolar';
    protected $primaryKey = 'id_periodo_escolar';
    public $timestamps = false;

    protected $fillable = [
        'codigoperiodo',
        'nombre_periodo',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'id_periodo_escolar');
    }

    public function fechasClave(): HasMany
    {
        return $this->hasMany(FechasClavePeriodo::class, 'periodo_escolar_id');
    }

    public function scopePeriodos($query)
    {
        return $query->select('id_periodo_escolar', 'codigoperiodo', 'nombre_periodo', 'fecha_inicio', 'fecha_fin', 'estado');
    }

    public function liberacionesDocentes(): HasMany
    {
        return $this->hasMany(LiberacionDocente::class, 'id_periodo_escolar', 'id_periodo_escolar');
    }

    public function liberacionesAcademicas(): HasMany
    {
        return $this->hasMany(LiberacionAcademica::class, 'id_periodo_escolar', 'id_periodo_escolar');
    }
}