<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservacionMaestroController extends Controller
{
    // Obtener todas las reservaciones de maestros
    public function index()
    {
        $reservaciones = DB::select("SELECT * FROM get_all_reservaciones_maestro()");
        return response()->json([
            'success' => true,
            'data' => $reservaciones
        ]);
    }

    // Obtener una reservación de maestro por ID
    public function show($id)
    {
        $reservacion = DB::select("SELECT * FROM get_reservacion_maestro_by_id(?)", [$id]);

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

    // Crear una nueva reservación de maestro
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tarjeta' => 'required|integer',
            'clave_aula' => 'required|string|max:20',
            'fecha_reservacion' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s'
        ]);

        $result = DB::select("SELECT insert_reservacion_maestro(?, ?, ?, ?, ?) AS message", [
            $validated['tarjeta'],
            $validated['clave_aula'],
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

    // Actualizar una reservación de maestro
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tarjeta' => 'required|integer',
            'clave_aula' => 'required|string|max:20',
            'fecha_reservacion' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s'
        ]);

        $result = DB::select("SELECT update_reservacion_maestro(?, ?, ?, ?, ?, ?) AS message", [
            $id,
            $validated['tarjeta'],
            $validated['clave_aula'],
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

    // Eliminar una reservación de maestro
    public function destroy($id)
    {
        $result = DB::select("SELECT delete_reservacion_maestro(?) AS message", [$id]);
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
