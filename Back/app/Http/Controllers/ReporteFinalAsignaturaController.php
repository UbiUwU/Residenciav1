<?php

namespace App\Http\Controllers;

use App\Models\ReporteFinalAsignatura;
use App\Models\ReporteFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReporteFinalAsignaturaController extends Controller
{

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'a' => 'sometimes|integer|min:0',
                'b' => 'sometimes|integer|min:0',
                'bco' => 'sometimes|integer|min:0',
                'c' => 'sometimes|numeric|min:0|max:100',
                'd' => 'sometimes|integer|min:0',
                'e' => 'sometimes|numeric|min:0|max:100',
                'f' => 'sometimes|integer|min:0',
                'g' => 'sometimes|numeric|min:0|max:100',
                'h' => 'sometimes|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $asignatura = ReporteFinalAsignatura::findOrFail($id);

            // Actualizar solo los campos proporcionados
            $asignatura->fill($request->only(['a', 'b','bco', 'c', 'd', 'e', 'f', 'g', 'h']));
            $asignatura->save();

            // Recargar relaciones
            $asignatura->load(['asignatura', 'carrera']);

            return response()->json([
                'success' => true,
                'message' => 'Asignatura actualizada exitosamente',
                'data' => $asignatura
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la asignatura: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $asignatura = ReporteFinalAsignatura::findOrFail($id);
            $asignatura->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asignatura eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la asignatura: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update multiple asignaturas at once.
     */
    public function updateMultiple(Request $request, $id_reportefinal)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'asignaturas' => 'required|array',
                'asignaturas.*.id_reportefinal_asignatura' => 'required|exists:reportefinal_asignatura,id_reportefinal_asignatura',
                'asignaturas.*.a' => 'sometimes|integer|min:0',
                'asignaturas.*.b' => 'sometimes|integer|min:0',
                'asignaturas.*.bco' => 'sometimes|integer|min:0',
                'asignaturas.*.c' => 'sometimes|numeric|min:0|max:100',
                'asignaturas.*.d' => 'sometimes|integer|min:0',
                'asignaturas.*.e' => 'sometimes|numeric|min:0|max:100',
                'asignaturas.*.f' => 'sometimes|integer|min:0',
                'asignaturas.*.g' => 'sometimes|numeric|min:0|max:100',
                'asignaturas.*.h' => 'sometimes|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $asignaturasActualizadas = [];

            foreach ($request->asignaturas as $asignaturaData) {
                $asignatura = ReporteFinalAsignatura::where('id_reportefinal_asignatura', $asignaturaData['id_reportefinal_asignatura'])
                    ->where('id_reportefinal', $id_reportefinal)
                    ->firstOrFail();

                // Actualizar solo los campos proporcionados
                $asignatura->fill(array_filter($asignaturaData, function($key) {
                    return in_array($key, ['a', 'b', 'bco', 'c', 'd', 'e', 'f', 'g', 'h']);
                }, ARRAY_FILTER_USE_KEY));
                
                $asignatura->save();
                $asignaturasActualizadas[] = $asignatura->load(['asignatura', 'carrera']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Asignaturas actualizadas exitosamente',
                'data' => $asignaturasActualizadas
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar las asignaturas: ' . $e->getMessage()
            ], 500);
        }
    }
}