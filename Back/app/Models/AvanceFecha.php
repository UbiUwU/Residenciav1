<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvanceFecha extends Model
{
    protected $table = 'avance_fechas';
    protected $primaryKey = 'id_avance_fecha';
    public $timestamps = false;

    protected $fillable = [
        'id_avance',
        'id_fecha_clave',
        'observaciones',
        'fecha_real'
    ];

    // Relación con Avance
    public function avance()
    {
        return $this->belongsTo(Avance::class, 'id_avance');
    }

    // Relación con la fecha clave del periodo escolar
    public function fechaClave()
    {
        return $this->belongsTo(FechasClavePeriodo::class, 'id_fecha_clave');
    }
}
