<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresentacionIntencion extends Model
{
    protected $table = 'presentacion_intencion';
    protected $primaryKey = 'id_Intencion';
    public $timestamps = false;
    protected $fillable = [
        'id_Intencion', 
        'id_Presentacion',
        'Orden',
        'Tema',
        'Descripcion'
    ];

    public function presentacion(): BelongsTo
    {
        return $this->belongsTo(Presentacion::class, 'id_Presentacion');
    }
}