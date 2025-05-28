<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
     public function store(Request $request)
    {
        $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $nuevoId = DB::selectOne('SELECT crear_proyecto_asignatura(?, ?) AS id', [
                $request->clave_asignatura,
                $request->descripcion
            ])->id;

            return response()->json([
                'success' => true,
                'id_Proyecto_Asig' => $nuevoId,
                'message' => 'Proyecto asignatura creado correctamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear proyecto asignatura: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            DB::statement('SELECT actualizar_proyecto_asignatura(?, ?, ?)', [
                $id,
                $request->clave_asignatura,
                $request->descripcion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proyecto asignatura actualizado correctamente',
                'id_Proyecto_Asig' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar proyecto asignatura: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('SELECT eliminar_proyecto_asignatura(?)', [$id]);

            return response()->json([
                'success' => true,
                'message' => 'Proyecto asignatura eliminado correctamente',
                'id_Proyecto_Asig' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar proyecto asignatura: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $proyecto = DB::selectOne('SELECT * FROM consultar_proyecto_asignatura_por_id(?)', [$id]);

            if (!$proyecto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyecto asignatura no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $proyecto
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al consultar proyecto asignatura: ' . $e->getMessage()
            ], 500);
        }
    }
}
