<?php

namespace App\Http\Controllers;

use App\Models\HorarioAsignaturaMaestro;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class HorarioAsignaturaMaestroController extends Controller
{
    // Listar todos los horarios
    public function index()
    {
        $horarios = HorarioAsignaturaMaestro::with([
            'maestro' => function ($query) {
                $query->soloNombre();
            },
            'aula',
            'grupo',
            'asignatura',
            'periodoEscolar'
        ])->get();

        return response()->json($horarios, 200);
    }

    // Mostrar un horario específico
    public function show($clavehorario)
    {
        $horario = HorarioAsignaturaMaestro::with([
            'maestro',
            'aula',
            'grupo',
            'asignatura',
            'periodoEscolar'
        ])->find($clavehorario);

        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        return response()->json($horario, 200);
    }

    public function indexByPeriodoAndMaestro($idperiodoescolar, $tarjeta)
    {
        // Validar los parámetros
        $validator = Validator::make(
            ['idperiodoescolar' => $idperiodoescolar, 'tarjeta' => $tarjeta],
            [
                'idperiodoescolar' => 'required|integer|exists:periodo_escolar,id_periodo_escolar',
                'tarjeta' => 'required|string|exists:maestros,tarjeta'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetros inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $horarios = HorarioAsignaturaMaestro::with([
            'aula',
            'grupo',
            'asignatura'
        ])
            ->where('idperiodoescolar', $idperiodoescolar)
            ->where('tarjeta', $tarjeta)
            ->orderBy('claveasignatura')
            ->get();

        if ($horarios->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No se encontraron horarios para el maestro en el período especificado',
                'data' => [],
                'count' => 0
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $horarios,
            'count' => $horarios->count()
        ], 200);
    }

    public function indexByPeriodoAndCarrera($idperiodoescolar, $clavecarrera)
    {
        // Validar los parámetros
        $validator = Validator::make(
            [
                'idperiodoescolar' => $idperiodoescolar,
                'clavecarrera' => $clavecarrera
            ],
            [
                'idperiodoescolar' => 'required|integer|exists:periodo_escolar,id_periodo_escolar',
                'clavecarrera' => 'required|string|exists:carreras,clavecarrera'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetros inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $horarios = HorarioAsignaturaMaestro::with([
            'maestro' => function ($query) {
                $query->soloNombre();
            },
            'aula',
            'grupo',
            'asignatura',
        ])
            ->whereHas('asignatura.carreras', function ($query) use ($clavecarrera) {
                $query->where('clavecarrera', $clavecarrera);
            })
            ->where('idperiodoescolar', $idperiodoescolar)
            ->orderBy('claveasignatura')
            ->get();

        // Filtrar solo las asignaturas que pertenecen a la carrera especificada
        $horarios->each(function ($horario) use ($clavecarrera) {
            if ($horario->asignatura) {
                $horario->asignatura->carreras = $horario->asignatura->carreras->filter(function ($carrera) use ($clavecarrera) {
                    return $carrera->clavecarrera === $clavecarrera;
                })->values();
            }
        });

        if ($horarios->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No se encontraron horarios para la carrera en el período especificado',
                'data' => [],
                'count' => 0
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $horarios,
            'count' => $horarios->count()
        ], 200);
    }

    public function indexByMaestro($tarjeta)
    {
        // Validar el parámetro
        $validator = Validator::make(
            ['tarjeta' => $tarjeta],
            [
                'tarjeta' => 'required|string|exists:maestros,tarjeta'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro inválido',
                'errors' => $validator->errors()
            ], 422);
        }

        $horarios = HorarioAsignaturaMaestro::with([
            'maestro' => function ($query) {
                $query->soloNombre();
            },
            'aula',
            'grupo',
            'asignatura',
            'periodoEscolar'
        ])
            ->where('tarjeta', $tarjeta)
            ->orderBy('idperiodoescolar')
            ->orderBy('claveasignatura')
            ->get();

        if ($horarios->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No se encontraron horarios para el maestro especificado',
                'data' => [],
                'count' => 0
            ], 200);
        }
        // Agrupar horarios por período escolar para mejor organización
        $horariosAgrupados = $horarios->groupBy('idperiodoescolar');

        return response()->json([
            'success' => true,
            'data' => $horariosAgrupados,
            'count' => $horarios->count(),
            'periodos_count' => $horariosAgrupados->count()
        ], 200);
    }
    // Crear un nuevo horario
    public function store(Request $request)
    {
        $request->validate([
            'tarjeta' => 'required|integer|exists:maestros,tarjeta',
            'claveaula' => 'required|string|exists:aulas,claveaula',
            'clavegrupo' => 'required|string|exists:grupos,clavegrupo',
            'claveasignatura' => 'required|string|exists:asignatura,ClaveAsignatura',
            'idperiodoescolar' => 'required|integer|exists:periodo_escolar,id_periodo_escolar',
            'lunes_hi' => 'nullable|date_format:H:i:s',
            'lunes_hf' => 'nullable|date_format:H:i:s',
            'martes_hi' => 'nullable|string|max:100',
            'martes_hf' => 'nullable|string|max:100',
            'miercoles_hi' => 'nullable|string|max:100',
            'miercoles_hf' => 'nullable|string|max:100',
            'jueves_hi' => 'nullable|string|max:100',
            'jueves_hf' => 'nullable|string|max:100',
            'viernes_hi' => 'nullable|string|max:100',
            'viernes_hf' => 'nullable|string|max:100',
            'sabado_hi' => 'nullable|string|max:100',
            'sabado_hf' => 'nullable|string|max:100'
        ]);

        try {
            $horario = DB::transaction(function () use ($request) {
                return HorarioAsignaturaMaestro::create($request->all());
            });

            $horario->load(['maestro.usuario.rol', 'aula', 'grupo', 'asignatura', 'periodoEscolar']);

            return response()->json([
                'message' => 'Horario creado exitosamente',
                'horario' => $horario
            ], 201);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Error al crear el horario',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // Actualizar un horario existente
    public function update(Request $request, $clavehorario)
    {
        $horario = HorarioAsignaturaMaestro::find($clavehorario);

        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        $request->validate([
            'tarjeta' => 'sometimes|required|integer|exists:maestros,tarjeta',
            'claveaula' => 'sometimes|required|string|exists:aulas,claveaula',
            'clavegrupo' => 'sometimes|required|string|exists:grupos,clavegrupo',
            'claveasignatura' => 'sometimes|required|string|exists:asignatura,ClaveAsignatura',
            'idperiodoescolar' => 'sometimes|required|integer|exists:periodo_escolar,id_periodo_escolar',
            'lunes_hi' => 'nullable|date_format:H:i:s',
            'lunes_hf' => 'nullable|date_format:H:i:s',
            'martes_hi' => 'nullable|string|max:100',
            'martes_hf' => 'nullable|string|max:100',
            'miercoles_hi' => 'nullable|string|max:100',
            'miercoles_hf' => 'nullable|string|max:100',
            'jueves_hi' => 'nullable|string|max:100',
            'jueves_hf' => 'nullable|string|max:100',
            'viernes_hi' => 'nullable|string|max:100',
            'viernes_hf' => 'nullable|string|max:100',
            'sabado_hi' => 'nullable|string|max:100',
            'sabado_hf' => 'nullable|string|max:100'
        ]);

        try {
            $horario->update($request->all());

            $horario->load(['maestro.usuario.rol', 'aula', 'grupo', 'asignatura', 'periodoEscolar']);

            return response()->json([
                'message' => 'Horario actualizado exitosamente',
                'horario' => $horario
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Error al actualizar el horario',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // Eliminar un horario
    public function destroy($clavehorario)
    {
        $horario = HorarioAsignaturaMaestro::find($clavehorario);

        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        $horario->delete();

        return response()->json(['message' => 'Horario eliminado exitosamente'], 200);
    }
}
