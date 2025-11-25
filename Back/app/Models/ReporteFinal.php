<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReporteFinal extends Model
{
    protected $table = 'reportefinal';

    protected $primaryKey = 'id_reportefinal';

    public $incrementing = true;

    protected $keyType = 'integer';

    public $timestamps = false;

    protected $fillable = [
        'tarjeta_profesor',
        'id_periodo_escolar',
        'id_departamento',
        'estado',
    ];

    protected $attributes = [
        'estado' => 'borrador',
    ];

    protected $casts = [
        'tarjeta_profesor' => 'integer',
        'id_periodo_escolar' => 'integer',
        'id_departamento' => 'integer',
    ];

    /**
     * Relación con el maestro (profesor)
     */
    public function maestro(): BelongsTo
    {
        return $this->belongsTo(Maestro::class, 'tarjeta_profesor', 'tarjeta');
    }

    /**
     * Relación con el período escolar
     */
    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class, 'id_periodo_escolar', 'id_periodo_escolar');
    }

    /**
     * Relación con el departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    /**
     * Relación con los datos estáticos del reporte
     */
    public function datosEstaticos()
    {
        return $this->hasOne(DatosEstaticosReporteFinal::class, 'id_reportefinal', 'id_reportefinal');
    }

    /**
     * Relación con las asignaturas del reporte
     */
    public function asignaturas()
    {
        return $this->hasMany(ReporteFinalAsignatura::class, 'id_reportefinal', 'id_reportefinal');
    }
}
