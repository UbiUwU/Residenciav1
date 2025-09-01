<?php

namespace App\Http\Controllers;

use App\Models\CargaAcademicaDetalle;
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

    public function indexAlumnosByHorario($clavehorario)
    {
        // Validar el parámetro
        $validator = Validator::make(
            ['clavehorario' => $clavehorario],
            [
                'clavehorario' => 'required|integer|exists:horarioasignatura_maestro,clavehorario'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro inválido',
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener el horario con los alumnos inscritos
        $horario = HorarioAsignaturaMaestro::with([
            'maestro' => function ($query) {
                $query->soloNombre();
            },
            'aula',
            'grupo',
            'asignatura',
            'periodoEscolar',
            'cargaDetalles.cargaGeneral.alumno' => function ($query) {
                $query->select('numerocontrol', 'nombre', 'apellidopaterno', 'apellidomaterno');
            }
        ])->find($clavehorario);

        if (!$horario) {
            return response()->json([
                'success' => false,
                'message' => 'Horario no encontrado'
            ], 404);
        }

        // Extraer y formatear la lista de alumnos
        $alumnos = collect();

        foreach ($horario->cargaDetalles as $detalle) {
            if ($detalle->cargaGeneral && $detalle->cargaGeneral->alumno) {
                $alumnos->push([
                    'idcargageneral' => $detalle->cargaGeneral->idcargageneral,
                    'idcargadetalle' => $detalle->idcargadetalle,
                    'alumno' => $detalle->cargaGeneral->alumno,
                    'numerocontrol' => $detalle->cargaGeneral->alumno->numerocontrol,
                    'nombre_completo' => $detalle->cargaGeneral->alumno->nombre . ' ' .
                        $detalle->cargaGeneral->alumno->apellidopaterno . ' ' .
                        $detalle->cargaGeneral->alumno->apellidomaterno
                ]);
            }
        }

        // Información del horario
        $infoHorario = [
            'clavehorario' => $horario->clavehorario,
            'asignatura' => $horario->asignatura ? $horario->asignatura->NombreAsignatura : null,
            'claveasignatura' => $horario->claveasignatura,
            'clavegrupo' => $horario->clavegrupo,
            'maestro' => $horario->maestro ? $horario->maestro->nombre_completo : null,
            'tarjeta' => $horario->tarjeta,
            'periodo' => $horario->periodoEscolar ? $horario->periodoEscolar->nombre_periodo : null,
            'idperiodoescolar' => $horario->idperiodoescolar
        ];

        return response()->json([
            'success' => true,
            'horario' => $infoHorario,
            'alumnos' => $alumnos,
            'total_alumnos' => $alumnos->count(),
            'data' => [
                'horario_info' => $infoHorario,
                'alumnos_list' => $alumnos
            ]
        ], 200);
    }

    // Versión alternativa que devuelve solo la lista de alumnos (más ligera)
    public function indexAlumnosByHorarioSimple($clavehorario)
    {
        // Validar el parámetro
        $validator = Validator::make(
            ['clavehorario' => $clavehorario],
            [
                'clavehorario' => 'required|integer|exists:horarioasignatura_maestro,clavehorario'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro inválido',
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener solo los alumnos del horario (consulta más eficiente)
        $alumnos = CargaAcademicaDetalle::with([
            'cargaGeneral.alumno:numerocontrol,nombre,apellidopaterno,apellidomaterno'
        ])
            ->where('clavehorario', $clavehorario)
            ->get()
            ->map(function ($detalle) {
                if ($detalle->cargaGeneral && $detalle->cargaGeneral->alumno) {
                    return [
                        'idcargadetalle' => $detalle->idcargadetalle,
                        'idcargageneral' => $detalle->cargaGeneral->idcargageneral,
                        'numerocontrol' => $detalle->cargaGeneral->alumno->numerocontrol,
                        'nombre' => $detalle->cargaGeneral->alumno->nombre,
                        'apellido_paterno' => $detalle->cargaGeneral->alumno->apellidopaterno,
                        'apellido_materno' => $detalle->cargaGeneral->alumno->apellidomaterno,
                        'nombre_completo' => $detalle->cargaGeneral->alumno->nombre . ' ' .
                            $detalle->cargaGeneral->alumno->apellidopaterno . ' ' .
                            $detalle->cargaGeneral->alumno->apellidomaterno
                    ];
                }
                return null;
            })
            ->filter() // Eliminar elementos null
            ->values(); // Reindexar array

        // Obtener información básica del horario
        $horarioInfo = HorarioAsignaturaMaestro::with([
            'asignatura:ClaveAsignatura,NombreAsignatura',
            'grupo:clavegrupo',
            'maestro:tarjeta,nombre',
            'aula:claveaula',
        ])
            ->find($clavehorario, [
                'clavehorario',
                'claveasignatura',
                'clavegrupo',
                'tarjeta',
                'idperiodoescolar',
                'claveaula'
            ]);

        if (!$horarioInfo) {
            return response()->json([
                'success' => false,
                'message' => 'Horario no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'horario_info' => [
                'clavehorario' => $horarioInfo->clavehorario,
                'asignatura' => $horarioInfo->asignatura ? $horarioInfo->asignatura->NombreAsignatura : null,
                'claveasignatura' => $horarioInfo->claveasignatura,
                'clavegrupo' => $horarioInfo->clavegrupo,
                'maestro' => $horarioInfo->maestro ? $horarioInfo->maestro->nombre : null,
                'tarjeta' => $horarioInfo->tarjeta,
                'claveaula' => $horarioInfo->aula ? $horarioInfo->aula->claveaula : null,
            ],
            'alumnos' => $alumnos,
            'total_alumnos' => $alumnos->count()
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
