<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
    protected $table = 'avance';

    protected $primaryKey = 'id_avance';

    public $timestamps = false; // Usamos campos personalizados, no el default de Laravel

    protected $fillable = [
        'clave_asignatura',
        'tarjeta_profesor',
        'id_periodo_escolar',
        'fecha_creacion',
        'fecha_ultima_actualizacion',
        'firma_profesor',
        'firma_jefe_carrera',
        'requiere_firma_jefe',
        'estado',
        'clave_horario',
    ];

    // === Relaciones ===

    // Relación con asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'clave_asignatura', 'ClaveAsignatura');
    }

    // Relación con maestro (profesor)
    public function profesor()
    {
        return $this->belongsTo(Maestro::class, 'tarjeta_profesor', 'tarjeta');
    }

    // Relación con periodo escolar
    public function periodoEscolar()
    {
        return $this->belongsTo(PeriodoEscolar::class, 'id_periodo_escolar', 'id_periodo_escolar');
    }

    // Relación con horario de asignatura
    public function horario()
    {
        return $this->belongsTo(HorarioAsignaturaMaestro::class, 'clave_horario', 'clavehorario');
    }

    // Ejemplo de relación con detalle de avances (si existe la tabla avance_detalle)
    public function detalles()
    {
        return $this->hasMany(AvanceDetalle::class, 'id_avance', 'id_avance');
    }

    public function fechas()
    {
        return $this->hasMany(AvanceFecha::class, 'id_avance');
    }

    // Relación anidada: detalles con sus fechas
    public function detallesConFechas()
    {
        return $this->detalles()->with('fechas'); // 'fechas' es la relación en AvanceDetalle
    }
}
