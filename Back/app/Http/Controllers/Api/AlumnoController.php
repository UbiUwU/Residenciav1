<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    // Obtener todos los alumnos
    public function index()
    {
        $alumnos = DB::select("SELECT * FROM get_all_alumnos()");

        return response()->json([
            'success' => true,
            'data' => $alumnos
        ]);
    }

    // Obtener un alumno por número de control
    public function show($numeroControl)
    {
        $alumno = DB::select("SELECT * FROM get_alumno_by_id(?)", [$numeroControl]);

        if (empty($alumno)) {
            return response()->json([
                'success' => false,
                'message' => 'Alumno no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $alumno[0]
        ]);
    }

    // Crear un nuevo alumno
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_control' => 'required|integer|unique:alumnos,NumeroControl',
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'id_usuario' => 'required|integer|exists:usuarios,id',
            'clave_carrera' => 'required|string|exists:carreras,ClaveCarrera'
        ]);

        try {
            $result = DB::select("SELECT insert_alumno(?, ?, ?, ?, ?, ?) AS result", [
                $validated['numero_control'],
                $validated['nombre'],
                $validated['apellido_paterno'],
                $validated['apellido_materno'],
                $validated['id_usuario'],
                $validated['clave_carrera']
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
                'numero_control' => $validated['numero_control']
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear alumno: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    // Actualizar un alumno
    public function update(Request $request, $numeroControl)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'apellido_paterno' => 'sometimes|string|max:255',
            'apellido_materno' => 'sometimes|string|max:255',
            'clave_carrera' => 'sometimes|string|exists:carreras,ClaveCarrera'
        ]);

        try {
            $result = DB::select("SELECT update_alumno(?, ?, ?, ?, ?) AS result", [
                $numeroControl,
                $validated['nombre'] ?? null,
                $validated['apellido_paterno'] ?? null,
                $validated['apellido_materno'] ?? null,
                $validated['clave_carrera'] ?? null
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
            Log::error('Error al actualizar alumno: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    // Eliminar un alumno
    public function destroy($numeroControl)
    {
        try {
            $result = DB::select("SELECT delete_alumno(?) AS result", [$numeroControl]);
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
            Log::error('Error al eliminar alumno: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
}
