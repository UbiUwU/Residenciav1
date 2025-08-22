<?php

namespace App\Http\Controllers;

use App\Models\ReporteFinal;
use App\Models\DatosEstaticosReporteFinal;
use App\Models\ReporteFinalAsignatura;
use App\Models\Maestro;
use App\Models\PeriodoEscolar;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReporteFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    try {
        $reportes = ReporteFinal::with([
            'maestro' => function($query) {
                $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
            },
            'periodoEscolar' => function($query) {
                $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
            },
            'departamento' => function($query) {
                $query->select('id_departamento', 'nombre');
            },
            'datosEstaticos',
            'asignaturas' => function($query) {
                $query->select('id_reportefinal_asignatura', 'id_reportefinal', 'clave_asignatura', 'clave_carrera', 
                             'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
            },
            'asignaturas.asignatura' => function($query) {
                $query->select('ClaveAsignatura', 'NombreAsignatura', 'Creditos');
            },
            'asignaturas.carrera' => function($query) {
                $query->select('clavecarrera', 'nombre');
            }
        ])->get();
        

        return response()->json([
            'success' => true,
            'data' => $reportes
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener los reportes: ' . $e->getMessage()
        ], 500);
    }
}
 public function update(Request $request, $id)
    {
        // Validamos lo que sí se puede actualizar
        $validated = $request->validate([
            'tarjeta_profesor'   => 'sometimes|integer',
            'id_periodo_escolar' => 'sometimes|integer',
            'id_departamento'    => 'sometimes|integer',
            'estado'             => 'sometimes|string|in:borrador,activo,inactivo,eliminado'
        ]);

        // Buscar el registro o fallar con 404
        $reporte = ReporteFinal::findOrFail($id);

        // Actualizar con los datos validados
        $reporte->update($validated);

        return response()->json([
            'message' => 'Reporte Final actualizado correctamente',
            'data'    => $reporte
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'tarjeta_profesor' => 'required|exists:maestros,tarjeta',
                'id_periodo_escolar' => 'required|exists:periodo_escolar,id_periodo_escolar',
                'id_departamento' => 'required|exists:departamentos,id_departamento',
                'estado' => 'sometimes|in:borrador,enviado,aprobado,rechazado',
                'datos_estaticos' => 'sometimes|array',
                'datos_estaticos.numero_grupos_atendidos' => 'sometimes|integer|min:0',
                'datos_estaticos.numero_estudiantes' => 'sometimes|integer|min:0',
                'datos_estaticos.numero_asignaturas_diferentes' => 'sometimes|integer|min:0',
                'asignaturas' => 'sometimes|array',
                'asignaturas.*.clave_asignatura' => 'required_with:asignaturas|exists:asignatura,ClaveAsignatura',
                'asignaturas.*.clave_carrera' => 'required_with:asignaturas|exists:carreras,clavecarrera',
                'asignaturas.*.a' => 'sometimes|integer|min:0',
                'asignaturas.*.b' => 'sometimes|integer|min:0',
                'asignaturas.*.c' => 'sometimes|numeric|min:0|max:100',
                'asignaturas.*.d' => 'sometimes|integer|min:0',
                'asignaturas.*.e' => 'sometimes|numeric|min:0|max:100',
                'asignaturas.*.f' => 'sometimes|integer|min:0',
                'asignaturas.*.g' => 'sometimes|numeric|min:0|max:100',
                'asignaturas.*.h' => 'sometimes|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de entrada inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear el reporte final
            $reporteFinal = ReporteFinal::create([
                'tarjeta_profesor' => $request->tarjeta_profesor,
                'id_periodo_escolar' => $request->id_periodo_escolar,
                'id_departamento' => $request->id_departamento,
                'estado' => $request->estado ?? 'borrador'
            ]);

            // Crear datos estáticos si se proporcionan
            if ($request->has('datos_estaticos')) {
                DatosEstaticosReporteFinal::create([
                    'id_reportefinal' => $reporteFinal->id_reportefinal,
                    'numero_grupos_atendidos' => $request->datos_estaticos['numero_grupos_atendidos'] ?? 0,
                    'numero_estudiantes' => $request->datos_estaticos['numero_estudiantes'] ?? 0,
                    'numero_asignaturas_diferentes' => $request->datos_estaticos['numero_asignaturas_diferentes'] ?? 0
                ]);
            }

            // Crear asignaturas si se proporcionan
            if ($request->has('asignaturas')) {
                foreach ($request->asignaturas as $asignaturaData) {
                    ReporteFinalAsignatura::create([
                        'id_reportefinal' => $reporteFinal->id_reportefinal,
                        'clave_asignatura' => $asignaturaData['clave_asignatura'],
                        'clave_carrera' => $asignaturaData['clave_carrera'],
                        'a' => $asignaturaData['a'] ?? 0,
                        'b' => $asignaturaData['b'] ?? 0,
                        'c' => $asignaturaData['c'] ?? 0,
                        'd' => $asignaturaData['d'] ?? 0,
                        'e' => $asignaturaData['e'] ?? 0,
                        'f' => $asignaturaData['f'] ?? 0,
                        'g' => $asignaturaData['g'] ?? 0,
                        'h' => $asignaturaData['h'] ?? 0
                    ]);
                }
            }

            DB::commit();

            // Cargar relaciones para la respuesta
            $reporteFinal->load(['maestro', 'periodoEscolar', 'departamento', 'datosEstaticos', 'asignaturas']);

            return response()->json([
                'success' => true,
                'message' => 'Reporte final creado exitosamente',
                'data' => $reporteFinal
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el reporte final: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $reporte = ReporteFinal::with([
                 'maestro' => function($query) {
                $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
            },
            'periodoEscolar' => function($query) {
                $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
            },
            'departamento' => function($query) {
                $query->select('id_departamento', 'nombre');
            },
            'datosEstaticos',
            'asignaturas' => function($query) {
                $query->select('id_reportefinal_asignatura', 'id_reportefinal', 'clave_asignatura', 'clave_carrera', 
                             'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
            },
            'asignaturas.asignatura' => function($query) {
                $query->select('ClaveAsignatura', 'NombreAsignatura', 'Creditos');
            },
            'asignaturas.carrera' => function($query) {
                $query->select('clavecarrera', 'nombre');
            }
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $reporte
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reporte final no encontrado: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $reporte = ReporteFinal::findOrFail($id);
            $reporte->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reporte final eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el reporte final: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener reportes por maestro y período
     */
    public function getByMaestroPeriodo($tarjeta, $id_periodo_escolar)
    {
        try {
            $reportes = ReporteFinal::with([
                 'maestro' => function($query) {
                $query->select('tarjeta', 'nombre', 'apellidopaterno', 'apellidomaterno');
            },
            'periodoEscolar' => function($query) {
                $query->select('id_periodo_escolar', 'nombre_periodo', 'codigoperiodo');
            },
            'departamento' => function($query) {
                $query->select('id_departamento', 'nombre');
            },
            'datosEstaticos',
            'asignaturas' => function($query) {
                $query->select('id_reportefinal_asignatura', 'id_reportefinal', 'clave_asignatura', 'clave_carrera', 
                             'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
            },
            'asignaturas.asignatura' => function($query) {
                $query->select('ClaveAsignatura', 'NombreAsignatura', 'Creditos');
            },
            'asignaturas.carrera' => function($query) {
                $query->select('clavecarrera', 'nombre');
            }
            ])
                ->where('tarjeta_profesor', $tarjeta)
                ->where('id_periodo_escolar', $id_periodo_escolar)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $reportes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los reportes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar estado del reporte
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'estado' => 'required|in:borrador,enviado,aprobado,rechazado'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estado inválido',
                    'errors' => $validator->errors()
                ], 422);
            }

            $reporte = ReporteFinal::findOrFail($id);
            $reporte->estado = $request->estado;
            $reporte->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado del reporte actualizado exitosamente',
                'data' => $reporte
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }
}