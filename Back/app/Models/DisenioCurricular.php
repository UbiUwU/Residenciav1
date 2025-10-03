<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisenioCurricular extends Model
{
    protected $table = 'disenio_curricular';
    protected $primaryKey = 'id_disenio_curricular';
    public $timestamps = false;

    protected $fillable = [
        'id_disenio_curricular',
        'ClaveAsignatura',
        'Lugar',
        'FechaInicio',
        'FechaFinal',
        'NombreEvento',
        'Descripcion'
    ];

    protected $casts = [
        'FechaInicio' => 'date',
        'FechaFinal' => 'date'
    ];

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'ClaveAsignatura', 'ClaveAsignatura');
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(DisenioCurricularParticipante::class, 'id_disenio_curricular');
    }
}