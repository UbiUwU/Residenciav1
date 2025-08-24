<?php

namespace App\Http\Controllers;

use App\Models\Competencia;
use Illuminate\Http\Request;

class CompetenciaController extends Controller
{
    // Crear una competencia
    public function createOne(Request $request)
    {
        $request->validate([
            'ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'Descripcion' => 'required|string|max:255',
            'Tipo_Competencia' => 'required|in:Específica,Generica'
        ]);


        $competencia = Competencia::create($request->all());

        return response()->json([
            'message' => 'Competencia creada exitosamente',
            'data' => $competencia
        ], 201);
    }

    // Crear múltiples competencias
    public function createMultiple(Request $request)
    {
        $request->validate([
            'competencias' => 'required|array',
            'competencias.*.ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'competencias.*.Descripcion' => 'required|string|max:255',
            'competencias.*.Tipo_Competencia' => 'required|in:Específica,Previas,Generica'
        ]);

        $competencias = [];
        foreach ($request->competencias as $competenciaData) {
            $competencias[] = Competencia::create($competenciaData);
        }

        return response()->json([
            'message' => 'Competencias creadas exitosamente',
            'data' => $competencias
        ], 201);
    }

    // Actualizar una competencia
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'Descripcion' => 'sometimes|required|string|max:255',
            'Tipo_Competencia' => 'sometimes|required|in:Específica,Previas,Generica'
        ]);

        $competencia = Competencia::findOrFail($id);
        $competencia->update($request->all());

        return response()->json([
            'message' => 'Competencia actualizada exitosamente',
            'data' => $competencia
        ]);
    }

    // Actualizar múltiples competencias
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'competencias' => 'required|array',
            'competencias.*.id_Competencia' => 'required|exists:competencia,id_Competencia',
            'competencias.*.Descripcion' => 'sometimes|required|string|max:255',
            'competencias.*.Tipo_Competencia' => 'sometimes|required|in:Específica,Previas,Generica'
        ]);

        $competenciasActualizadas = [];
        foreach ($request->competencias as $competenciaData) {
            $competencia = Competencia::findOrFail($competenciaData['id_Competencia']);
            $competencia->update($competenciaData);
            $competenciasActualizadas[] = $competencia;
        }

        return response()->json([
            'message' => 'Competencias actualizadas exitosamente',
            'data' => $competenciasActualizadas
        ]);
    }

    // Eliminar una competencia
    public function deleteOne($id)
    {
        $competencia = Competencia::findOrFail($id);
        $competencia->delete();

        return response()->json([
            'message' => 'Competencia eliminada exitosamente'
        ]);
    }

    // Obtener competencias por tipo y asignatura (método adicional útil)
    public function getByTipo($claveAsignatura, $tipo)
    {
        $competencias = Competencia::where('ClaveAsignatura', $claveAsignatura)
            ->where('Tipo_Competencia', $tipo)
            ->get();

        return response()->json([
            'data' => $competencias
        ]);
    }
}