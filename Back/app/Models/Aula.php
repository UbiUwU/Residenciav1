<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $table = 'aulas';
    protected $primaryKey = 'claveaula';
    public $incrementing = false; // claveaula es string
    protected $keyType = 'string'; // <-- agregado
    public $timestamps = false;

    protected $fillable = [
        'claveaula',
        'claveedificio',
        'nombre',
        'cantidadcomputadoras',
        'horadisponible',
        'estado',
    ];

    public function edificio()
    {
        return $this->belongsTo(Edificio::class, 'claveedificio', 'claveedificio');
    }
}

