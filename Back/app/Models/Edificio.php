<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edificio extends Model
{
    protected $table = 'edificios';
    protected $primaryKey = 'claveedificio';
    public $incrementing = false; // PK es string
    protected $keyType = 'string'; // <-- agregado
    public $timestamps = false;

    protected $fillable = [
        'claveedificio',
        'nombre',
        'descripcion',
    ];
}

