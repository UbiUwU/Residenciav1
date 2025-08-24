<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyectoAsignatura extends Model
{
    protected $table = 'proyecto_asignatura';
    protected $primaryKey = 'id_proyecto';
    public $timestamps = false;

    protected $fillable = [
        'ClaveAsignatura',
        'descripcion',
        'orden'
    ];

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'ClaveAsignatura', 'ClaveAsignatura');
    }
}