<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MaestroController extends Controller
{
    // Obtener todos los maestros
    public function index()
    {
        $maestros = DB::select("SELECT * FROM get_all_maestros()");

        return response()->json([
            'success' => true,
            'data' => $maestros
        ]);
    }

    // Obtener un maestro por tarjeta
    public function show($tarjeta)
    {
        $maestro = DB::select("SELECT * FROM get_maestro_by_tarjeta(?)", [$tarjeta]);

        if (empty($maestro)) {
            return response()->json([
                'success' => false,
                'message' => 'Maestro no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $maestro[0]
        ]);
    }

    // Crear un nuevo maestro
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Datos del usuario
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:6',
            'id_rol' => 'required|integer',

            // Datos del maestro
            'tarjeta' => 'required|integer|unique:maestros,tarjeta',
            'nombre' => 'required|string|max:255',
            'apellidopaterno' => 'required|string|max:255',
            'apellidomaterno' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'escolaridad_licenciatura' => 'nullable|string|max:50',
            'estado_licenciatura' => 'nullable|string|max:1',
            'escolaridad_especializacion' => 'nullable|string|max:50',
            'estado_especializacion' => 'nullable|string|max:1',
            'escolaridad_maestria' => 'nullable|string|max:50',
            'estado_maestria' => 'nullable|string|max:1',
            'escolaridad_doctorado' => 'nullable|string|max:50',
            'estado_doctorado' => 'nullable|string|max:1',
            'id_departamento' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear usuario y obtener su ID (usando la funcion de postgre)
            $usuario = DB::select('SELECT insert_usuario_con_id(?, ?, ?) AS id', [
                $validated['correo'],
                $validated['password'],
                $validated['id_rol']
            ]);

            $id_usuario = $usuario[0]->id ?? null;

            if (!$id_usuario) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo crear el usuario.'
                ], 400);
            }

            // 2. Crear maestro
            $result = DB::select("SELECT insert_maestro(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS result", [
                $validated['tarjeta'],
                $validated['nombre'],
                $validated['apellidopaterno'],
                $validated['apellidomaterno'],
                $id_usuario,
                $validated['rfc'],
                $validated['escolaridad_licenciatura'] ?? null,
                $validated['estado_licenciatura'] ?? null,
                $validated['escolaridad_especializacion'] ?? null,
                $validated['estado_especializacion'] ?? null,
                $validated['escolaridad_maestria'] ?? null,
                $validated['estado_maestria'] ?? null,
                $validated['escolaridad_doctorado'] ?? null,
                $validated['estado_doctorado'] ?? null,
                $validated['id_departamento']
            ]);

            $message = $result[0]->result;

            if (str_contains($message, 'Error')) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Maestro y usuario creados correctamente.',
                'id_usuario' => $id_usuario,
                'id_maestro' => $validated['tarjeta']
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear maestro y usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }


    // Actualizar un maestro existente
    public function update(Request $request, $tarjeta)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'apellidopaterno' => 'sometimes|string|max:255',
            'apellidomaterno' => 'sometimes|string|max:255',
            'idusuario' => 'sometimes|integer',
            'rfc' => 'sometimes|string|max:13',
            'escolaridad_licenciatura' => 'nullable|string|max:50',
            'estado_licenciatura' => 'nullable|string|max:1',
            'escolaridad_especializacion' => 'nullable|string|max:50',
            'estado_especializacion' => 'nullable|string|max:1',
            'escolaridad_maestria' => 'nullable|string|max:50',
            'estado_maestria' => 'nullable|string|max:1',
            'escolaridad_doctorado' => 'nullable|string|max:50',
            'estado_doctorado' => 'nullable|string|max:1',
            'id_departamento' => 'sometimes|integer'
        ]);

        $result = DB::select("SELECT update_maestro(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) as result", [
            $tarjeta,
            $validated['nombre'] ?? null,
            $validated['apellidopaterno'] ?? null,
            $validated['apellidomaterno'] ?? null,
            $validated['idusuario'] ?? null,
            $validated['rfc'] ?? null,
            $validated['escolaridad_licenciatura'] ?? null,
            $validated['estado_licenciatura'] ?? null,
            $validated['escolaridad_especializacion'] ?? null,
            $validated['estado_especializacion'] ?? null,
            $validated['escolaridad_maestria'] ?? null,
            $validated['estado_maestria'] ?? null,
            $validated['escolaridad_doctorado'] ?? null,
            $validated['estado_doctorado'] ?? null,
            $validated['id_departamento'] ?? null
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
    }

    // Eliminar un maestro
    public function destroy($tarjeta)
    {
        $result = DB::select("SELECT delete_maestro(?) as result", [$tarjeta]);
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
    }
}