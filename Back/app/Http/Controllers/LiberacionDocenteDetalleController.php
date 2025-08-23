<?php

namespace App\Http\Controllers;

use App\Models\LiberacionDocenteDetalle;
use App\Models\LiberacionDocente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LiberacionDocenteDetalleController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id_liberacion)
    {
        DB::beginTransaction();

        try {
            // Verificar que la liberación existe
            LiberacionDocente::findOrFail($id_liberacion);

            $validator = Validator::make($request->all(), [
                'numero_actividad' => 'required|integer|min:1',
                'descripcion_actividad' => 'required|string|max:500',
                'estado' => 'required|in:SI,NO,N/A'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar si ya existe este número de actividad para la liberación
            $existe = LiberacionDocenteDetalle::where('id_liberacion', $id_liberacion)
                ->where('numero_actividad', $request->numero_actividad)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una actividad con el número ' . $request->numero_actividad . ' para esta liberación'
                ], 409);
            }

            // Crear el detalle
            $detalle = new LiberacionDocenteDetalle();
            $detalle->id_liberacion = $id_liberacion;
            $detalle->numero_actividad = $request->numero_actividad;
            $detalle->descripcion_actividad = $request->descripcion_actividad;
            $detalle->estado = $request->estado; // Esto activará el setter automáticamente
            $detalle->save();

            DB::commit();

            // Recargar relación
            $detalle->load('liberacion');

            return response()->json([
                'success' => true,
                'message' => 'Detalle de liberación creado exitosamente',
                'data' => $detalle
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'descripcion_actividad' => 'sometimes|string|max:500',
                'estado' => 'sometimes|in:SI,NO,N/A'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detalle = LiberacionDocenteDetalle::findOrFail($id);

            // Actualizar solo los campos proporcionados
            if ($request->has('descripcion_actividad')) {
                $detalle->descripcion_actividad = $request->descripcion_actividad;
            }

            if ($request->has('estado')) {
                $detalle->estado = $request->estado;
            }

            $detalle->save();

            DB::commit();

            // Recargar relación
            $detalle->load('liberacion');

            return response()->json([
                'success' => true,
                'message' => 'Detalle actualizado exitosamente',
                'data' => $detalle
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $detalle = LiberacionDocenteDetalle::findOrFail($id);
            $detalle->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detalle eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store multiple detalles at once
     */
    public function storeMultiple(Request $request, $id_liberacion)
    {
        DB::beginTransaction();

        try {
            // Verificar que la liberación existe
            LiberacionDocente::findOrFail($id_liberacion);

            $validator = Validator::make($request->all(), [
                'detalles' => 'required|array',
                'detalles.*.numero_actividad' => 'required|integer|min:1',
                'detalles.*.descripcion_actividad' => 'required|string|max:500',
                'detalles.*.estado' => 'required|in:SI,NO,N/A'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detallesCreados = [];

            foreach ($request->detalles as $detalleData) {
                // Verificar si ya existe este número de actividad
                $existe = LiberacionDocenteDetalle::where('id_liberacion', $id_liberacion)
                    ->where('numero_actividad', $detalleData['numero_actividad'])
                    ->exists();

                if ($existe) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Ya existe una actividad con el número ' . $detalleData['numero_actividad'] . ' para esta liberación'
                    ], 409);
                }

                $detalle = new LiberacionDocenteDetalle();
                $detalle->id_liberacion = $id_liberacion;
                $detalle->numero_actividad = $detalleData['numero_actividad'];
                $detalle->descripcion_actividad = $detalleData['descripcion_actividad'];
                $detalle->estado = $detalleData['estado'];
                $detalle->save();

                $detallesCreados[] = $detalle;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detalles creados exitosamente',
                'data' => $detallesCreados
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear los detalles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update multiple detalles at once
     */
    public function updateMultiple(Request $request, $id_liberacion)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'detalles' => 'required|array',
                'detalles.*.id_detalle' => 'required|exists:liberacion_docente_detalles,id_detalle',
                'detalles.*.descripcion_actividad' => 'sometimes|string|max:500',
                'detalles.*.estado' => 'sometimes|in:SI,NO,N/A'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detallesActualizados = [];

            foreach ($request->detalles as $detalleData) {
                $detalle = LiberacionDocenteDetalle::where('id_detalle', $detalleData['id_detalle'])
                    ->where('id_liberacion', $id_liberacion)
                    ->firstOrFail();

                if (isset($detalleData['descripcion_actividad'])) {
                    $detalle->descripcion_actividad = $detalleData['descripcion_actividad'];
                }

                if (isset($detalleData['estado'])) {
                    $detalle->estado = $detalleData['estado'];
                }

                $detalle->save();
                $detallesActualizados[] = $detalle;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detalles actualizados exitosamente',
                'data' => $detallesActualizados
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los detalles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update estado of a specific detail
     */
    public function updateEstado(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'estado' => 'required|in:SI,NO,N/A'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detalle = LiberacionDocenteDetalle::findOrFail($id);
            $detalle->estado = $request->estado;
            $detalle->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'data' => $detalle
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }
}