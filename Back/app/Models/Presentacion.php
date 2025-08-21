<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presentacion extends Model
{
    protected $table = 'presentacion';
    protected $primaryKey = 'id_Presentacion';

    protected $fillable = [
        'Clave_Asignatura'
    ];

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'Clave_Asignatura', 'ClaveAsignatura');
    }

    public function caracterizaciones(): HasMany
    {
        return $this->hasMany(PresentacionCaracterizacion::class, 'id_Presentacion');
    }

    public function intenciones(): HasMany
    {
        return $this->hasMany(PresentacionIntencion::class, 'id_Presentacion');
    }
}