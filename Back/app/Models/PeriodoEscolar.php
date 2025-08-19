<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
