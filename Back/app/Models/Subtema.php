<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subtema extends Model
{
    protected $table = 'subtema';

    protected $primaryKey = 'id_Subtema';

    public $timestamps = false;

    protected $fillable = [
        'Tema_id',
        'Subtema_Padre_id',
        'Nombre_Subtema',
        'Orden',
        'Nivel',
    ];

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'Tema_id', 'id_Tema');
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Subtema::class, 'Subtema_Padre_id', 'id_Subtema');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(Subtema::class, 'Subtema_Padre_id', 'id_Subtema')
            ->orderBy('Orden');
    }

    public function hijosRecursivos(): HasMany
    {
        return $this->hijos()->with('hijosRecursivos');
    }

    public function scopeRaices($query)
    {
        return $query->whereNull('Subtema_Padre_id');
    }

    public function scopePorNivel($query, $nivel)
    {
        return $query->where('Nivel', $nivel);
    }

    public function getRutaCompletaAttribute(): string
    {
        $ruta = $this->Nombre_Subtema;
        $padre = $this->padre;

        while ($padre) {
            $ruta = $padre->Nombre_Subtema.' → '.$ruta;
            $padre = $padre->padre;
        }

        return $ruta;
    }

    public function getAncestros()
    {
        $ancestros = collect();
        $actual = $this->padre;

        while ($actual) {
            $ancestros->push($actual);
            $actual = $actual->padre;
        }

        return $ancestros->reverse();
    }

    public function getDescendientes()
    {
        $descendientes = collect();

        foreach ($this->hijos as $hijo) {
            $descendientes->push($hijo);
            $descendientes = $descendientes->merge($hijo->getDescendientes());
        }

        return $descendientes;
    }
}
