<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluacionController extends Controller
{
    // Crear evaluación
    public function store(Request $request)
    {
        $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'criterios_evaluacion' => 'required|string|max:255',
        ]);

        $clave = $request->input('clave_asignatura');
        $criterios = $request->input('criterios_evaluacion');

        $result = DB::select('SELECT crear_evaluacion(?, ?) AS id', [$clave, $criterios]);

        if (!empty($result) && isset($result[0]->id)) {
            return response()->json([
                'success' => true,
                'id_evaluacion' => $result[0]->id,
                'message' => 'Evaluación creada correctamente'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear evaluación'
        ], 500);
    }

    // Actualizar evaluación
    public function update(Request $request, $id)
    {
        $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'criterios_evaluacion' => 'required|string|max:255',
        ]);

        $clave = $request->input('clave_asignatura');
        $criterios = $request->input('criterios_evaluacion');

        try {
            DB::statement('SELECT actualizar_evaluacion(?, ?, ?)', [$id, $clave, $criterios]);
            return response()->json([
                'success' => true,
                'message' => 'Evaluación actualizada correctamente',
                'id_evaluacion' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar evaluación: ' . $e->getMessage()
            ], 500);
        }
    }

    // Eliminar evaluación
    public function destroy($id)
    {
        try {
            DB::statement('SELECT eliminar_evaluacion(?)', [$id]);
            return response()->json([
                'success' => true,
                'message' => 'Evaluación eliminada correctamente',
                'id_evaluacion' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar evaluación: ' . $e->getMessage()
            ], 500);
        }
    }

    // Consultar evaluación por ID
    public function show($id)
    {
        $evaluacion = DB::select('SELECT * FROM consultar_evaluacion_por_id(?)', [$id]);

        if (!empty($evaluacion)) {
            return response()->json([
                'success' => true,
                'evaluacion' => $evaluacion[0]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Evaluación no encontrada'
        ], 404);
    }
}
