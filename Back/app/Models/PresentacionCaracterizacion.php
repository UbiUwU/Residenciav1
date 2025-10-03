<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresentacionCaracterizacion extends Model
{
    protected $table = 'presentacion_caracterizacion';
    protected $primaryKey = 'id_Caracterizacion';
    public $timestamps = false;
    protected $fillable = [
        'id_Caracterizacion', 
        'id_Presentacion',
        'Orden',
        'Punto'
    ];

    public function presentacion(): BelongsTo
    {
        return $this->belongsTo(Presentacion::class, 'id_Presentacion');
    }
}