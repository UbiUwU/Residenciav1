<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogoTiposFecha extends Model
{
    protected $table = 'catalogo_tipos_fecha';
    protected $primaryKey = 'id_tipo_fecha';
    public $timestamps = false;

    protected $fillable = [
        'clave',
        'nombre',
        'descripcion',
        'es_activo',
    ];

    public function fechasClave(): HasMany
    {
        return $this->hasMany(FechasClavePeriodo::class, 'id_tipo_fecha', 'id_tipo_fecha');
    }
}