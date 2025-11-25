<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActividadAprendizajeTema extends Model
{
    protected $table = 'actividad_aprendizaje_tema';

    protected $primaryKey = 'id_actividad';

    public $timestamps = false;

    protected $fillable = [
        'id_Tema',
        'descripcion',
        'orden',
    ];

    protected $casts = [
        'id_Tema' => 'integer',
    ];

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'id_Tema', 'id_Tema');
    }
}
