<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $primaryKey = 'id_departamento';

    public $timestamps = false;

    protected $fillable = [
        'id_departamento',
        'nombre',
        'abreviacion',
    ];

    public function scopeInfoBasicaDepartamento($query)
    {
        return $query->select('id_departamento', 'nombre');
    }

    public function reportesFinales()
    {
        return $this->hasMany(ReporteFinal::class, 'id_departamento', 'id_departamento');
    }

    public function liberacionesDocentes(): HasMany
    {
        return $this->hasMany(LiberacionDocente::class, 'id_departamento', 'id_departamento');
    }

    public function liberacionesAcademicas(): HasMany
    {
        return $this->hasMany(LiberacionAcademica::class, 'id_departamento', 'id_departamento');
    }
}
