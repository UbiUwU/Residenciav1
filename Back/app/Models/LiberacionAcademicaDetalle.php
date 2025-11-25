<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiberacionAcademicaDetalle extends Model
{
    protected $table = 'liberacion_academica_detalles';

    protected $primaryKey = 'id_detalle';

    public $incrementing = true;

    protected $keyType = 'integer';

    public $timestamps = false;

    protected $fillable = [
        'id_liberacion',
        'numero_actividad',
        'descripcion_actividad',
        'si',
        'no',
        'na',
    ];

    protected $attributes = [
        'si' => false,
        'no' => false,
        'na' => false,
    ];

    protected $casts = [
        'si' => 'boolean',
        'no' => 'boolean',
        'na' => 'boolean',
        'numero_actividad' => 'integer',
    ];

    /**
     * Relación con la liberación académica
     */
    public function liberacion(): BelongsTo
    {
        return $this->belongsTo(LiberacionAcademica::class, 'id_liberacion', 'id_liberacion');
    }

    /**
     * Scope para buscar por número de actividad
     */
    public function scopePorNumeroActividad($query, $numero_actividad)
    {
        return $query->where('numero_actividad', $numero_actividad);
    }

    /**
     * Scope para actividades con estado SI
     */
    public function scopeEstadoSi($query)
    {
        return $query->where('si', true);
    }

    /**
     * Scope para actividades con estado NO
     */
    public function scopeEstadoNo($query)
    {
        return $query->where('no', true);
    }

    /**
     * Scope para actividades con estado N/A
     */
    public function scopeEstadoNa($query)
    {
        return $query->where('na', true);
    }

    /**
     * Obtener el estado actual como string
     */
    public function getEstadoAttribute(): string
    {
        if ($this->si) {
            return 'SI';
        }
        if ($this->no) {
            return 'NO';
        }

        return 'N/A';
    }

    /**
     * Setter para el estado
     */
    public function setEstadoAttribute($value)
    {
        $this->si = false;
        $this->no = false;
        $this->na = false;

        switch (strtoupper($value)) {
            case 'SI':
                $this->si = true;
                break;
            case 'NO':
                $this->no = true;
                break;
            case 'N/A':
            default:
                $this->na = true;
                break;
        }
    }

    /**
     * Validar que solo un estado esté activo (se ejecuta automáticamente)
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->validarEstadoUnico();
        });
    }

    /**
     * Validar que solo un estado esté activo
     */
    public function validarEstadoUnico()
    {
        $estadosActivos = 0;
        if ($this->si) {
            $estadosActivos++;
        }
        if ($this->no) {
            $estadosActivos++;
        }
        if ($this->na) {
            $estadosActivos++;
        }

        if ($estadosActivos > 1) {
            throw new \Exception('Solo un estado (SI, NO, N/A) puede estar activo a la vez.');
        }

        // Si ningún estado está activo, establecer N/A por defecto
        if ($estadosActivos === 0) {
            $this->na = true;
        }
    }
}
