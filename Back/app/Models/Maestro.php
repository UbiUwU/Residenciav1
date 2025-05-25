<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    use HasFactory;

    protected $table = 'maestros';
    protected $primaryKey = 'tarjeta';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;
    
    protected $fillable = [
        'tarjeta',
        'nombre',
        'apellidopaterno',
        'apellidomaterno',
        'idusuario',
        'rfc',
        'escolaridad_licenciatura',
        'estado_licenciatura',
        'escolaridad_especializacion',
        'estado_especializacion',
        'escolaridad_maestria',
        'estado_maestria',
        'escolaridad_doctorado',
        'estado_doctorado',
        'id_departamento'
    ];

    // Relación con departamento (si existe el modelo Departamento)
    /*
    public function departamento() {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }*/
}