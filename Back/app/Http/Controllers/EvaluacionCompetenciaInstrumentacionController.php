<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionCompetenciaInstrumentacion;
use Illuminate\Http\Request;

class EvaluacionCompetenciaInstrumentacionController extends Controller
{
    // Crear una evaluación de competencia
    public function createOne(Request $request)
    {
        $request->validate([
            'id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'evidencia_aprendizaje' => 'required|string',
            'evaluacion_formativa' => 'required|string',
            'porcentaje_valor' => 'nullable|integer|min:0|max:100'
        ]);

        $evaluacion = EvaluacionCompetenciaInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Evaluación de competencia creada exitosamente',
            'data' => $evaluacion
        ], 201);
    }

    // Crear múltiples evaluaciones de competencia
    public function createMultiple(Request $request)
    {
        $request->validate([
            'evaluaciones' => 'required|array',
            'evaluaciones.*.id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'evaluaciones.*.evidencia_aprendizaje' => 'required|string',
            'evaluaciones.*.evaluacion_formativa' => 'required|string',
            'evaluaciones.*.porcentaje_valor' => 'nullable|integer|min:0|max:100'
        ]);

        $evaluaciones = [];
        foreach ($request->evaluaciones as $evaluacionData) {
            $evaluaciones[] = EvaluacionCompetenciaInstrumentacion::create($evaluacionData);
        }

        return response()->json([
            'message' => 'Evaluaciones de competencia creadas exitosamente',
            'data' => $evaluaciones
        ], 201);
    }

    // Actualizar una evaluación de competencia
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'evidencia_aprendizaje' => 'sometimes|required|string',
            'evaluacion_formativa' => 'sometimes|required|string',
            'porcentaje_valor' => 'nullable|integer|min:0|max:100'
        ]);

        $evaluacion = EvaluacionCompetenciaInstrumentacion::findOrFail($id);
        $evaluacion->update($request->all());

        return response()->json([
            'message' => 'Evaluación de competencia actualizada exitosamente',
            'data' => $evaluacion
        ]);
    }

    // Actualizar múltiples evaluaciones de competencia
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'evaluaciones' => 'required|array',
            'evaluaciones.*.id_evaluacion_competencia' => 'required|exists:evaluacion_competencias_instrumentacion,id_evaluacion_competencia',
            'evaluaciones.*.evidencia_aprendizaje' => 'sometimes|required|string',
            'evaluaciones.*.evaluacion_formativa' => 'sometimes|required|string',
            'evaluaciones.*.porcentaje_valor' => 'nullable|integer|min:0|max:100'
        ]);

        $evaluacionesActualizadas = [];
        foreach ($request->evaluaciones as $evaluacionData) {
            $evaluacion = EvaluacionCompetenciaInstrumentacion::findOrFail($evaluacionData['id_evaluacion_competencia']);
            $evaluacion->update($evaluacionData);
            $evaluacionesActualizadas[] = $evaluacion;
        }

        return response()->json([
            'message' => 'Evaluaciones de competencia actualizadas exitosamente',
            'data' => $evaluacionesActualizadas
        ]);
    }
}