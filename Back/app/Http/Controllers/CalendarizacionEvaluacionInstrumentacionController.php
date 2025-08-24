<?php

namespace App\Http\Controllers;

use App\Models\CalendarizacionEvaluacionInstrumentacion;
use Illuminate\Http\Request;

class CalendarizacionEvaluacionInstrumentacionController extends Controller
{
    // Crear una calendarización
    public function createOne(Request $request)
    {
        $request->validate([
            'id_instrumentacion' => 'required|exists:instrumentacion,id_instrumentacion',
            'semana' => 'required|integer|min:1',
            'tiempo_planeado' => 'nullable|string|max:100',
            'tiempo_real' => 'nullable|string|max:100',
            'seguimiento_departamental' => 'nullable|boolean',
            'descripcion' => 'nullable|string'
        ]);

        // Verificar que no exista ya esta semana para esta instrumentación
        $existente = CalendarizacionEvaluacionInstrumentacion::where('id_instrumentacion', $request->id_instrumentacion)
            ->where('semana', $request->semana)
            ->first();

        if ($existente) {
            return response()->json([
                'message' => 'Ya existe una calendarización para esta semana en esta instrumentación',
                'data' => $existente
            ], 409);
        }

        $calendarizacion = CalendarizacionEvaluacionInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Calendarización creada exitosamente',
            'data' => $calendarizacion
        ], 201);
    }

    // Crear múltiples calendarizaciones
    public function createMultiple(Request $request)
    {
        $request->validate([
            'calendarizaciones' => 'required|array',
            'calendarizaciones.*.id_instrumentacion' => 'required|exists:instrumentacion,id_instrumentacion',
            'calendarizaciones.*.semana' => 'required|integer|min:1',
            'calendarizaciones.*.tiempo_planeado' => 'nullable|string|max:100',
            'calendarizaciones.*.tiempo_real' => 'nullable|string|max:100',
            'calendarizaciones.*.seguimiento_departamental' => 'nullable|boolean',
            'calendarizaciones.*.descripcion' => 'nullable|string'
        ]);

        $calendarizacionesCreadas = [];
        $errores = [];

        foreach ($request->calendarizaciones as $calendarizacionData) {
            try {
                // Verificar que no exista ya esta semana para esta instrumentación
                $existente = CalendarizacionEvaluacionInstrumentacion::where('id_instrumentacion', $calendarizacionData['id_instrumentacion'])
                    ->where('semana', $calendarizacionData['semana'])
                    ->first();

                if ($existente) {
                    $errores[] = [
                        'data' => $calendarizacionData,
                        'error' => 'Ya existe una calendarización para la semana ' . $calendarizacionData['semana'] . ' en esta instrumentación'
                    ];
                    continue;
                }

                $calendarizacion = CalendarizacionEvaluacionInstrumentacion::create($calendarizacionData);
                $calendarizacionesCreadas[] = $calendarizacion;
            } catch (\Exception $e) {
                $errores[] = [
                    'data' => $calendarizacionData,
                    'error' => $e->getMessage()
                ];
            }
        }

        $response = [
            'message' => 'Proceso de creación de calendarizaciones completado',
            'data' => $calendarizacionesCreadas
        ];

        if (!empty($errores)) {
            $response['errores'] = $errores;
        }

        return response()->json($response, 201);
    }

    // Actualizar una calendarización
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'semana' => 'sometimes|required|integer|min:1',
            'tiempo_planeado' => 'nullable|string|max:100',
            'tiempo_real' => 'nullable|string|max:100',
            'seguimiento_departamental' => 'nullable|boolean',
            'descripcion' => 'nullable|string'
        ]);

        $calendarizacion = CalendarizacionEvaluacionInstrumentacion::findOrFail($id);

        // Si se cambia la semana, verificar que no exista conflicto
        if ($request->has('semana') && $request->semana != $calendarizacion->semana) {
            $existente = CalendarizacionEvaluacionInstrumentacion::where('id_instrumentacion', $calendarizacion->id_instrumentacion)
                ->where('semana', $request->semana)
                ->where('id_calendarizacion', '!=', $id)
                ->first();

            if ($existente) {
                return response()->json([
                    'message' => 'Ya existe otra calendarización para la semana ' . $request->semana . ' en esta instrumentación',
                    'data' => $existente
                ], 409);
            }
        }

        $calendarizacion->update($request->all());

        return response()->json([
            'message' => 'Calendarización actualizada exitosamente',
            'data' => $calendarizacion
        ]);
    }

    // Actualizar múltiples calendarizaciones
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'calendarizaciones' => 'required|array',
            'calendarizaciones.*.id_calendarizacion' => 'required|exists:calendarizacion_evaluacion_instrumentacion,id_calendarizacion',
            'calendarizaciones.*.semana' => 'sometimes|required|integer|min:1',
            'calendarizaciones.*.tiempo_planeado' => 'nullable|string|max:100',
            'calendarizaciones.*.tiempo_real' => 'nullable|string|max:100',
            'calendarizaciones.*.seguimiento_departamental' => 'nullable|boolean',
            'calendarizaciones.*.descripcion' => 'nullable|string'
        ]);

        $calendarizacionesActualizadas = [];
        $errores = [];

        foreach ($request->calendarizaciones as $calendarizacionData) {
            try {
                $calendarizacion = CalendarizacionEvaluacionInstrumentacion::findOrFail($calendarizacionData['id_calendarizacion']);

                // Si se cambia la semana, verificar que no exista conflicto
                if (isset($calendarizacionData['semana']) && $calendarizacionData['semana'] != $calendarizacion->semana) {
                    $existente = CalendarizacionEvaluacionInstrumentacion::where('id_instrumentacion', $calendarizacion->id_instrumentacion)
                        ->where('semana', $calendarizacionData['semana'])
                        ->where('id_calendarizacion', '!=', $calendarizacion->id_calendarizacion)
                        ->first();

                    if ($existente) {
                        $errores[] = [
                            'id_calendarizacion' => $calendarizacionData['id_calendarizacion'],
                            'error' => 'Ya existe otra calendarización para la semana ' . $calendarizacionData['semana'] . ' en esta instrumentación'
                        ];
                        continue;
                    }
                }

                $calendarizacion->update($calendarizacionData);
                $calendarizacionesActualizadas[] = $calendarizacion;
            } catch (\Exception $e) {
                $errores[] = [
                    'id_calendarizacion' => $calendarizacionData['id_calendarizacion'] ?? 'unknown',
                    'error' => $e->getMessage()
                ];
            }
        }

        $response = [
            'message' => 'Proceso de actualización de calendarizaciones completado',
            'data' => $calendarizacionesActualizadas
        ];

        if (!empty($errores)) {
            $response['errores'] = $errores;
        }

        return response()->json($response);
    }
}