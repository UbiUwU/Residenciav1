<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompetenciaTemaController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tema_id' => 'required|integer|exists:tema,id_Tema',
            'descripcion_competencia' => 'required|string|max:255',
            'tipo_competencia' => 'required|array',
            'tipo_competencia.*' => 'in:Genérica,Previas,Específica', // Ajusta según tus ENUMs
            'descripcion_actividad' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        try {
            $result = DB::select(
                'SELECT crear_conjunto_competencia_actividad(?, ?, ?, ?) AS id',
                [
                    $request->tema_id,
                    $request->descripcion_competencia,
                    '{' . implode(',', $request->tipo_competencia) . '}',
                    $request->descripcion_actividad,
                ]
            );

            return response()->json([
                'mensaje' => 'Conjunto creado exitosamente',
                'id_actividad_competencia' => $result[0]->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el conjunto', 'detalle' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'comp_id' => 'nullable|integer|exists:competencia_tema,id_Comp_Tema',
            'descripcion_competencia' => 'nullable|string|max:255',
            'tipo_competencia' => 'nullable|array',
            'tipo_competencia.*' => 'in:profesional,generica,basica',
            'act_id' => 'nullable|integer|exists:actividad_aprendizaje,id_Act_Aprendizaje',
            'descripcion_actividad' => 'nullable|string|max:255',
        ]);

        $result = DB::select('SELECT actualizar_conjunto_competencia_actividad(?, ?, ?, ?, ?)', [
            $validated['comp_id'] ?? null,
            $validated['descripcion_competencia'] ?? null,
            isset($validated['tipo_competencia']) ? '{' . implode(',', $validated['tipo_competencia']) . '}' : null,
            $validated['act_id'] ?? null,
            $validated['descripcion_actividad'] ?? null,
        ]);

        return response()->json(['mensaje' => 'Actualización realizada correctamente'], 200);
    }
    public function destroy($id)
    {
        DB::select('SELECT eliminar_conjunto_competencia_actividad(?)', [$id]);
        return response()->json(['mensaje' => 'Conjunto eliminado correctamente']);
    }

}
