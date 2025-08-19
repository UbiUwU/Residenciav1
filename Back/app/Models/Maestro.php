<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    use HasFactory;

    protected $table = 'maestros';
    protected $primaryKey = 'tarjeta';
    public $incrementing = true;      // bigserial
    protected $keyType = 'int';
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

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idusuario', 'idusuario');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    // Si tienes el modelo Comision, puedes habilitar esta relación:
    // public function comisiones()
    // {
    //     return $this->belongsToMany(
    //         Comision::class,
    //         'comision_maestro',
    //         'tarjeta_maestro',   // FK en la pivote hacia maestro
    //         'id_comision',       // FK en la pivote hacia comision
    //         'tarjeta',           // local key maestro
    //         'id_comision'        // local key comision
    //     );
    // }
}
