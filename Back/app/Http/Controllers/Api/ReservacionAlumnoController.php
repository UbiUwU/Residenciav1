<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservacionAlumnoController extends Controller
{
    // Obtener todas las reservaciones
    public function index()
    {
        $reservaciones = DB::select("SELECT * FROM get_all_reservaciones()");
        return response()->json([
            'success' => true,
            'data' => $reservaciones
        ]);
    }

    // Obtener una reservación por ID
    public function show($id)
    {
        $reservacion = DB::select("SELECT * FROM get_reservacion_by_id(?)", [$id]);

        if (empty($reservacion)) {
            return response()->json([
                'success' => false,
                'message' => 'Reservación no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $reservacion[0]
        ]);
    }

    // Crear una nueva reservación
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_control' => 'required|integer',
            'numero_inventario' => 'required|integer',
            'fecha_reservacion' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s'
        ]);

        $result = DB::select("SELECT insert_reservacion_alumno(?, ?, ?, ?, ?) AS message", [
            $validated['numero_control'],
            $validated['numero_inventario'],
            $validated['fecha_reservacion'],
            $validated['hora_inicio'],
            $validated['hora_fin']
        ]);

        $message = $result[0]->message;

        if (str_contains($message, 'Error')) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ], 201);
    }

    // Actualizar una reservación
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'numero_control' => 'required|integer',
            'numero_inventario' => 'required|integer',
            'fecha_reservacion' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s'
        ]);

        $result = DB::select("SELECT update_reservacion(?, ?, ?, ?, ?, ?) AS message", [
            $id,
            $validated['numero_control'],
            $validated['numero_inventario'],
            $validated['fecha_reservacion'],
            $validated['hora_inicio'],
            $validated['hora_fin']
        ]);

        $message = $result[0]->message;

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
    }

    // Eliminar una reservación
    public function destroy($id)
    {
        $result = DB::select("SELECT delete_reservacion(?) AS message", [$id]);
        $message = $result[0]->message;

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
    }
}
