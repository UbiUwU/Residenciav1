<?php

namespace App\Http\Controllers;

use App\Models\NivelDesempenoInstrumentacion;
use Illuminate\Http\Request;

class NivelDesempenoInstrumentacionController extends Controller
{
    // Crear un nivel de desempeño
    public function createOne(Request $request)
    {
        $request->validate([
            'id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'nivel_desempeno' => 'required|string|max:100',
            'valoracion_inicial' => 'required|integer|min:0',
            'valoracion_final' => 'required|integer|min:0',
            'desempeno_alcanzado' => 'nullable|boolean',
        ]);

        if ($request->valoracion_inicial > $request->valoracion_final) {
            return response()->json([
                'message' => 'La valoración inicial no puede ser mayor que la valoración final',
            ], 422);
        }

        $nivelDesempeno = NivelDesempenoInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Nivel de desempeño creado exitosamente',
            'data' => $nivelDesempeno,
        ], 201);
    }

    // Crear múltiples niveles de desempeño
    public function createMultiple(Request $request)
    {
        $request->validate([
            'niveles_desempeno' => 'required|array',
            'niveles_desempeno.*.id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'niveles_desempeno.*.nivel_desempeno' => 'required|string|max:100',
            'niveles_desempeno.*.valoracion_inicial' => 'required|integer|min:0',
            'niveles_desempeno.*.valoracion_final' => 'required|integer|min:0',
            'niveles_desempeno.*.desempeno_alcanzado' => 'nullable|boolean',
        ]);

        $nivelesCreados = [];
        $errores = [];

        foreach ($request->niveles_desempeno as $nivelData) {
            if ($nivelData['valoracion_inicial'] > $nivelData['valoracion_final']) {
                $errores[] = [
                    'data' => $nivelData,
                    'error' => 'La valoración inicial no puede ser mayor que la valoración final',
                ];

                continue;
            }

            try {
                $nivel = NivelDesempenoInstrumentacion::create($nivelData);
                $nivelesCreados[] = $nivel;
            } catch (\Exception $e) {
                $errores[] = [
                    'data' => $nivelData,
                    'error' => $e->getMessage(),
                ];
            }
        }

        $response = [
            'message' => 'Proceso de creación de niveles de desempeño completado',
            'data' => $nivelesCreados,
        ];

        if (! empty($errores)) {
            $response['errores'] = $errores;
        }

        return response()->json($response, 201);
    }

    // Actualizar un nivel de desempeño
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'nivel_desempeno' => 'sometimes|required|string|max:100',
            'valoracion_inicial' => 'sometimes|required|integer|min:0',
            'valoracion_final' => 'sometimes|required|integer|min:0',
            'desempeno_alcanzado' => 'nullable|boolean',
        ]);

        if ($request->has('valoracion_inicial') && $request->has('valoracion_final') &&
            $request->valoracion_inicial > $request->valoracion_final) {
            return response()->json([
                'message' => 'La valoración inicial no puede ser mayor que la valoración final',
            ], 422);
        }

        $nivelDesempeno = NivelDesempenoInstrumentacion::findOrFail($id);
        $nivelDesempeno->update($request->all());

        return response()->json([
            'message' => 'Nivel de desempeño actualizado exitosamente',
            'data' => $nivelDesempeno,
        ]);
    }

    // Actualizar múltiples niveles de desempeño
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'niveles_desempeno' => 'required|array',
            'niveles_desempeno.*.id_nivel_desempeno' => 'required|exists:niveles_desempeno_instrumentacion,id_nivel_desempeno',
            'niveles_desempeno.*.nivel_desempeno' => 'sometimes|required|string|max:100',
            'niveles_desempeno.*.valoracion_inicial' => 'sometimes|required|integer|min:0',
            'niveles_desempeno.*.valoracion_final' => 'sometimes|required|integer|min:0',
            'niveles_desempeno.*.desempeno_alcanzado' => 'nullable|boolean',
        ]);

        $nivelesActualizados = [];
        $errores = [];

        foreach ($request->niveles_desempeno as $nivelData) {
            try {
                $nivel = NivelDesempenoInstrumentacion::findOrFail($nivelData['id_nivel_desempeno']);

                if (isset($nivelData['valoracion_inicial']) && isset($nivelData['valoracion_final']) &&
                    $nivelData['valoracion_inicial'] > $nivelData['valoracion_final']) {
                    $errores[] = [
                        'id_nivel_desempeno' => $nivelData['id_nivel_desempeno'],
                        'error' => 'La valoración inicial no puede ser mayor que la valoración final',
                    ];

                    continue;
                }

                $nivel->update($nivelData);
                $nivelesActualizados[] = $nivel;
            } catch (\Exception $e) {
                $errores[] = [
                    'id_nivel_desempeno' => $nivelData['id_nivel_desempeno'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        $response = [
            'message' => 'Proceso de actualización de niveles de desempeño completado',
            'data' => $nivelesActualizados,
        ];

        if (! empty($errores)) {
            $response['errores'] = $errores;
        }

        return response()->json($response);
    }
}
