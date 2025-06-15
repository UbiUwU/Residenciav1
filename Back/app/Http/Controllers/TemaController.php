<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'numero' => 'required|integer',
            'nombre_tema' => 'required|string|max:255',
        ]);

        $clave = $request->input('clave_asignatura');
        $numero = $request->input('numero');
        $nombre = $request->input('nombre_tema');

        try {
            $result = DB::select('SELECT crear_tema(?, ?, ?) AS id', [$clave, $numero, $nombre]);

            return response()->json([
                'success' => true,
                'id_tema' => $result[0]->id,
                'message' => 'Tema creado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear tema: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'clave_asignatura' => 'required|string|max:50',
            'numero' => 'required|integer',
            'nombre_tema' => 'required|string|max:255',
        ]);

        try {
            DB::statement('SELECT actualizar_tema(?, ?, ?, ?)', [
                $id,
                $request->input('clave_asignatura'),
                $request->input('numero'),
                $request->input('nombre_tema'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tema actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tema: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            DB::statement('SELECT eliminar_tema(?)', [$id]);

            return response()->json([
                'success' => true,
                'message' => 'Tema eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar tema: ' . $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        try {
            $temas = DB::select('SELECT * FROM consultar_todos_temas()');

            return response()->json([
                'success' => true,
                'data' => $temas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener temas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerTemasYSubtemasPorAsignatura($claveAsignatura)
    {
        try {
            $resultados = DB::select('SELECT * FROM obtener_temas_y_subtemas_por_asignatura(?)', [
                $claveAsignatura
            ]);

            return response()->json([
                'success' => true,
                'data' => $resultados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener temas y subtemas: ' . $e->getMessage()
            ], 500);
        }
    }

}
