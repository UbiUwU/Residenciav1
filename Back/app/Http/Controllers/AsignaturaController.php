<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Carrera;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsignaturaController extends Controller
{
    // Listar todas las asignaturas
    public function index()
    {
        $asignaturas = Asignatura::with('carreras')->get();

        return response()->json($asignaturas, 200);
    }

    public function indexC()
    {
        $asignaturas = Asignatura::all();

        return response()->json($asignaturas, 200);
    }

    // Mostrar una asignatura específica
    public function show($ClaveAsignatura)
    {
        $asignatura = Asignatura::with([
            'carreras',
            'presentacion',
            'competencias',
            'diseniosCurriculares.participantes',
            'temasConSubtemas' => function ($query) {
                $query->with([
                    'competenciasGenericas',
                    'competenciasEspecificas',
                    'actividadesAprendizaje',
                ]);
            },
            'practias',
            'proyectos',
            'evaluacionesCompetencias',
            'fuentesInformacion',
        ])->find($ClaveAsignatura);

        if (! $asignatura) {
            return response()->json(['message' => 'Asignatura no encontrada'], 404);
        }

        return response()->json($asignatura, 200);
    }

    public function indexByCarrera($clavecarrera)
    {
        // Validar que la carrera exista
        $validator = Validator::make(
            ['clavecarrera' => $clavecarrera],
            ['clavecarrera' => 'required|string|exists:carreras,clavecarrera']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Carrera no encontrada',
                'errors' => $validator->errors(),
            ], 404);
        }

        // Filtrar asignaturas por carrera (solo datos de asignatura, sin relaciones)
        $asignaturas = Asignatura::whereHas('carreras', function ($query) use ($clavecarrera) {
            $query->where('clavecarrera', $clavecarrera);
        })->get();

        // Obtener el nombre de la carrera para la respuesta
        $carrera = Carrera::where('clavecarrera', $clavecarrera)->first();

        return response()->json([
            'success' => true,
            'data' => $asignaturas,
            'count' => $asignaturas->count(),
        ], 200);
    }

    // Crear una nueva asignatura
    public function store(Request $request)
    {
        $request->validate([
            'ClaveAsignatura' => 'required|string|max:20|unique:asignatura,ClaveAsignatura',
            'NombreAsignatura' => 'required|string|max:100',
            'Creditos' => 'required|integer|min:0',
            'Satca_Practicas' => 'required|integer|min:0',
            'Satca_Teoricas' => 'required|integer|min:0',
            'Satca_Total' => 'required|integer|min:0',
            'carreras' => 'nullable|array', // Arreglo de carreras
            'carreras.*.clavecarrera' => 'required_with:carreras|string|exists:carreras,clavecarrera',
            'carreras.*.Semestre' => 'required_with:carreras|integer|min:1',
            'carreras.*.Posicion' => 'required_with:carreras|integer|min:1',
        ]);

        try {
            $asignatura = DB::transaction(function () use ($request) {

                // Crear la asignatura
                $asignatura = Asignatura::create($request->only([
                    'ClaveAsignatura',
                    'NombreAsignatura',
                    'Creditos',
                    'Satca_Practicas',
                    'Satca_Teoricas',
                    'Satca_Total',
                ]));

                // Asociar carreras si se proporcionan
                if ($request->has('carreras')) {
                    $carrerasData = [];
                    foreach ($request->carreras as $c) {
                        $carrerasData[$c['clavecarrera']] = [
                            'Semestre' => $c['Semestre'],
                            'Posicion' => $c['Posicion'],
                        ];
                    }
                    $asignatura->carreras()->attach($carrerasData);
                }

                return $asignatura->load('carreras'); // Cargar relaciones
            });

            return response()->json([
                'message' => 'Asignatura creada exitosamente',
                'asignatura' => $asignatura,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la asignatura',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $ClaveAsignatura)
    {
        $asignatura = Asignatura::find($ClaveAsignatura);
        if (! $asignatura) {
            return response()->json(['message' => 'Asignatura no encontrada'], 404);
        }

        $request->validate([
            'NombreAsignatura' => 'sometimes|required|string|max:100',
            'Creditos' => 'sometimes|required|integer|min:0',
            'Satca_Practicas' => 'sometimes|required|integer|min:0',
            'Satca_Teoricas' => 'sometimes|required|integer|min:0',
            'Satca_Total' => 'sometimes|required|integer|min:0',
            'carreras' => 'nullable|array',
            'carreras.*.clavecarrera' => 'required_with:carreras|string|exists:carreras,clavecarrera',
            'carreras.*.Semestre' => 'required_with:carreras|integer|min:1',
            'carreras.*.Posicion' => 'required_with:carreras|integer|min:1',
        ]);

        try {
            $updatedAsignatura = DB::transaction(function () use ($asignatura, $request) {

                // Actualizar datos de la asignatura
                $asignatura->update($request->only([
                    'NombreAsignatura',
                    'Creditos',
                    'Satca_Practicas',
                    'Satca_Teoricas',
                    'Satca_Total',
                ]));

                // Sincronizar carreras si se proporcionan
                if ($request->has('carreras')) {
                    $carrerasData = [];
                    foreach ($request->carreras as $c) {
                        $carrerasData[$c['clavecarrera']] = [
                            'Semestre' => $c['Semestre'],
                            'Posicion' => $c['Posicion'],
                        ];
                    }
                    $asignatura->carreras()->sync($carrerasData); // Reemplaza las anteriores
                }

                return $asignatura->load('carreras'); // Cargar relaciones actualizadas
            });

            return response()->json([
                'message' => 'Asignatura actualizada exitosamente',
                'asignatura' => $updatedAsignatura,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la asignatura', 'error' => $e->getMessage()], 400);
        }
    }

    // Eliminar una asignatura
    public function destroy($ClaveAsignatura)
    {
        $asignatura = Asignatura::find($ClaveAsignatura);
        if (! $asignatura) {
            return response()->json(['message' => 'Asignatura no encontrada'], 404);
        }

        try {
            $asignatura->delete();

            return response()->json(['message' => 'Asignatura eliminada exitosamente'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
