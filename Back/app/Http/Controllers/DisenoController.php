<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisenoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'fecha' => 'required|date',
            'evento' => 'required|string|max:255',
            'participantes' => 'required|array',
            'participantes.*.id' => 'nullable|integer',
            'participantes.*.nombre' => 'nullable|string|max:255'
        ]);

        try {
            $result = DB::selectOne(
                "SELECT crear_diseno_completo(?, ?, ?, ?::jsonb) as result",
                [
                    $validated['clave_asignatura'],
                    $validated['fecha'],
                    $validated['evento'],
                    json_encode($validated['participantes'])
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
            'fecha' => 'nullable|date',
            'evento' => 'nullable|string|max:255',

        ]);

        try {
            $result = DB::selectOne(
                "SELECT actualizar_diseno_completo(?, ?, ?, ?) AS result",
                [
                    $id,
                    $validated['clave_asignatura'] ?? null,
                    $validated['fecha'] ?? null,
                    $validated['evento'] ?? null,
                ]
            );

            return response()->json(json_decode($result->result));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Llamar a la función eliminar_diseno_completo solo con el ID
            $result = DB::selectOne(
                "SELECT eliminar_diseno_completo(?) AS result",
                [$id]
            );

            return response()->json(json_decode($result->result));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateParticipantes(Request $request, $id)
    {
        $validated = $request->validate([
            '*.id' => 'nullable|integer',
            '*.nombre' => 'nullable|string|max:255'
        ]);

        try {
            $result = DB::selectOne(
                "SELECT actualizar_participantes_diseno(?, ?::jsonb) AS result",
                [
                    $id,
                    json_encode($request->all()) // usar el array plano
                ]
            );

            return response()->json(json_decode($result->result));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la actualización de participantes: ' . $e->getMessage(),
                'error_details' => $e->getCode()
            ], 500);
        }
    }
    public function eliminarParticipante($id, $participante_id)
    {
        try {
            $result = DB::selectOne(
                "SELECT eliminar_participante_diseno(?, ?) AS result",
                [
                    $id,
                    $participante_id
                ]
            );

            return response()->json(json_decode($result->result));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar participante: ' . $e->getMessage()
            ], 500);
        }
    }



}
