<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnumController extends Controller
{
    public function getValores($tipo)
    {
        // Validamos que solo se consulten los tipos permitidos
        $tiposPermitidos = ['estado_enum', 'origen_enum'];

        if (!in_array($tipo, $tiposPermitidos)) {
            return response()->json([
                'message' => 'Tipo de ENUM no permitido'
            ], 400);
        }

        // Consulta PostgreSQL para obtener los valores del ENUM
        $valores = DB::select("SELECT unnest(enum_range(NULL::$tipo)) AS valor");

        // Devolver en JSON
        return response()->json([
            'tipo' => $tipo,
            'valores' => array_map(fn($v) => $v->valor, $valores)
        ]);
    }
}
