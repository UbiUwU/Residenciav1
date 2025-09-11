<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiberacionAcademica extends Model
{
    protected $table = 'liberacion_academica';
    protected $primaryKey = 'id_liberacion';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    protected $fillable = [
        'id_departamento',
        'tarjeta_maestro',
        'id_periodo_escolar',
        'fecha_liberacion',
        'otorga_liberacion'
    ];

    protected $attributes = [
        'otorga_liberacion' => false
    ];

    protected $casts = [
        'fecha_liberacion' => 'date:Y-m-d',
        'otorga_liberacion' => 'boolean',
        'tarjeta_maestro' => 'string'
    ];

    /**
     * Relación con el departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    /**
     * Relación con el maestro
     */
    public function maestro(): BelongsTo
    {
        return $this->belongsTo(Maestro::class, 'tarjeta_maestro', 'tarjeta');
    }

    /**
     * Relación con el período escolar
     */
    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class, 'id_periodo_escolar', 'id_periodo_escolar');
    }

    /**
     * Relación con los detalles de liberación académica
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(LiberacionAcademicaDetalle::class, 'id_liberacion', 'id_liberacion');
    }

    /**
     * Scope para buscar por departamento
     */
    public function scopePorDepartamento($query, $id_departamento)
    {
        return $query->where('id_departamento', $id_departamento);
    }

    /**
     * Scope para buscar por maestro
     */
    public function scopePorMaestro($query, $tarjeta_maestro)
    {
        return $query->where('tarjeta_maestro', $tarjeta_maestro);
    }

    /**
     * Scope para buscar por período escolar
     */
    public function scopePorPeriodo($query, $id_periodo_escolar)
    {
        return $query->where('id_periodo_escolar', $id_periodo_escolar);
    }

    /**
     * Scope para buscar liberaciones otorgadas
     */
    public function scopeOtorgadas($query)
    {
        return $query->where('otorga_liberacion', true);
    }

    /**
     * Scope para buscar liberaciones no otorgadas
     */
    public function scopeNoOtorgadas($query)
    {
        return $query->where('otorga_liberacion', false);
    }
}