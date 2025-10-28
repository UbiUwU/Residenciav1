<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPlantilla extends Model
{
    use HasFactory;

    protected $table = 'estados_plantilla';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    public $timestamps = false;

    // Relación con plantillas
    public function plantillas()
    {
        return $this->hasMany(Plantilla::class, 'estado_id');
    }
}