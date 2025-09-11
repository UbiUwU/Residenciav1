<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Practiasasignatura extends Model
{
    protected $table = 'practias_asignatura';
    protected $primaryKey = 'id_practicas';
    public $timestamps = false;

    protected $fillable = [
        'ClaveAsignatura',
        'descripcion',
        'orden'
    ];

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'ClaveAsignatura', 'ClaveAsignatura');
    }
}