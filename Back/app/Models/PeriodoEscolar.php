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

    // Relación con comisiones
    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'id_periodo_escolar');
    }
    public function fechasClave(): HasMany
    {
        return $this->hasMany(FechasClavePeriodo::class, 'periodo_escolar_id');
    }

    // Acceso a la información del catálogo desde fechas_clave
    public function fechasClaveConCatalogo()
    {
        return $this->fechasClave()->with('tipoCatalogo');
    }

    public function scopePeriodos($query)
    {
        return $query->select('id_periodo_escolar', 'codigoperiodo', 'nombre_periodo', 'fecha_inicio', 'fecha_fin', 'estado');
    }

}
