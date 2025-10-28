<?php

namespace App\Http\Controllers;

use App\Models\DatosEstaticosReporteFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DatosEstaticosReporteFinalController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_reportefinal)
    {
        try {
            $validator = Validator::make($request->all(), [
                'numero_grupos_atendidos' => 'required|integer|min:0',
                'numero_estudiantes' => 'required|integer|min:0',
                'numero_asignaturas_diferentes' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar o crear datos estáticos
            $datosEstaticos = DatosEstaticosReporteFinal::firstOrNew(
                ['id_reportefinal' => $id_reportefinal]
            );

            $datosEstaticos->id_reportefinal = $id_reportefinal;
            $datosEstaticos->numero_grupos_atendidos = $request->numero_grupos_atendidos;
            $datosEstaticos->numero_estudiantes = $request->numero_estudiantes;
            $datosEstaticos->numero_asignaturas_diferentes = $request->numero_asignaturas_diferentes;
            $datosEstaticos->save();

            // Cargar relación con el reporte final
            $datosEstaticos->load('reporteFinal');

            return response()->json([
                'success' => true,
                'message' => 'Datos estáticos actualizados exitosamente',
                'data' => $datosEstaticos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar datos estáticos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_reportefinal)
    {
        try {
            $datosEstaticos = DatosEstaticosReporteFinal::with('reporteFinal')
                ->where('id_reportefinal', $id_reportefinal)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $datosEstaticos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos estáticos no encontrados: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_reportefinal)
    {
        try {
            $datosEstaticos = DatosEstaticosReporteFinal::where('id_reportefinal', $id_reportefinal)
                ->firstOrFail();
            
            $datosEstaticos->delete();

            return response()->json([
                'success' => true,
                'message' => 'Datos estáticos eliminados exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar datos estáticos: ' . $e->getMessage()
            ], 500);
        }
    }
}