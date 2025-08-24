<?php

namespace App\Http\Controllers;

use App\Models\CompetenciaGenericoInstrumentacion;
use Illuminate\Http\Request;

class CompetenciaGenericoInstrumentacionController extends Controller
{
    // Crear una competencia genérica
    public function createOne(Request $request)
    {
        $request->validate([
            'id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer'
        ]);

        $competenciaGenerica = CompetenciaGenericoInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Competencia genérica creada exitosamente',
            'data' => $competenciaGenerica
        ], 201);
    }

    // Crear múltiples competencias genéricas
    public function createMultiple(Request $request)
    {
        $request->validate([
            'competencias_genericas' => 'required|array',
            'competencias_genericas.*.id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'competencias_genericas.*.descripcion' => 'required|string',
            'competencias_genericas.*.orden' => 'nullable|integer'
        ]);

        $competencias = [];
        foreach ($request->competencias_genericas as $competenciaData) {
            $competencias[] = CompetenciaGenericoInstrumentacion::create($competenciaData);
        }

        return response()->json([
            'message' => 'Competencias genéricas creadas exitosamente',
            'data' => $competencias
        ], 201);
    }

    // Actualizar una competencia genérica
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer'
        ]);

        $competenciaGenerica = CompetenciaGenericoInstrumentacion::findOrFail($id);
        $competenciaGenerica->update($request->all());

        return response()->json([
            'message' => 'Competencia genérica actualizada exitosamente',
            'data' => $competenciaGenerica
        ]);
    }

    // Actualizar múltiples competencias genéricas
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'competencias_genericas' => 'required|array',
            'competencias_genericas.*.id_competencia_generico_instrumentacion' => 'required|exists:competencia_generico_instrumentacion,id_competencia_generico_instrumentacion',
            'competencias_genericas.*.descripcion' => 'sometimes|required|string',
            'competencias_genericas.*.orden' => 'nullable|integer'
        ]);

        $competenciasActualizadas = [];
        foreach ($request->competencias_genericas as $competenciaData) {
            $competencia = CompetenciaGenericoInstrumentacion::findOrFail($competenciaData['id_competencia_generico_instrumentacion']);
            $competencia->update($competenciaData);
            $competenciasActualizadas[] = $competencia;
        }

        return response()->json([
            'message' => 'Competencias genéricas actualizadas exitosamente',
            'data' => $competenciasActualizadas
        ]);
    }
}