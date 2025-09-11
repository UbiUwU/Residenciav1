<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competencia extends Model
{
    protected $table = 'competencia';
    protected $primaryKey = 'id_Competencia';
    public $timestamps = false;

    protected $fillable = [
        'ClaveAsignatura',
        'Descripcion',
        'Tipo_Competencia'
    ];

    protected $casts = [
        'Tipo_Competencia' => 'string'
    ];

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'ClaveAsignatura', 'ClaveAsignatura');
    }

    // En el modelo Competencia
public function scopeGenericasOEspecificas($query)
{
    return $query->where(function($q) {
        $q->whereRaw("'Generica' = ANY(\"Tipo_Competencia\")")
          ->orWhereRaw("'Específica' = ANY(\"Tipo_Competencia\")");
    });
}
}