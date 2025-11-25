<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    protected $table = 'tipo_evento';

    protected $primaryKey = 'id_tipo_evento';

    public $timestamps = false; // No tenemos created_at/updated_at

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    // Relación con comisiones
    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'id_tipo_evento');
    }
}
