<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Presentacion;
use App\Models\Asignatura;
use App\Models\PresentacionCaracterizacion;
use App\Models\PresentacionIntencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PresentacionController extends Controller
{
    // Crear una nueva presentación (solo si no existe) - No destructivo
    public function store(Request $request, $claveAsignatura)
    {
        $validator = Validator::make($request->all(), [
            'caracterizacion' => 'sometimes|array',
            'caracterizacion.*.punto' => 'required_with:caracterizacion|string|max:1000',
            'caracterizacion.*.orden' => 'sometimes|integer|min:1',
            'intencion' => 'sometimes|array',
            'intencion.*.tema' => 'sometimes|string|max:100|nullable',
            'intencion.*.descripcion' => 'required_with:intencion|string|max:2000',
            'intencion.*.orden' => 'sometimes|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que la asignatura existe
        $asignatura = Asignatura::findOrFail($claveAsignatura);

        try {
            return DB::transaction(function () use ($asignatura, $request) {

                // Verificar si ya existe la presentación
                $presentacion = Presentacion::where('Clave_Asignatura', $asignatura->ClaveAsignatura)
                    ->first();

                if ($presentacion) {
                    return response()->json([
                        'message' => 'La presentación ya existe. Use PUT para actualizar.',
                        'data' => $presentacion
                    ], 409); // Conflict
                }

                // Crear nueva presentación
                $presentacion = Presentacion::create([
                    'Clave_Asignatura' => $asignatura->ClaveAsignatura
                ]);

                // Agregar caracterizaciones si vienen en el request
                if ($request->has('caracterizacion')) {
                    foreach ($request->caracterizacion as $index => $item) {
                        $presentacion->caracterizaciones()->create([
                            'Orden' => $item['orden'] ?? $index + 1,
                            'Punto' => $item['punto']
                        ]);
                    }
                }

                // Agregar intenciones si vienen en el request
                if ($request->has('intencion')) {
                    foreach ($request->intencion as $index => $item) {
                        $presentacion->intenciones()->create([
                            'Orden' => $item['orden'] ?? $index + 1,
                            'Tema' => $item['tema'],
                            'Descripcion' => $item['descripcion']
                        ]);
                    }
                }

                return response()->json([
                    'message' => 'Presentación creada exitosamente',
                    'data' => $presentacion->load([
                        'caracterizaciones' => function ($query) {
                            $query->orderBy('Orden');
                        },
                        'intenciones' => function ($query) {
                            $query->orderBy('Orden');
                        }
                    ])
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la presentación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Obtener la presentación completa de una asignatura
    public function show($claveAsignatura)
    {
        try {
            $presentacion = Presentacion::with([
                'caracterizaciones' => function ($query) {
                    $query->orderBy('Orden');
                },
                'intenciones' => function ($query) {
                    $query->orderBy('Orden');
                }
            ])
                ->where('Clave_Asignatura', $claveAsignatura)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $presentacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la presentación para la asignatura especificada'
            ], 404);
        }
    }

    // Actualizar la presentación de forma incremental
    public function update(Request $request, $claveAsignatura)
    {
        $validator = Validator::make($request->all(), [
            'caracterizacion' => 'sometimes|array',
            'caracterizacion.*.id_Caracterizacion' => 'sometimes|integer|exists:presentacion_caracterizacion,id_Caracterizacion',
            'caracterizacion.*.punto' => 'required_with:caracterizacion|string|max:1000',
            'caracterizacion.*.orden' => 'sometimes|integer|min:1',
            'caracterizacion.*._delete' => 'sometimes|boolean', // Para eliminar

            'intencion' => 'sometimes|array',
            'intencion.*.id_Intencion' => 'sometimes|integer|exists:presentacion_intencion,id_Intencion',
            'intencion.*.tema' => 'sometimes|string|max:100|nullable',
            'intencion.*.descripcion' => 'required_with:intencion|string|max:2000',
            'intencion.*.orden' => 'sometimes|integer|min:1',
            'intencion.*._delete' => 'sometimes|boolean' // Para eliminar
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($claveAsignatura, $request) {

                $presentacion = Presentacion::where('Clave_Asignatura', $claveAsignatura)
                    ->firstOrFail();

                // Procesar caracterizaciones
                if ($request->has('caracterizacion')) {
                    foreach ($request->caracterizacion as $item) {

                        // Eliminar
                        if (isset($item['_delete']) && $item['_delete'] === true && isset($item['id_Caracterizacion'])) {
                            $caracterizacion = PresentacionCaracterizacion::where('id_Caracterizacion', $item['id_Caracterizacion'])
                                ->where('id_Presentacion', $presentacion->id_Presentacion)
                                ->firstOrFail();
                            $caracterizacion->delete();
                            continue;
                        }

                        // Actualizar existente
                        if (isset($item['id_Caracterizacion'])) {
                            $caracterizacion = PresentacionCaracterizacion::where('id_Caracterizacion', $item['id_Caracterizacion'])
                                ->where('id_Presentacion', $presentacion->id_Presentacion)
                                ->firstOrFail();

                            $caracterizacion->update([
                                'Punto' => $item['punto'],
                                'Orden' => $item['orden'] ?? $caracterizacion->Orden
                            ]);
                        } else {
                            // Crear nueva
                            $presentacion->caracterizaciones()->create([
                                'Orden' => $item['orden'] ?? 1,
                                'Punto' => $item['punto']
                            ]);
                        }
                    }
                }

                // Procesar intenciones
                if ($request->has('intencion')) {
                    foreach ($request->intencion as $item) {

                        // Eliminar
                        if (isset($item['_delete']) && $item['_delete'] === true && isset($item['id_Intencion'])) {
                            $intencion = PresentacionIntencion::where('id_Intencion', $item['id_Intencion'])
                                ->where('id_Presentacion', $presentacion->id_Presentacion)
                                ->firstOrFail();
                            $intencion->delete();
                            continue;
                        }

                        // Actualizar existente
                        if (isset($item['id_Intencion'])) {
                            $intencion = PresentacionIntencion::where('id_Intencion', $item['id_Intencion'])
                                ->where('id_Presentacion', $presentacion->id_Presentacion)
                                ->firstOrFail();

                            $intencion->update([
                                'Tema' => $item['tema'],
                                'Descripcion' => $item['descripcion'],
                                'Orden' => $item['orden'] ?? $intencion->Orden
                            ]);
                        } else {
                            // Crear nueva
                            $presentacion->intenciones()->create([
                                'Orden' => $item['orden'] ?? 1,
                                'Tema' => $item['tema'],
                                'Descripcion' => $item['descripcion']
                            ]);
                        }
                    }
                }

                return response()->json([
                    'message' => 'Presentación actualizada exitosamente',
                    'data' => $presentacion->load([
                        'caracterizaciones' => function ($query) {
                            $query->orderBy('Orden');
                        },
                        'intenciones' => function ($query) {
                            $query->orderBy('Orden');
                        }
                    ])
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la presentación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Eliminar la presentación completa de una asignatura
    public function destroy($claveAsignatura)
{
    try {
        // Verificar primero si la asignatura existe
        $asignatura = Asignatura::where('ClaveAsignatura', $claveAsignatura)->first();
        
        if (!$asignatura) {
            return response()->json([
                'message' => 'La asignatura no existe',
                'error' => 'Asignatura no encontrada'
            ], 404);
        }

        // Buscar la presentación
        $presentacion = Presentacion::where('Clave_Asignatura', $claveAsignatura)->first();

        if (!$presentacion) {
            return response()->json([
                'message' => 'No existe presentación para esta asignatura',
                'error' => 'Presentación no encontrada'
            ], 404);
        }

        DB::transaction(function () use ($presentacion) {
            // Eliminar en el orden correcto (primero las dependencias)
            $presentacion->caracterizaciones()->delete();
            $presentacion->intenciones()->delete();
            $presentacion->delete();
        });

        return response()->json([
            'message' => 'Presentación eliminada exitosamente',
            'deleted' => [
                'presentacion_id' => $presentacion->id_Presentacion,
                'asignatura' => $presentacion->Clave_Asignatura,
                'caracterizaciones_eliminadas' => $presentacion->caracterizaciones()->count(),
                'intenciones_eliminadas' => $presentacion->intenciones()->count()
            ]
        ]);

    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json([
            'message' => 'Error de base de datos al eliminar la presentación',
            'error' => $e->getMessage()
        ], 500);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error inesperado al eliminar la presentación',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Verificar si existe presentación para una asignatura
    public function exists($claveAsignatura)
    {
        $exists = Presentacion::where('Clave_Asignatura', $claveAsignatura)
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }
}