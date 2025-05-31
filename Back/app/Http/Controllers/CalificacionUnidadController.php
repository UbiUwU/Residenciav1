<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CalificacionUnidadController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'id_carga_detalle' => 'required|integer|min:1',
            'unidad' => 'integer|min:1',
            'tipo_evaluacion' => 'string|max:30',
            'calificacion' => 'required|numeric|min:0|max:100',
            'ponderacion' => 'numeric|min:0.01|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        // Llamar a la función PostgreSQL
        $result = DB::select(
            'SELECT * FROM crear_calificacion(CAST(? AS bigint), CAST(? AS smallint), CAST(? AS varchar), CAST(? AS decimal), CAST(? AS decimal))',
            [
                $request->id_carga_detalle,
                $request->unidad,
                $request->tipo_evaluacion,
                $request->calificacion,
                $request->ponderacion
            ]
        );

        return response()->json(json_decode($result[0]->crear_calificacion));
    }

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
