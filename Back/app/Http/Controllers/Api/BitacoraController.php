<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BitacoraController extends Controller
{
    // ======================== ALUMNOS ========================

    // Obtener todos los registros de bitácora de alumnos
    public function indexAlumnos()
    {
        $bitacoras = DB::select("SELECT * FROM get_all_bitacora_alumnos()");
        return response()->json(['success' => true, 'data' => $bitacoras]);
    }

    // Obtener registros de bitácora de alumno por número de control
    public function showAlumno($numeroControl)
    {
        $result = DB::select("SELECT * FROM get_bitacora_by_numero_control(?)", [$numeroControl]);

        if (empty($result)) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros para este alumno'
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    // Insertar nuevo registro en bitácora de alumnos
    public function storeAlumno(Request $request)
    {
        $validated = $request->validate([
            'numero_control' => 'required|integer',
            'numero_inventario' => 'required|integer'
        ]);

        try {
            $result = DB::select("SELECT insert_bitacora_alumno(?, ?) AS result", [
                $validated['numero_control'],
                $validated['numero_inventario']
            ]);

            $message = $result[0]->result;

            return response()->json([
                'success' => !str_contains($message, 'Error'),
                'message' => $message
            ], str_contains($message, 'Error') ? 400 : 201);
        } catch (\Exception $e) {
            Log::error('Error al registrar en bitácora alumno: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error inesperado'], 500);
        }
    }

    // Eliminar registro de bitácora de alumno por ID
    public function destroyAlumno($idBitacora)
    {
        try {
            $result = DB::select("SELECT delete_bitacora_entry(?) AS result", [$idBitacora]);
            $message = $result[0]->result;

            return response()->json([
                'success' => !str_contains($message, 'Error'),
                'message' => $message
            ], str_contains($message, 'Error') ? 404 : 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar registro de bitácora alumno: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error inesperado'], 500);
        }
    }

    // ======================== MAESTROS ========================

    // Obtener todos los registros de bitácora de maestros
    public function indexMaestros()
    {
        $bitacoras = DB::select("SELECT * FROM get_all_bitacora_maestros()");
        return response()->json(['success' => true, 'data' => $bitacoras]);
    }

    // Obtener registros de bitácora por tarjeta del maestro
    public function showMaestro($tarjeta)
    {
        $result = DB::select("SELECT * FROM get_bitacora_by_tarjeta(?)", [$tarjeta]);

        if (empty($result)) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros para esta tarjeta'
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    // Insertar nuevo registro en bitácora de maestros
    public function storeMaestro(Request $request)
    {
        $validated = $request->validate([
            'tarjeta' => 'required|integer',
            'clave_aula' => 'required|string'
        ]);

        try {
            $result = DB::select("SELECT insert_bitacora_maestro(?, ?) AS result", [
                $validated['tarjeta'],
                $validated['clave_aula']
            ]);

            $message = $result[0]->result;

            return response()->json([
                'success' => !str_contains($message, 'Error'),
                'message' => $message
            ], str_contains($message, 'Error') ? 400 : 201);
        } catch (\Exception $e) {
            Log::error('Error al registrar en bitácora maestro: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error inesperado'], 500);
        }
    }

    // Eliminar registro de bitácora de maestro por ID
    public function destroyMaestro($idBitacora)
    {
        try {
            $result = DB::select("SELECT delete_bitacora_maestro_entry(?) AS result", [$idBitacora]);
            $message = $result[0]->result;

            return response()->json([
                'success' => !str_contains($message, 'Error'),
                'message' => $message
            ], str_contains($message, 'Error') ? 404 : 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar registro de bitácora maestro: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error inesperado'], 500);
        }
    }
}
