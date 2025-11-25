<?php

namespace App\Http\Controllers;

use App\Models\CompetenciaInstrumentacion;
use Illuminate\Http\Request;

class CompetenciaInstrumentacionController extends Controller
{
    // Crear una competencia
    public function createOne(Request $request)
    {
        $request->validate([
            'id_instrumentacion' => 'required|exists:instrumentacion,id_instrumentacion',
            'id_tema' => 'required|exists:tema,id_Tema',
            'horas_dedicadas' => 'required|integer|min:1',
        ]);

        $competencia = CompetenciaInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Competencia creada exitosamente',
            'data' => $competencia->load(['tema', 'competenciasGenericas', 'actividadesEnsenanza']),
        ], 201);
    }

    // Crear múltiples competencias
    public function createMultiple(Request $request)
    {
        $request->validate([
            'competencias' => 'required|array',
            'competencias.*.id_instrumentacion' => 'required|exists:instrumentacion,id_instrumentacion',
            'competencias.*.id_tema' => 'required|exists:tema,id_Tema',
            'competencias.*.horas_dedicadas' => 'required|integer|min:1',
        ]);

        $competencias = [];
        foreach ($request->competencias as $competenciaData) {
            $competencias[] = CompetenciaInstrumentacion::create($competenciaData);
        }

        return response()->json([
            'message' => 'Competencias creadas exitosamente',
            'data' => $competencias,
        ], 201);
    }

    // Actualizar una competencia
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'horas_dedicadas' => 'sometimes|required|integer|min:1',
        ]);

        $competencia = CompetenciaInstrumentacion::findOrFail($id);
        $competencia->update($request->all());

        return response()->json([
            'message' => 'Competencia actualizada exitosamente',
            'data' => $competencia->load(['tema', 'competenciasGenericas', 'actividadesEnsenanza']),
        ]);
    }

    // Actualizar múltiples competencias
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'competencias' => 'required|array',
            'competencias.*.id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'competencias.*.horas_dedicadas' => 'sometimes|required|integer|min:1',
        ]);

        $competenciasActualizadas = [];
        foreach ($request->competencias as $competenciaData) {
            $competencia = CompetenciaInstrumentacion::findOrFail($competenciaData['id_competencia']);
            $competencia->update($competenciaData);
            $competenciasActualizadas[] = $competencia;
        }

        return response()->json([
            'message' => 'Competencias actualizadas exitosamente',
            'data' => $competenciasActualizadas,
        ]);
    }
}
