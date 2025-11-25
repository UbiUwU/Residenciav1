<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuentesInformacion extends Model
{
    protected $table = 'fuentes_informacion';

    protected $primaryKey = 'id_fuente';

    public $timestamps = false;

    protected $fillable = [
        'ClaveAsignatura',
        'descripcion',
        'orden',
    ];

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'ClaveAsignatura', 'ClaveAsignatura');
    }
}
