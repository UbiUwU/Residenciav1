<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuenteInformacionController extends Controller
{
    // Crear fuente de información
    public function store(Request $request)
    {
        $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'referencia_fuente_info' => 'required|string|max:255',
        ]);

        $clave = $request->input('clave_asignatura');
        $referencia = $request->input('referencia_fuente_info');

        $result = DB::select('SELECT crear_fuente_informacion(?, ?) AS id', [$clave, $referencia]);

        if (! empty($result) && isset($result[0]->id)) {
            return response()->json([
                'success' => true,
                'id_fuente_info' => $result[0]->id,
                'message' => 'Fuente de información creada correctamente',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear fuente de información',
        ], 500);
    }

    // Actualizar fuente de información
    public function update(Request $request, $id)
    {
        $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'referencia_fuente_info' => 'required|string|max:255',
        ]);

        $clave = $request->input('clave_asignatura');
        $referencia = $request->input('referencia_fuente_info');

        try {
            DB::statement('SELECT actualizar_fuente_informacion(?, ?, ?)', [$id, $clave, $referencia]);

            return response()->json([
                'success' => true,
                'message' => 'Fuente de información actualizada correctamente',
                'id_fuente_info' => $id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar fuente de información: '.$e->getMessage(),
            ], 500);
        }
    }

    // Eliminar fuente de información
    public function destroy($id)
    {
        try {
            DB::statement('SELECT eliminar_fuente_informacion(?)', [$id]);

            return response()->json([
                'success' => true,
                'message' => 'Fuente de información eliminada correctamente',
                'id_fuente_info' => $id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar fuente de información: '.$e->getMessage(),
            ], 500);
        }
    }

    // Consultar fuente de información por ID
    public function show($id)
    {
        $fuente = DB::select('SELECT * FROM consultar_fuente_informacion_por_id(?)', [$id]);

        if (! empty($fuente)) {
            return response()->json([
                'success' => true,
                'fuente_informacion' => $fuente[0],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Fuente de información no encontrada',
        ], 404);
    }
}
