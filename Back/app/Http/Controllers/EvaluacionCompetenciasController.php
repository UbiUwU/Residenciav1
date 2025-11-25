<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionCompetencias;
use Illuminate\Http\Request;

class EvaluacionCompetenciasController extends Controller
{
    // Crear una evaluación por competencias
    public function createOne(Request $request)
    {
        $request->validate([
            'ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer',
        ]);

        $evaluacion = EvaluacionCompetencias::create($request->all());

        return response()->json([
            'message' => 'Evaluación por competencias creada exitosamente',
            'data' => $evaluacion,
        ], 201);
    }

    // Crear múltiples evaluaciones por competencias
    public function createMultiple(Request $request)
    {
        $request->validate([
            'evaluaciones' => 'required|array',
            'evaluaciones.*.ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'evaluaciones.*.descripcion' => 'required|string',
            'evaluaciones.*.orden' => 'nullable|integer',
        ]);

        $evaluaciones = [];
        foreach ($request->evaluaciones as $evaluacionData) {
            $evaluaciones[] = EvaluacionCompetencias::create($evaluacionData);
        }

        return response()->json([
            'message' => 'Evaluaciones por competencias creadas exitosamente',
            'data' => $evaluaciones,
        ], 201);
    }

    // Actualizar una evaluación por competencias
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer',
        ]);

        $evaluacion = EvaluacionCompetencias::findOrFail($id);
        $evaluacion->update($request->all());

        return response()->json([
            'message' => 'Evaluación por competencias actualizada exitosamente',
            'data' => $evaluacion,
        ]);
    }

    // Actualizar múltiples evaluaciones por competencias
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'evaluaciones' => 'required|array',
            'evaluaciones.*.id_evaluacion' => 'required|exists:evaluacion_competencias,id_evaluacion',
            'evaluaciones.*.descripcion' => 'sometimes|required|string',
            'evaluaciones.*.orden' => 'nullable|integer',
        ]);

        $evaluacionesActualizadas = [];
        foreach ($request->evaluaciones as $evaluacionData) {
            $evaluacion = EvaluacionCompetencias::findOrFail($evaluacionData['id_evaluacion']);
            $evaluacion->update($evaluacionData);
            $evaluacionesActualizadas[] = $evaluacion;
        }

        return response()->json([
            'message' => 'Evaluaciones por competencias actualizadas exitosamente',
            'data' => $evaluacionesActualizadas,
        ]);
    }

    // Eliminar una evaluación por competencias
    public function deleteOne($id)
    {
        $evaluacion = EvaluacionCompetencias::findOrFail($id);
        $evaluacion->delete();

        return response()->json([
            'message' => 'Evaluación por competencias eliminada exitosamente',
        ]);
    }
}
