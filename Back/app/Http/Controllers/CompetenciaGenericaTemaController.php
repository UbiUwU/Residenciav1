<?php

namespace App\Http\Controllers;

use App\Models\CompetenciaGenericaTema;
use Illuminate\Http\Request;

class CompetenciaGenericaTemaController extends Controller
{
    // Crear una competencia genérica
    public function createOne(Request $request)
    {
        $request->validate([
            'id_Tema' => 'required|exists:tema,id_Tema',
            'descripcion' => 'required|string',
            'nivel' => 'nullable|string|max:50',
            'orden' => 'nullable|integer',
        ]);

        $competencia = CompetenciaGenericaTema::create($request->all());

        return response()->json([
            'message' => 'Competencia genérica creada exitosamente',
            'data' => $competencia,
        ], 201);
    }

    // Crear múltiples competencias genéricas
    public function createMultiple(Request $request)
    {
        $request->validate([
            'competencias' => 'required|array',
            'competencias.*.id_Tema' => 'required|exists:tema,id_Tema',
            'competencias.*.descripcion' => 'required|string',
            'competencias.*.nivel' => 'nullable|string|max:50',
            'competencias.*.orden' => 'nullable|integer',
        ]);

        $competencias = [];
        foreach ($request->competencias as $competenciaData) {
            $competencias[] = CompetenciaGenericaTema::create($competenciaData);
        }

        return response()->json([
            'message' => 'Competencias genéricas creadas exitosamente',
            'data' => $competencias,
        ], 201);
    }

    // Actualizar una competencia genérica
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'nivel' => 'nullable|string|max:50',
            'orden' => 'nullable|integer',
        ]);

        $competencia = CompetenciaGenericaTema::findOrFail($id);
        $competencia->update($request->all());

        return response()->json([
            'message' => 'Competencia genérica actualizada exitosamente',
            'data' => $competencia,
        ]);
    }

    // Actualizar múltiples competencias genéricas
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'competencias' => 'required|array',
            'competencias.*.id_competencia_generica' => 'required|exists:competencia_generica_tema,id_competencia_generica',
            'competencias.*.descripcion' => 'sometimes|required|string',
            'competencias.*.nivel' => 'nullable|string|max:50',
            'competencias.*.orden' => 'nullable|integer',
        ]);

        $competenciasActualizadas = [];
        foreach ($request->competencias as $competenciaData) {
            $competencia = CompetenciaGenericaTema::findOrFail($competenciaData['id_competencia_generica']);
            $competencia->update($competenciaData);
            $competenciasActualizadas[] = $competencia;
        }

        return response()->json([
            'message' => 'Competencias genéricas actualizadas exitosamente',
            'data' => $competenciasActualizadas,
        ]);
    }

    // Eliminar una competencia genérica
    public function deleteOne($id)
    {
        $competencia = CompetenciaGenericaTema::findOrFail($id);
        $competencia->delete();

        return response()->json([
            'message' => 'Competencia genérica eliminada exitosamente',
        ]);
    }
}
