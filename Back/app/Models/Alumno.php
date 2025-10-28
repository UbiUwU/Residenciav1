<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';
    protected $primaryKey = 'numerocontrol';
    public $incrementing = true; 
    public $timestamps = false; // La tabla no tiene created_at / updated_at

    protected $fillable = [
        'nombre',
        'apellidopaterno',
        'apellidomaterno',
        'idusuario',
        'clavecarrera'
    ];

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idusuario', 'idusuario');
    }

    /**
     * Relación con Carrera (si tienes una tabla carreras)
     */
    public function cargasGenerales()
    {
        return $this->hasMany(CargaAcademicaGeneral::class, 'numerocontrol', 'numerocontrol');
    }
}
