<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetenciaEspecificaTema extends Model
{
    protected $table = 'competencia_especifica_tema';
    protected $primaryKey = 'id_competencia_especifica';
    public $timestamps = false;

    protected $fillable = [
        'id_Tema',
        'descripcion',
        'orden'
    ];
    protected $casts = [
        'id_Tema' => 'integer'
    ];

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'id_Tema', 'id_Tema');
    }
}