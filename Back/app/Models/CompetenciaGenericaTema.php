<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetenciaGenericaTema extends Model
{
    protected $table = 'competencia_generica_tema';

    protected $primaryKey = 'id_competencia_generica';

    public $timestamps = false;

    protected $fillable = [
        'id_Tema',
        'descripcion',
        'nivel',
        'orden',
    ];

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'id_Tema', 'id_Tema');
    }
}
