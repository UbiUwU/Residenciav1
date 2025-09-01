<?php

namespace App\Http\Controllers;

use App\Models\CargaAcademicaDetalle;
use App\Models\CargaAcademicaGeneral;
use App\Models\HorarioAsignaturaMaestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CargaAcademicaDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalles = CargaAcademicaDetalle::with([
            'cargaGeneral.alumno',
            'horarioAsignatura'
        ])->get();

        return response()->json($detalles);
    }

    /**
     * Store a newly created resource in storage.
     * Registra un alumno en un horario
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numerocontrol' => 'required|exists:alumnos,numerocontrol',
            'clavehorario' => 'required|exists:horarioasignatura_maestro,clavehorario'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Buscar o crear la carga general del alumno
            $cargaGeneral = CargaAcademicaGeneral::firstOrCreate([
                'numerocontrol' => $request->numerocontrol
            ]);

            // Verificar si ya está registrado en este horario
            $existente = CargaAcademicaDetalle::where('idcargageneral', $cargaGeneral->idcargageneral)
                ->where('clavehorario', $request->clavehorario)
                ->exists();

            if ($existente) {
                DB::rollBack();
                return response()->json([
                    'message' => 'El alumno ya está registrado en este horario'
                ], 409);
            }

            // Crear el detalle de carga académica
            $detalle = CargaAcademicaDetalle::create([
                'idcargageneral' => $cargaGeneral->idcargageneral,
                'clavehorario' => $request->clavehorario
            ]);

            // Cargar solo la información del horario para la respuesta
            $detalle->load('horarioAsignatura.asignatura');

            DB::commit();

            return response()->json([
                'message' => 'Alumno registrado en el horario exitosamente',
                'horario' => [
                    'clavehorario' => $detalle->horarioAsignatura->clavehorario,
                    'clave_asignatura' => $detalle->horarioAsignatura->asignatura->ClaveAsignatura
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar el alumno en el horario'
            ], 500);
        }
    }
    public function indexAlumnosByHorario($clavehorario)
    {
        $validator = Validator::make(
            ['clavehorario' => $clavehorario],
            [
                'clavehorario' => 'required|string|exists:horario_asignatura_maestro,clavehorario'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro inválido',
                'errors' => $validator->errors()
            ], 422);
        }

        $alumnos = CargaAcademicaDetalle::with([
            'cargaGeneral.alumno:numerocontrol,nombre,apellidopaterno,apellidomaterno'
        ])
            ->where('clavehorario', $clavehorario)
            ->get()
            ->pluck('cargaGeneral.alumno')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $alumnos,
            'count' => $alumnos->count(),
            'clavehorario' => $clavehorario
        ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $detalle = CargaAcademicaDetalle::with([
                'cargaGeneral.alumno',
                'horarioAsignatura.asignatura',
                'horarioAsignatura.maestro'
            ])->findOrFail($id);

            return response()->json($detalle);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registro no encontrado'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Elimina el registro de un alumno de un horario
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $detalle = CargaAcademicaDetalle::findOrFail($id);
            $detalle->delete();

            DB::commit();

            return response()->json([
                'message' => 'Registro eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al eliminar el registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los horarios de un alumno
     */
    public function horariosPorAlumno($numerocontrol)
    {
        try {
            $cargaGeneral = CargaAcademicaGeneral::with([
                'detalles.horarioAsignatura.asignatura',
                'detalles.horarioAsignatura.maestro'
            ])->where('numerocontrol', $numerocontrol)->first();

            if (!$cargaGeneral) {
                return response()->json([
                    'message' => 'El alumno no tiene horarios registrados'
                ], 404);
            }

            return response()->json([
                'alumno' => $cargaGeneral->alumno,
                'horarios' => $cargaGeneral->detalles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los horarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los alumnos de un horario
     */
    public function alumnosPorHorario($clavehorario)
    {
        try {
            $alumnos = CargaAcademicaDetalle::with([
                'cargaGeneral.alumno'
            ])->where('clavehorario', $clavehorario)->get();

            return response()->json([
                'horario' => $clavehorario,
                'total_alumnos' => $alumnos->count(),
                'alumnos' => $alumnos->map(function ($detalle) {
                    return $detalle->cargaGeneral->alumno;
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los alumnos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}