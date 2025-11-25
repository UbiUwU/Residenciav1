<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PracticaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $result = DB::selectOne(
                'SELECT crear_practica(?, ?) AS result',
                [
                    $validated['clave_asignatura'],
                    $validated['descripcion'],
                ]
            );

            return response()->json(json_decode($result->result));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: '.$e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            // Llamar a la función PostgreSQL
            DB::statement(
                'SELECT actualizar_practica(?, ?, ?)',
                [
                    $id,
                    $validated['clave_asignatura'],
                    $validated['descripcion'],
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Práctica actualizada correctamente',
                'id_Practica' => $id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la práctica: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('SELECT eliminar_practica(?)', [$id]);

            return response()->json([
                'success' => true,
                'message' => 'Práctica eliminada correctamente',
                'id_Practica' => $id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la práctica: '.$e->getMessage(),
            ], 500);
        }
    }
}
