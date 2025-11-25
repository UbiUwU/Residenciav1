<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApoyoDidacticoInstrumentacion extends Model
{
    protected $table = 'apoyos_didacticos_instrumentacion';

    protected $primaryKey = 'id_apoyo_didactico';

    public $timestamps = false;

    protected $fillable = [
        'id_instrumentacion',
        'descripcion',
        'orden',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function instrumentacion(): BelongsTo
    {
        return $this->belongsTo(Instrumentacion::class, 'id_instrumentacion', 'id_instrumentacion');
    }
}
