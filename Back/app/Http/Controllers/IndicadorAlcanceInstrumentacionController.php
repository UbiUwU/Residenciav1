<?php

namespace App\Http\Controllers;

use App\Models\IndicadorAlcanceInstrumentacion;
use Illuminate\Http\Request;

class IndicadorAlcanceInstrumentacionController extends Controller
{
    // Crear un indicador de alcance
    public function createOne(Request $request)
    {
        $request->validate([
            'id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'indicador_alcance' => 'required|string',
            'valor_indicador' => 'required|integer|min:0'
        ]);

        $indicador = IndicadorAlcanceInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Indicador de alcance creado exitosamente',
            'data' => $indicador
        ], 201);
    }

    // Crear múltiples indicadores de alcance
    public function createMultiple(Request $request)
    {
        $request->validate([
            'indicadores' => 'required|array',
            'indicadores.*.id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'indicadores.*.indicador_alcance' => 'required|string',
            'indicadores.*.valor_indicador' => 'required|integer|min:0'
        ]);

        $indicadores = [];
        foreach ($request->indicadores as $indicadorData) {
            $indicadores[] = IndicadorAlcanceInstrumentacion::create($indicadorData);
        }

        return response()->json([
            'message' => 'Indicadores de alcance creados exitosamente',
            'data' => $indicadores
        ], 201);
    }

    // Actualizar un indicador de alcance
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'indicador_alcance' => 'sometimes|required|string',
            'valor_indicador' => 'sometimes|required|integer|min:0'
        ]);

        $indicador = IndicadorAlcanceInstrumentacion::findOrFail($id);
        $indicador->update($request->all());

        return response()->json([
            'message' => 'Indicador de alcance actualizado exitosamente',
            'data' => $indicador
        ]);
    }

    // Actualizar múltiples indicadores de alcance
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'indicadores' => 'required|array',
            'indicadores.*.id_indicador' => 'required|exists:indicadores_alcance_instrumentacion,id_indicador',
            'indicadores.*.indicador_alcance' => 'sometimes|required|string',
            'indicadores.*.valor_indicador' => 'sometimes|required|integer|min:0'
        ]);

        $indicadoresActualizados = [];
        foreach ($request->indicadores as $indicadorData) {
            $indicador = IndicadorAlcanceInstrumentacion::findOrFail($indicadorData['id_indicador']);
            $indicador->update($indicadorData);
            $indicadoresActualizados[] = $indicador;
        }

        return response()->json([
            'message' => 'Indicadores de alcance actualizados exitosamente',
            'data' => $indicadoresActualizados
        ]);
    }
}