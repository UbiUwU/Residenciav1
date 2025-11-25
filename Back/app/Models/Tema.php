<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tema extends Model
{
    protected $table = 'tema';

    protected $primaryKey = 'id_Tema';

    public $timestamps = false;

    protected $fillable = [
        'Clave_Asignatura',
        'Numero',
        'Nombre_Tema',
    ];

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'Clave_Asignatura', 'ClaveAsignatura');
    }

    public function subtemas(): HasMany
    {
        return $this->hasMany(Subtema::class, 'Tema_id', 'id_Tema')
            ->whereNull('Subtema_Padre_id')
            ->orderBy('Orden');
    }

    public function subtemasRecursivos(): HasMany
    {
        return $this->subtemas()->with('hijosRecursivos');
    }

    public function competenciasGenericas(): HasMany
    {
        return $this->hasMany(CompetenciaGenericaTema::class, 'id_Tema', 'id_Tema')
            ->orderBy('orden');
    }

    public function competenciasEspecificas(): HasMany
    {
        return $this->hasMany(CompetenciaEspecificaTema::class, 'id_Tema', 'id_Tema')
            ->orderBy('orden');
    }

    public function actividadesAprendizaje(): HasMany
    {
        return $this->hasMany(ActividadAprendizajeTema::class, 'id_Tema', 'id_Tema')
            ->orderBy('orden');
    }
}
