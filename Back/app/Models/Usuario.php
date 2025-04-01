<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'IdUsuario';
    public $timestamps = false;

    protected $fillable = [
        'Correo',
        'Password',
        'Token',
        'IdRol'
    ];
}