<?php

// app/Models/Plantilla.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'archivo', 'tipo', 'estado'
    ];
}
