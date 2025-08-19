<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'idrol';
    public $incrementing = false; // Porque idrol es BIGINT y no autoincremental
    public $timestamps = false;

    protected $fillable = [
        'idrol',
        'nombre',
    ];

    // Relación con usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'idrol', 'idrol');
    }
}
