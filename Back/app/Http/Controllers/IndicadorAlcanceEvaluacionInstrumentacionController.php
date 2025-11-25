<?php

namespace App\Http\Controllers;

use App\Models\IndicadorAlcanceEvaluacionInstrumentacion;
use Illuminate\Http\Request;

class IndicadorAlcanceEvaluacionInstrumentacionController extends Controller
{
    // Crear un indicador de alcance para evaluación
    public function createOne(Request $request)
    {
        $request->validate([
            'id_evaluacion_competencia' => 'required|exists:evaluacion_competencias_instrumentacion,id_evaluacion_competencia',
            'letra_indicador' => 'required|string|max:5',
            'descripcion' => 'nullable|string',
            'porcentaje' => 'nullable|integer|min:0|max:100',
            'orden' => 'nullable|integer',
        ]);

        // Verificar que no exista ya esta letra para esta evaluación
        $existente = IndicadorAlcanceEvaluacionInstrumentacion::where('id_evaluacion_competencia', $request->id_evaluacion_competencia)
            ->where('letra_indicador', $request->letra_indicador)
            ->first();

        if ($existente) {
            return response()->json([
                'message' => 'Ya existe un indicador con esta letra para esta evaluación',
                'data' => $existente,
            ], 409);
        }

        $indicador = IndicadorAlcanceEvaluacionInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Indicador de alcance para evaluación creado exitosamente',
            'data' => $indicador,
        ], 201);
    }

    // Crear múltiples indicadores de alcance para evaluación
    public function createMultiple(Request $request)
    {
        $request->validate([
            'indicadores' => 'required|array',
            'indicadores.*.id_evaluacion_competencia' => 'required|exists:evaluacion_competencias_instrumentacion,id_evaluacion_competencia',
            'indicadores.*.letra_indicador' => 'required|string|max:5',
            'indicadores.*.descripcion' => 'nullable|string',
            'indicadores.*.porcentaje' => 'nullable|integer|min:0|max:100',
            'indicadores.*.orden' => 'nullable|integer',
        ]);

        $indicadoresCreados = [];
        $errores = [];

        foreach ($request->indicadores as $indicadorData) {
            try {
                // Verificar que no exista ya esta letra para esta evaluación
                $existente = IndicadorAlcanceEvaluacionInstrumentacion::where('id_evaluacion_competencia', $indicadorData['id_evaluacion_competencia'])
                    ->where('letra_indicador', $indicadorData['letra_indicador'])
                    ->first();

                if ($existente) {
                    $errores[] = [
                        'data' => $indicadorData,
                        'error' => 'Ya existe un indicador con la letra '.$indicadorData['letra_indicador'].' para esta evaluación',
                    ];

                    continue;
                }

                $indicador = IndicadorAlcanceEvaluacionInstrumentacion::create($indicadorData);
                $indicadoresCreados[] = $indicador;
            } catch (\Exception $e) {
                $errores[] = [
                    'data' => $indicadorData,
                    'error' => $e->getMessage(),
                ];
            }
        }

        $response = [
            'message' => 'Proceso de creación de indicadores de alcance para evaluación completado',
            'data' => $indicadoresCreados,
        ];

        if (! empty($errores)) {
            $response['errores'] = $errores;
        }

        return response()->json($response, 201);
    }

    // Actualizar un indicador de alcance para evaluación
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'letra_indicador' => 'sometimes|required|string|max:5',
            'descripcion' => 'nullable|string',
            'porcentaje' => 'nullable|integer|min:0|max:100',
            'orden' => 'nullable|integer',
        ]);

        $indicador = IndicadorAlcanceEvaluacionInstrumentacion::findOrFail($id);

        // Si se cambia la letra, verificar que no exista conflicto
        if ($request->has('letra_indicador') && $request->letra_indicador != $indicador->letra_indicador) {
            $existente = IndicadorAlcanceEvaluacionInstrumentacion::where('id_evaluacion_competencia', $indicador->id_evaluacion_competencia)
                ->where('letra_indicador', $request->letra_indicador)
                ->where('id_indicador_alcance', '!=', $id)
                ->first();

            if ($existente) {
                return response()->json([
                    'message' => 'Ya existe otro indicador con la letra '.$request->letra_indicador.' para esta evaluación',
                    'data' => $existente,
                ], 409);
            }
        }

        $indicador->update($request->all());

        return response()->json([
            'message' => 'Indicador de alcance para evaluación actualizado exitosamente',
            'data' => $indicador,
        ]);
    }

    // Actualizar múltiples indicadores de alcance para evaluación
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'indicadores' => 'required|array',
            'indicadores.*.id_indicador_alcance' => 'required|exists:indicadores_alcance_evaluacion_instrumentacion,id_indicador_alcance',
            'indicadores.*.letra_indicador' => 'sometimes|required|string|max:5',
            'indicadores.*.descripcion' => 'nullable|string',
            'indicadores.*.porcentaje' => 'nullable|integer|min:0|max:100',
            'indicadores.*.orden' => 'nullable|integer',
        ]);

        $indicadoresActualizados = [];
        $errores = [];

        foreach ($request->indicadores as $indicadorData) {
            try {
                $indicador = IndicadorAlcanceEvaluacionInstrumentacion::findOrFail($indicadorData['id_indicador_alcance']);

                // Si se cambia la letra, verificar que no exista conflicto
                if (isset($indicadorData['letra_indicador']) && $indicadorData['letra_indicador'] != $indicador->letra_indicador) {
                    $existente = IndicadorAlcanceEvaluacionInstrumentacion::where('id_evaluacion_competencia', $indicador->id_evaluacion_competencia)
                        ->where('letra_indicador', $indicadorData['letra_indicador'])
                        ->where('id_indicador_alcance', '!=', $indicador->id_indicador_alcance)
                        ->first();

                    if ($existente) {
                        $errores[] = [
                            'id_indicador_alcance' => $indicadorData['id_indicador_alcance'],
                            'error' => 'Ya existe otro indicador con la letra '.$indicadorData['letra_indicador'].' para esta evaluación',
                        ];

                        continue;
                    }
                }

                $indicador->update($indicadorData);
                $indicadoresActualizados[] = $indicador;
            } catch (\Exception $e) {
                $errores[] = [
                    'id_indicador_alcance' => $indicadorData['id_indicador_alcance'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        $response = [
            'message' => 'Proceso de actualización de indicadores de alcance para evaluación completado',
            'data' => $indicadoresActualizados,
        ];

        if (! empty($errores)) {
            $response['errores'] = $errores;
        }

        return response()->json($response);
    }
}
