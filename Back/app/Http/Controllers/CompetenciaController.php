<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetenciaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
            'tipo_comp' => 'required|array',
            'tipo_comp.*' => 'string' // ajusta si `tipo_comp` es otro tipo
        ]);

        try {
            $result = DB::selectOne(
                "SELECT crear_competencia(?, ?, ?) AS result",
                [
                    $validated['clave_asignatura'],
                    $validated['descripcion'],
                    '{' . implode(',', array_map(function ($item) {
                        return '"' . $item . '"'; // Escapamos como texto SQL
                    }, $validated['tipo_comp'])) . '}'
                ]
            );

            return response()->json(json_decode($result->result));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'clave_asignatura' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string|max:255',
            'tipo_comp' => 'nullable|array',
            'tipo_comp.*' => 'string'
        ]);

        try {
            $tipoCompArray = null;
            if (isset($validated['tipo_comp'])) {
                $tipoCompArray = '{' . implode(',', array_map(function ($item) {
                    return '"' . $item . '"';
                }, $validated['tipo_comp'])) . '}';
            }

            $result = DB::selectOne(
                "SELECT actualizar_competencia(?, ?, ?, ?) AS result",
                [
                    $id,
                    $validated['clave_asignatura'] ?? null,
                    $validated['descripcion'] ?? null,
                    $tipoCompArray
                ]
            );

            return response()->json(json_decode($result->result));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $result = DB::selectOne(
                "SELECT eliminar_competencia(?) AS result",
                [$id]
            );

            return response()->json(json_decode($result->result));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

}
