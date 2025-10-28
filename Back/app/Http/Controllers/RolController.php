<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Rol;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $roles = Rol::all();
            
            return response()->json([
                'success' => true,
                'data' => $roles,
                'message' => 'Roles obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener roles: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $rol = Rol::find($id);
            
            if (!$rol) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => $rol,
                'message' => 'Rol obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error("Error al obtener rol {$id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'idrol' => 'required|integer|min:1|unique:roles,idrol',
                'nombre' => 'required|string|max:50|unique:roles,nombre',
            ]);

            $rol = Rol::create($validated);

            Log::info("Rol creado exitosamente: {$rol->id}");

            return response()->json([
                'success' => true,
                'data' => $rol,
                'message' => 'Rol creado exitosamente'
            ], Response::HTTP_CREATED);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            
        } catch (\Exception $e) {
            Log::error('Error al crear rol: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $rol = Rol::find($id);
            
            if (!$rol) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            $validated = $request->validate([
                'nombre' => 'sometimes|required|string|max:50|unique:roles,nombre,' . $id . ',idrol',
                'idrol' => 'sometimes|required|integer|min:1|unique:roles,idrol,' . $id . ',idrol',
            ]);

            $rol->update($validated);

            Log::info("Rol actualizado exitosamente: {$id}");

            return response()->json([
                'success' => true,
                'data' => $rol,
                'message' => 'Rol actualizado exitosamente'
            ]);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            
        } catch (\Exception $e) {
            Log::error("Error al actualizar rol {$id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $rol = Rol::find($id);
            
            if (!$rol) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            // Verificar si el rol tiene usuarios asociados antes de eliminar
            if ($rol->usuarios && $rol->usuarios->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el rol porque tiene usuarios asociados'
                ], Response::HTTP_CONFLICT);
            }

            $rol->delete();

            Log::info("Rol eliminado exitosamente: {$id}");

            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al eliminar rol {$id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}