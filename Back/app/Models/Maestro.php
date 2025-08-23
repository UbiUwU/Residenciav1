<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public function scopeSoloNombre($query)
    {
        return $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
    }

    // En tu modelo Maestro
    public function scopeInfoBasicaMaestros($query)
    {
        return $query->select(
            'maestros.tarjeta',
            'maestros.nombre',
            'maestros.apellidopaterno',
            'maestros.apellidomaterno',
            'departamentos.id_departamento',
            'departamentos.nombre as departamento_nombre'
        )->leftJoin('departamentos', 'maestros.id_departamento', '=', 'departamentos.id_departamento');
    }
    public function reportesFinales()
    {
        return $this->hasMany(ReporteFinal::class, 'tarjeta_profesor', 'tarjeta');
    }
    public function liberacionesDocentes(): HasMany
    {
        return $this->hasMany(LiberacionDocente::class, 'tarjeta_maestro', 'tarjeta');
    }
    public function liberacionesAcademicas(): HasMany
    {
        return $this->hasMany(LiberacionAcademica::class, 'tarjeta_maestro', 'tarjeta');
    }
}
