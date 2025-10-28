<?php

namespace App\Http\Controllers;

use App\Models\ActividadAprendizajeTema;
use Illuminate\Http\Request;

class ActividadAprendizajeTemaController extends Controller
{
    // Crear una actividad de aprendizaje
    public function createOne(Request $request)
    {
        $request->validate([
            'id_Tema' => 'required|exists:tema,id_Tema',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer'
        ]);

        $actividad = ActividadAprendizajeTema::create($request->all());

        return response()->json([
            'message' => 'Actividad de aprendizaje creada exitosamente',
            'data' => $actividad
        ], 201);
    }

    // Crear múltiples actividades de aprendizaje
    public function createMultiple(Request $request)
    {
        $request->validate([
            'actividades' => 'required|array',
            'actividades.*.id_Tema' => 'required|exists:tema,id_Tema',
            'actividades.*.descripcion' => 'required|string',
            'actividades.*.orden' => 'nullable|integer'
        ]);

        $actividades = [];
        foreach ($request->actividades as $actividadData) {
            $actividades[] = ActividadAprendizajeTema::create($actividadData);
        }

        return response()->json([
            'message' => 'Actividades de aprendizaje creadas exitosamente',
            'data' => $actividades
        ], 201);
    }

    // Actualizar una actividad de aprendizaje
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer'
        ]);

        $actividad = ActividadAprendizajeTema::findOrFail($id);
        $actividad->update($request->all());

        return response()->json([
            'message' => 'Actividad de aprendizaje actualizada exitosamente',
            'data' => $actividad
        ]);
    }

    // Actualizar múltiples actividades de aprendizaje
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'actividades' => 'required|array',
            'actividades.*.id_actividad' => 'required|exists:actividad_aprendizaje_tema,id_actividad',
            'actividades.*.descripcion' => 'sometimes|required|string',
            'actividades.*.orden' => 'nullable|integer'
        ]);

        $actividadesActualizadas = [];
        foreach ($request->actividades as $actividadData) {
            $actividad = ActividadAprendizajeTema::findOrFail($actividadData['id_actividad']);
            $actividad->update($actividadData);
            $actividadesActualizadas[] = $actividad;
        }

        return response()->json([
            'message' => 'Actividades de aprendizaje actualizadas exitosamente',
            'data' => $actividadesActualizadas
        ]);
    }

    // Eliminar una actividad de aprendizaje
    public function deleteOne($id)
    {
        $actividad = ActividadAprendizajeTema::findOrFail($id);
        $actividad->delete();

        return response()->json([
            'message' => 'Actividad de aprendizaje eliminada exitosamente'
        ]);
    }
}