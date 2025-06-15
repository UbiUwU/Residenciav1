<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CalificacionUnidadController extends Controller
{

    public function getDetalleGruposPorCarrera($tarjeta)
    {
        // Validar que la tarjeta sea numérica positiva
        if (!is_numeric($tarjeta) || $tarjeta <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Tarjeta inválida'], 400);
        }

        // Llamar a la función PostgreSQL
        $result = DB::select('SELECT * FROM get_Detalle_Grupos_Calificacion_por_Carrera(CAST(? AS bigint))', [$tarjeta]);

        if (empty($result)) {
            return response()->json(['status' => 'error', 'message' => 'Sin resultados'], 404);
        }

        $json = $result[0]->get_detalle_grupos_calificacion_por_carrera;

        return response()->json(json_decode($json));
    }

}
