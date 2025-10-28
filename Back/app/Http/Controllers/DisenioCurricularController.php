<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DisenioCurricular;
use App\Models\DisenioCurricularParticipante;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DisenioCurricularController extends Controller
{
    // Obtener todos los diseños curriculares
    public function index()
    {
        try {
            $disenios = DisenioCurricular::with(['asignatura', 'participantes'])
                ->orderBy('FechaFinal', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $disenios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los diseños curriculares',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Obtener diseños curriculares por asignatura
    public function porAsignatura($claveAsignatura)
    {
        try {
            // Verificar que la asignatura existe
            $asignatura = Asignatura::findOrFail($claveAsignatura);

            $disenios = DisenioCurricular::with(['participantes'])
                ->where('ClaveAsignatura', $claveAsignatura)
                ->orderBy('FechaFinal', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'asignatura' => $asignatura,
                'data' => $disenios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los diseños curriculares de la asignatura'
            ], 404);
        }
    }

    // Obtener un diseño curricular específico
    public function show($id)
    {
        try {
            $disenio = DisenioCurricular::with(['asignatura', 'participantes'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $disenio
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Diseño curricular no encontrado'
            ], 404);
        }
    }

    // Crear un nuevo diseño curricular
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ClaveAsignatura' => 'required|string|max:20|exists:asignatura,ClaveAsignatura',
            'Lugar' => 'required|string|max:200',
            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'NombreEvento' => 'required|string|max:200',
            'Descripcion' => 'nullable|string',
            'participantes' => 'sometimes|array',
            'participantes.*.Instituto' => 'required_with:participantes|string|max:200'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                // Crear el diseño curricular
                $disenio = DisenioCurricular::create([
                    'ClaveAsignatura' => $request->ClaveAsignatura,
                    'Lugar' => $request->Lugar,
                    'FechaInicio' => $request->FechaInicio,
                    'FechaFinal' => $request->FechaFinal,
                    'NombreEvento' => $request->NombreEvento,
                    'Descripcion' => $request->Descripcion
                ]);

                // Agregar participantes si vienen en el request
                if ($request->has('participantes')) {
                    foreach ($request->participantes as $participante) {
                        $disenio->participantes()->create([
                            'Instituto' => $participante['Instituto']
                        ]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Diseño curricular creado exitosamente',
                    'data' => $disenio->load(['asignatura', 'participantes'])
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el diseño curricular',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Actualizar un diseño curricular
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'Lugar' => 'sometimes|string|max:200',
            'FechaInicio' => 'sometimes|date',
            'FechaFinal' => 'sometimes|date|after_or_equal:FechaInicio',
            'NombreEvento' => 'sometimes|string|max:200',
            'Descripcion' => 'nullable|string',
            'participantes' => 'sometimes|array',
            'participantes.*.IdParticipacion' => 'sometimes|integer|exists:disenio_curricular_participantes,IdParticipacion',
            'participantes.*.Instituto' => 'required_with:participantes|string|max:200',
            'participantes.*._delete' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request, $id) {
                $disenio = DisenioCurricular::with('participantes')->findOrFail($id);

                // Actualizar datos básicos
                $disenio->update($request->only([
                    'Lugar', 'FechaInicio', 'FechaFinal', 'NombreEvento', 'Descripcion'
                ]));

                // Procesar participantes
                if ($request->has('participantes')) {
                    foreach ($request->participantes as $participante) {
                        // Eliminar participante
                        if (isset($participante['_delete']) && $participante['_delete'] === true && isset($participante['IdParticipacion'])) {
                            $part = DisenioCurricularParticipante::where('IdParticipacion', $participante['IdParticipacion'])
                                ->where('id_disenio_curricular', $id)
                                ->firstOrFail();
                            $part->delete();
                            continue;
                        }

                        // Actualizar existente
                        if (isset($participante['IdParticipacion'])) {
                            $part = DisenioCurricularParticipante::where('IdParticipacion', $participante['IdParticipacion'])
                                ->where('id_disenio_curricular', $id)
                                ->firstOrFail();
                            $part->update(['Instituto' => $participante['Instituto']]);
                        } else {
                            // Crear nuevo
                            $disenio->participantes()->create([
                                'Instituto' => $participante['Instituto']
                            ]);
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Diseño curricular actualizado exitosamente',
                    'data' => $disenio->fresh(['asignatura', 'participantes'])
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el diseño curricular',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Eliminar un diseño curricular
    public function destroy($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $disenio = DisenioCurricular::withCount('participantes')->findOrFail($id);

                // Eliminar participantes primero
                $disenio->participantes()->delete();
                $disenio->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Diseño curricular eliminado exitosamente',
                    'deleted' => [
                        'id_disenio_curricular' => $disenio->id_disenio_curricular,
                        'participantes_eliminados' => $disenio->participantes_count
                    ]
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el diseño curricular',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Obtener participantes de un diseño curricular
    public function participantes($id)
    {
        try {
            $disenio = DisenioCurricular::with('participantes')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $disenio->participantes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Diseño curricular no encontrado'
            ], 404);
        }
    }

    // Agregar participante a un diseño curricular
    public function agregarParticipante(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'Instituto' => 'required|string|max:200'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $disenio = DisenioCurricular::findOrFail($id);

            $participante = $disenio->participantes()->create([
                'Instituto' => $request->Instituto
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Participante agregado exitosamente',
                'data' => $participante
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar participante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Eliminar participante específico
    public function eliminarParticipante($idDisenio, $idParticipante)
    {
        try {
            $participante = DisenioCurricularParticipante::where('IdParticipacion', $idParticipante)
                ->where('id_disenio_curricular', $idDisenio)
                ->firstOrFail();

            $participante->delete();

            return response()->json([
                'success' => true,
                'message' => 'Participante eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Participante no encontrado'
            ], 404);
        }
    }
}