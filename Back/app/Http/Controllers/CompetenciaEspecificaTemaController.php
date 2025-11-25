<?php

namespace App\Http\Controllers;

use App\Models\CompetenciaEspecificaTema;
use Illuminate\Http\Request;

class CompetenciaEspecificaTemaController extends Controller
{
    // Crear una competencia específica
    public function createOne(Request $request)
    {
        $request->validate([
            'id_Tema' => 'required|exists:tema,id_Tema',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer',
        ]);

        $competencia = CompetenciaEspecificaTema::create($request->all());

        return response()->json([
            'message' => 'Competencia específica creada exitosamente',
            'data' => $competencia,
        ], 201);
    }

    // Crear múltiples competencias específicas
    public function createMultiple(Request $request)
    {
        $request->validate([
            'competencias' => 'required|array',
            'competencias.*.id_Tema' => 'required|exists:tema,id_Tema',
            'competencias.*.descripcion' => 'required|string',
            'competencias.*.orden' => 'nullable|integer',
        ]);

        $competencias = [];
        foreach ($request->competencias as $competenciaData) {
            $competencias[] = CompetenciaEspecificaTema::create($competenciaData);
        }

        return response()->json([
            'message' => 'Competencias específicas creadas exitosamente',
            'data' => $competencias,
        ], 201);
    }

    // Actualizar una competencia específica
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer',
        ]);

        $competencia = CompetenciaEspecificaTema::findOrFail($id);
        $competencia->update($request->all());

        return response()->json([
            'message' => 'Competencia específica actualizada exitosamente',
            'data' => $competencia,
        ]);
    }

    // Actualizar múltiples competencias específicas
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'competencias' => 'required|array',
            'competencias.*.id_competencia_especifica' => 'required|exists:competencia_especifica_tema,id_competencia_especifica',
            'competencias.*.descripcion' => 'sometimes|required|string',
            'competencias.*.orden' => 'nullable|integer',
        ]);

        $competenciasActualizadas = [];
        foreach ($request->competencias as $competenciaData) {
            $competencia = CompetenciaEspecificaTema::findOrFail($competenciaData['id_competencia_especifica']);
            $competencia->update($competenciaData);
            $competenciasActualizadas[] = $competencia;
        }

        return response()->json([
            'message' => 'Competencias específicas actualizadas exitosamente',
            'data' => $competenciasActualizadas,
        ]);
    }

    // Eliminar una competencia específica
    public function deleteOne($id)
    {
        $competencia = CompetenciaEspecificaTema::findOrFail($id);
        $competencia->delete();

        return response()->json([
            'message' => 'Competencia específica eliminada exitosamente',
        ]);
    }
}
