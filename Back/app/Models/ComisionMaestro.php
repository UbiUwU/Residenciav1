<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ComisionMaestro extends Pivot
{
    protected $table = 'comision_maestro';

    public $timestamps = false;

    protected $fillable = [
        'id_comision',
        'tarjeta_maestro',
    ];
}
