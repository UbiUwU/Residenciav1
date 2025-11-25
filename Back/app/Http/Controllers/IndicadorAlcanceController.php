<?php

namespace App\Http\Controllers;

use App\Models\IndicadorAlcance;
use Illuminate\Http\Request;

class IndicadorAlcanceController extends Controller
{
    // Crear un indicador de alcance
    public function createOne(Request $request)
    {
        $request->validate([
            'id_nivel_desempeno' => 'required|exists:niveles_desempeno_instrumentacion,id_nivel_desempeno',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer',
        ]);

        $indicador = IndicadorAlcance::create($request->all());

        return response()->json([
            'message' => 'Indicador de alcance creado exitosamente',
            'data' => $indicador,
        ], 201);
    }

    // Crear múltiples indicadores de alcance
    public function createMultiple(Request $request)
    {
        $request->validate([
            'indicadores' => 'required|array',
            'indicadores.*.id_nivel_desempeno' => 'required|exists:niveles_desempeno_instrumentacion,id_nivel_desempeno',
            'indicadores.*.descripcion' => 'required|string',
            'indicadores.*.orden' => 'nullable|integer',
        ]);

        $indicadores = [];
        foreach ($request->indicadores as $indicadorData) {
            $indicadores[] = IndicadorAlcance::create($indicadorData);
        }

        return response()->json([
            'message' => 'Indicadores de alcance creados exitosamente',
            'data' => $indicadores,
        ], 201);
    }

    // Actualizar un indicador de alcance
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer',
        ]);

        $indicador = IndicadorAlcance::findOrFail($id);
        $indicador->update($request->all());

        return response()->json([
            'message' => 'Indicador de alcance actualizado exitosamente',
            'data' => $indicador,
        ]);
    }

    // Actualizar múltiples indicadores de alcance
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'indicadores' => 'required|array',
            'indicadores.*.id_indicador_alcance' => 'required|exists:indicadores_alcance,id_indicador_alcance',
            'indicadores.*.descripcion' => 'sometimes|required|string',
            'indicadores.*.orden' => 'nullable|integer',
        ]);

        $indicadoresActualizados = [];
        foreach ($request->indicadores as $indicadorData) {
            $indicador = IndicadorAlcance::findOrFail($indicadorData['id_indicador_alcance']);
            $indicador->update($indicadorData);
            $indicadoresActualizados[] = $indicador;
        }

        return response()->json([
            'message' => 'Indicadores de alcance actualizados exitosamente',
            'data' => $indicadoresActualizados,
        ]);
    }
}
