<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PublicoDestinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $publicos = DB::table('publico_destino')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Públicos destino obtenidos correctamente',
                'data' => $publicos
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los públicos destino',
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
            'nombre' => 'required|string|max:255|unique:publico_destino,nombre'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $id = DB::table('publico_destino')->insertGetId([
                'nombre' => $request->nombre
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Público destino creado exitosamente',
                'data' => ['id' => $id]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el público destino',
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
            $publico = DB::table('publico_destino')->where('id', $id)->first();

            if (!$publico) {
                return response()->json([
                    'success' => false,
                    'message' => 'Público destino no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Público destino obtenido correctamente',
                'data' => $publico
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el público destino',
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
            'nombre' => 'required|string|max:255|unique:publico_destino,nombre,'.$id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $affected = DB::table('publico_destino')
                ->where('id', $id)
                ->update([
                    'nombre' => $request->nombre
                ]);

            if ($affected === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Público destino no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Público destino actualizado correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el público destino',
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
            $deleted = DB::table('publico_destino')->where('id', $id)->delete();

            if ($deleted === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Público destino no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Público destino eliminado correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el público destino',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}