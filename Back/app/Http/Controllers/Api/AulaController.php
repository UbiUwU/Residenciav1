<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AulaController extends Controller
{
    // Obtener todas las aulas
    public function index()
    {
        $aulas = DB::select("SELECT * FROM get_all_aulas()");

        return response()->json([
            'success' => true,
            'data' => $aulas
        ]);
    }

    // Obtener una aula por ClaveAula
    public function show($claveAula)
    {
        $aula = DB::select("SELECT * FROM get_aula_by_id(?)", [$claveAula]);

        if (empty($aula)) {
            return response()->json([
                'success' => false,
                'message' => 'Aula no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $aula[0]
        ]);
    }

    // Crear una nueva aula
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave_aula' => 'required|string|max:20|unique:aulas,ClaveAula',
            'clave_edificio' => 'required|string|exists:edificios,ClaveEdificio',
            'nombre' => 'required|string|max:100',
            'cantidad_computadoras' => 'required|integer|min:0',
            'hora_disponible' => 'required|date_format:H:i:s'
        ]);

        try {
            $result = DB::select("SELECT insert_aula(?, ?, ?, ?, ?) AS result", [
                $validated['clave_aula'],
                $validated['clave_edificio'],
                $validated['nombre'],
                $validated['cantidad_computadoras'],
                $validated['hora_disponible']
            ]);

            $message = $result[0]->result;

            if (str_contains($message, 'Error')) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'clave_aula' => $validated['clave_aula']
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear aula: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    // Actualizar aula
    public function update(Request $request, $claveAula)
    {
        $validated = $request->validate([
            'clave_edificio' => 'required|string|exists:edificios,ClaveEdificio',
            'nombre' => 'required|string|max:100',
            'cantidad_computadoras' => 'required|integer|min:0',
            'hora_disponible' => 'required|date_format:H:i:s'
        ]);

        try {
            $result = DB::select("SELECT update_aula(?, ?, ?, ?, ?) AS result", [
                $claveAula,
                $validated['clave_edificio'],
                $validated['nombre'],
                $validated['cantidad_computadoras'],
                $validated['hora_disponible']
            ]);

            $message = $result[0]->result;

            if (str_contains($message, 'Error')) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $validated
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar aula: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    // Eliminar aula
    public function destroy($claveAula)
    {
        try {
            $result = DB::select("SELECT delete_aula(?) AS result", [$claveAula]);
            $message = $result[0]->result;

            if (str_contains($message, 'Error')) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar aula: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
}
