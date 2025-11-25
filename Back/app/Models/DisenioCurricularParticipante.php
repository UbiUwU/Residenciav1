<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisenioCurricularParticipante extends Model
{
    protected $table = 'disenio_curricular_participantes';

    protected $primaryKey = 'IdParticipacion';

    public $timestamps = false;

    protected $fillable = [
        'IdParticipacion',
        'id_disenio_curricular',
        'Instituto',
    ];

    public function disenioCurricular(): BelongsTo
    {
        return $this->belongsTo(DisenioCurricular::class, 'id_disenio_curricular');
    }
}
