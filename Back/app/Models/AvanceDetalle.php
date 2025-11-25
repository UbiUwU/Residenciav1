<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvanceDetalle extends Model
{
    use HasFactory;

    protected $table = 'avance_detalles';

    protected $primaryKey = 'id_avance_detalle';

    public $timestamps = true; // usa created_at y updated_at

    protected $fillable = [
        'id_avance',
        'id_tema',
        'porcentaje_aprobacion',
        'requiere_firma_docente',
        'observaciones',
    ];

    public function avance()
    {
        return $this->belongsTo(Avance::class, 'id_avance', 'id_avance');
    }

    public function tema()
    {
        return $this->belongsTo(Tema::class, 'id_tema', 'id_Tema');
    }

    public function fechas()
    {
        return $this->hasMany(AvanceDetalleFecha::class, 'id_avance_detalle');
    }
}
