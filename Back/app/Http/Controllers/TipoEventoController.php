<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TipoEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tipos = DB::table('tipo_evento')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Tipos de evento obtenidos correctamente',
                'data' => $tipos
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los tipos de evento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:tipo_evento,nombre',
            'descripcion' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $id = DB::table('tipo_evento')->insertGetId([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de evento creado exitosamente',
                'data' => ['id' => $id]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el tipo de evento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $tipo = DB::table('tipo_evento')->where('id', $id)->first();

            if (!$tipo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de evento no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tipo de evento obtenido correctamente',
                'data' => $tipo
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el tipo de evento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:tipo_evento,nombre,'.$id,
            'descripcion' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $affected = DB::table('tipo_evento')
                ->where('id', $id)
                ->update([
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion
                ]);

            if ($affected === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de evento no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tipo de evento actualizado correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el tipo de evento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = DB::table('tipo_evento')->where('id', $id)->delete();

            if ($deleted === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de evento no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tipo de evento eliminado correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el tipo de evento',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}