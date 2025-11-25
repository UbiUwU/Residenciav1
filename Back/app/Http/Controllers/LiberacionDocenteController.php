<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\LiberacionDocente;
use App\Models\Maestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LiberacionDocenteController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = LiberacionDocente::with([
                'departamento' => function ($query) {
                    $query->select('id_departamento', 'nombre', 'abreviacion');
                },
                'maestro' => function ($query) {
                    $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
                },
                'periodoEscolar' => function ($query) {
                    $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
                },
                'detalles' => function ($query) {
                    $query->orderBy('numero_actividad');
                },
            ]);

            // Filtros por query parameters (opcionales)
            if ($request->has('departamento')) {
                $query->where('id_departamento', $request->departamento);
            }

            if ($request->has('maestro')) {
                $query->where('tarjeta_maestro', $request->maestro);
            }

            if ($request->has('periodo')) {
                $query->where('id_periodo_escolar', $request->periodo);
            }

            if ($request->has('otorgada')) {
                $query->where('otorga_liberacion', $request->boolean('otorgada'));
            }

            $liberaciones = $query->get();

            return response()->json([
                'success' => true,
                'data' => $liberaciones,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las liberaciones: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display liberaciones by departamento and periodo.
     */
    public function indexByDepartamentoPeriodo($id_departamento, $id_periodo_escolar)
    {
        try {
            $liberaciones = LiberacionDocente::with([
                'departamento' => function ($query) {
                    $query->select('id_departamento', 'nombre', 'abreviacion');
                },
                'maestro' => function ($query) {
                    $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
                },
                'periodoEscolar' => function ($query) {
                    $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
                },
                'detalles' => function ($query) {
                    $query->orderBy('numero_actividad');
                },
            ])
                ->where('id_departamento', $id_departamento)
                ->where('id_periodo_escolar', $id_periodo_escolar)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $liberaciones,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las liberaciones: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display liberaciones by departamento.
     */
    public function indexByDepartamento($id_departamento)
    {
        try {
            $liberaciones = LiberacionDocente::with([
                'departamento' => function ($query) {
                    $query->select('id_departamento', 'nombre', 'abreviacion');
                },
                'maestro' => function ($query) {
                    $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
                },
                'periodoEscolar' => function ($query) {
                    $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
                },
                'detalles' => function ($query) {
                    $query->orderBy('numero_actividad');
                },
            ])
                ->where('id_departamento', $id_departamento)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $liberaciones,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las liberaciones: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display liberaciones by periodo.
     */
    public function indexByPeriodo($id_periodo_escolar)
    {
        try {
            $liberaciones = LiberacionDocente::with([
                'departamento' => function ($query) {
                    $query->select('id_departamento', 'nombre', 'abreviacion');
                },
                'maestro' => function ($query) {
                    $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
                },
                'periodoEscolar' => function ($query) {
                    $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
                },
                'detalles' => function ($query) {
                    $query->orderBy('numero_actividad');
                },
            ])
                ->where('id_periodo_escolar', $id_periodo_escolar)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $liberaciones,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las liberaciones: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display liberaciones by maestro.
     */
    public function indexByMaestro($tarjeta_maestro)
    {
        try {
            $liberaciones = LiberacionDocente::with([
                'departamento' => function ($query) {
                    $query->select('id_departamento', 'nombre', 'abreviacion');
                },
                'maestro' => function ($query) {
                    $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
                },
                'periodoEscolar' => function ($query) {
                    $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
                },
                'detalles' => function ($query) {
                    $query->orderBy('numero_actividad');
                },
            ])
                ->where('tarjeta_maestro', $tarjeta_maestro)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $liberaciones,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las liberaciones: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display liberaciones by maestro and periodo.
     */
    public function indexByMaestroPeriodo($tarjeta_maestro, $id_periodo_escolar)
    {
        try {
            $liberaciones = LiberacionDocente::with([
                'departamento' => function ($query) {
                    $query->select('id_departamento', 'nombre', 'abreviacion');
                },
                'maestro' => function ($query) {
                    $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
                },
                'periodoEscolar' => function ($query) {
                    $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
                },
                'detalles' => function ($query) {
                    $query->orderBy('numero_actividad');
                },
            ])
                ->where('tarjeta_maestro', $tarjeta_maestro)
                ->where('id_periodo_escolar', $id_periodo_escolar)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $liberaciones,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las liberaciones: '.$e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'id_departamento' => 'required|exists:departamentos,id_departamento',
                'tarjeta_maestro' => 'required|exists:maestros,tarjeta',
                'id_periodo_escolar' => 'required|exists:periodo_escolar,id_periodo_escolar',
                'fecha_liberacion' => 'required|date',
                'otorga_liberacion' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Verificar si ya existe una liberación para este maestro y período
            $existe = LiberacionDocente::where('tarjeta_maestro', $request->tarjeta_maestro)
                ->where('id_periodo_escolar', $request->id_periodo_escolar)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una liberación para este maestro en el período especificado',
                ], 409);
            }

            // Crear la liberación docente
            $liberacion = LiberacionDocente::create([
                'id_departamento' => $request->id_departamento,
                'tarjeta_maestro' => $request->tarjeta_maestro,
                'id_periodo_escolar' => $request->id_periodo_escolar,
                'fecha_liberacion' => $request->fecha_liberacion,
                'otorga_liberacion' => $request->otorga_liberacion,
            ]);

            DB::commit();

            // Cargar relaciones para la respuesta
            $liberacion->load(['departamento', 'maestro', 'periodoEscolar']);

            return response()->json([
                'success' => true,
                'message' => 'Liberación docente creada exitosamente',
                'data' => $liberacion,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la liberación docente: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $liberacion = LiberacionDocente::with([
                'departamento' => function ($query) {
                    $query->select('id_departamento', 'nombre', 'abreviacion');
                },
                'maestro' => function ($query) {
                    $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
                },
                'periodoEscolar' => function ($query) {
                    $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo', 'fecha_inicio', 'fecha_fin');
                },
                'detalles' => function ($query) {
                    $query->orderBy('numero_actividad');
                },
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $liberacion,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Liberación docente no encontrada: '.$e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'fecha_liberacion' => 'sometimes|date',
                'otorga_liberacion' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $liberacion = LiberacionDocente::findOrFail($id);

            // Actualizar solo los campos proporcionados
            if ($request->has('fecha_liberacion')) {
                $liberacion->fecha_liberacion = $request->fecha_liberacion;
            }

            if ($request->has('otorga_liberacion')) {
                $liberacion->otorga_liberacion = $request->otorga_liberacion;
            }

            $liberacion->save();

            DB::commit();

            // Recargar relaciones
            $liberacion->load(['departamento', 'maestro', 'periodoEscolar', 'detalles']);

            return response()->json([
                'success' => true,
                'message' => 'Liberación docente actualizada exitosamente',
                'data' => $liberacion,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la liberación docente: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $liberacion = LiberacionDocente::findOrFail($id);
            $liberacion->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Liberación docente eliminada exitosamente',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la liberación docente: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener liberación por maestro y período
     */
    public function getByMaestroPeriodo($tarjeta_maestro, $id_periodo_escolar)
    {
        try {
            $liberacion = LiberacionDocente::with([
                'departamento' => function ($query) {
                    $query->select('id_departamento', 'nombre', 'abreviacion');
                },
                'maestro' => function ($query) {
                    $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
                },
                'periodoEscolar' => function ($query) {
                    $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
                },
                'detalles' => function ($query) {
                    $query->orderBy('numero_actividad');
                },
            ])
                ->where('tarjeta_maestro', $tarjeta_maestro)
                ->where('id_periodo_escolar', $id_periodo_escolar)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $liberacion,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Liberación no encontrada para el maestro y período especificados',
            ], 404);
        }
    }

    /**
     * Cambiar estado de otorgamiento de liberación
     */
    public function cambiarEstadoLiberacion(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otorga_liberacion' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $liberacion = LiberacionDocente::findOrFail($id);
            $liberacion->otorga_liberacion = $request->otorga_liberacion;
            $liberacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado de liberación actualizado exitosamente',
                'data' => $liberacion,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de liberación: '.$e->getMessage(),
            ], 500);
        }
    }
}
