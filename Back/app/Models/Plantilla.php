<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;

    protected $table = 'plantillas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'archivo',
        'tipo_id',
        'estado_id',
        'periodo_escolar_id',
        'creado_en',
        'actualizado_en',
    ];

    protected $casts = [
        'id' => 'integer',
        'tipo_id' => 'integer',
        'estado_id' => 'integer',
        'periodo_escolar_id' => 'integer',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    public $timestamps = false;

    // Boot method para actualizar automáticamente actualizado_en
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            $model->actualizado_en = now();
        });
    }

    // Relación con tipo de plantilla
    public function tipo()
    {
        return $this->belongsTo(TipoPlantilla::class, 'tipo_id');
    }

    // Relación con estado de plantilla
    public function estado()
    {
        return $this->belongsTo(EstadoPlantilla::class, 'estado_id');
    }

    // Relación con periodo escolar
    public function periodoEscolar()
    {
        return $this->belongsTo(PeriodoEscolar::class, 'periodo_escolar_id');
    }

    // Scope para plantillas activas
    public function scopeActivas($query)
    {
        return $query->where('estado_id', 1); // Asumiendo que 1 es el estado activo
    }

    // Scope para plantillas por tipo
    public function scopePorTipo($query, $tipoId)
    {
        return $query->where('tipo_id', $tipoId);
    }

    // Scope para plantillas por periodo escolar
    public function scopePorPeriodo($query, $periodoId)
    {
        return $query->where('periodo_escolar_id', $periodoId);
    }

    // Método para verificar si la plantilla está activa
    public function estaActiva()
    {
        return $this->estado_id === 1;
    }

    // Método para obtener la ruta completa del archivo
    public function getRutaArchivoAttribute()
    {
        return storage_path('app/plantillas/'.$this->archivo);
    }

    // Método para obtener la extensión del archivo
    public function getExtensionAttribute()
    {
        return pathinfo($this->archivo, PATHINFO_EXTENSION);
    }
}
