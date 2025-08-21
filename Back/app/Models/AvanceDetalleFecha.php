<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvanceDetalleFecha extends Model
{
    use HasFactory;

    protected $table = 'avance_detalles_fechas';
    protected $primaryKey = 'id_avance_detalle_fecha';
    public $timestamps = true;

    protected $fillable = [
        'id_avance_detalle',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio_real',
        'fecha_fin_real',
        'fecha_evaluacion',
        'fecha_evaluacion_real'
    ];

    public function avanceDetalle()
    {
        return $this->belongsTo(AvanceDetalle::class, 'id_avance_detalle');
    }
}
