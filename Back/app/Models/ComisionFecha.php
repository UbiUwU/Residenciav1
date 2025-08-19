<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComisionFecha extends Model
{
    protected $table = 'comision_fecha';
    protected $primaryKey = 'id_comision_fecha';
    public $timestamps = false;

    protected $fillable = [
        'id_comision',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'duracion',
        'observaciones',
    ];

    public function comision()
    {
        return $this->belongsTo(Comision::class, 'id_comision');
    }
}
