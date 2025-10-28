<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargaAcademicaGeneral extends Model
{
    protected $table = 'carga_academica_general';
    protected $primaryKey = 'idcargageneral';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'numerocontrol'
    ];

    /**
     * Relación: Una carga académica general pertenece a un alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'numerocontrol', 'numerocontrol');
    }

    /**
     * Relación: Una carga académica general tiene muchos detalles
     */
    public function detalles()
    {
        return $this->hasMany(CargaAcademicaDetalle::class, 'idcargageneral', 'idcargageneral');
    }
}
