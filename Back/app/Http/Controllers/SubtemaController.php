<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubtemaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tema_id' => 'required|integer',
            'nombre_subtema' => 'required|string|max:255',
        ]);

        try {
            $nuevoId = DB::select('SELECT crear_subtema(?, ?) AS id', [
                $request->tema_id,
                $request->nombre_subtema,
            ]);

            return response()->json([
                'success' => true,
                'id_Subtema' => $nuevoId[0]->id,
                'message' => 'Subtema creado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear subtema: '.$e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tema_id' => 'required|integer',
            'nombre_subtema' => 'required|string|max:255',
        ]);

        try {
            DB::statement('SELECT actualizar_subtema(?, ?, ?)', [
                $id,
                $request->tema_id,
                $request->nombre_subtema,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subtema actualizado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar subtema: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('SELECT eliminar_subtema(?)', [$id]);

            return response()->json([
                'success' => true,
                'message' => 'Subtema eliminado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar subtema: '.$e->getMessage(),
            ], 500);
        }
    }
}
