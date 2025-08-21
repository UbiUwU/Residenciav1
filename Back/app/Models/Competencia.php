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
        'Clave_Asignatura',
        'Descripcion',
        'Tipo_Competencia',
    ];

    /**
     * Relación con Asignatura
     */
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'Clave_Asignatura', 'ClaveAsignatura');
    }
}
