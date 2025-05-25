<?php

// app/Models/HorarioMaestro.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioMaestro extends Model
{
    protected $table = 'horarios_maestros';

    protected $fillable = [
        'maestro_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    public $timestamps = false;
}
