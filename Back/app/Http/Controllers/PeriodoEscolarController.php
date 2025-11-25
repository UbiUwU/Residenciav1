<?php

namespace App\Http\Controllers;

use App\Models\PeriodoEscolar;
use Illuminate\Http\Request;

class PeriodoEscolarController extends Controller
{
    public function index()
    {
        $periodos = PeriodoEscolar::with(['fechasClave', 'fechasClave.tipoFecha'])->get();

        return response()->json($periodos);
    }

    public function indexL()
    {
        $periodos = PeriodoEscolar::periodos()->get();

        return response()->json($periodos);

    }

    // En el Controller
    public function indexClean()
    {
        $periodos = PeriodoEscolar::with(['fechasClave.tipoFecha'])->get();

        $cleanPeriodos = $periodos->map(function ($periodo) {
            return [
                'id_periodo_escolar' => $periodo->id_periodo_escolar,
                'codigoperiodo' => $periodo->codigoperiodo,
                'nombre_periodo' => $periodo->nombre_periodo,
                'fecha_inicio' => $periodo->fecha_inicio,
                'fecha_fin' => $periodo->fecha_fin,
                'estado' => $periodo->estado,
                'fechas_clave' => $periodo->fechasClave->map(function ($fecha) {
                    return [
                        'id_fecha_clave' => $fecha->id_fecha_clave,
                        'nombre_fecha' => $fecha->nombre_fecha,
                        'descripcion' => $fecha->descripcion,
                        'fecha' => $fecha->fecha,
                        'fecha_limite' => $fecha->fecha_limite,
                        'obligatoria' => $fecha->es_obligatoria,
                        'tipo_fecha' => $fecha->tipoFecha ? [
                            'clave' => $fecha->tipoFecha->clave,
                            'nombre' => $fecha->tipoFecha->nombre,
                        ] : null,
                    ];
                }),
            ];
        });

        return response()->json($cleanPeriodos);
    }

    // Obtener un periodo escolar específico con sus fechas clave y tipos de fecha
    public function show($id)
    {
        $periodo = PeriodoEscolar::with(['fechasClave', 'fechasClave.tipoFecha'])->find($id);

        if (! $periodo) {
            return response()->json(['message' => 'Periodo escolar no encontrado'], 404);
        }

        return response()->json($periodo);
    }

    public function showclean($id)
    {
        $periodo = PeriodoEscolar::with(['fechasClave.tipoFecha'])->find($id);

        if (! $periodo) {
            return response()->json(['message' => 'Periodo escolar no encontrado'], 404);
        }

        $cleanPeriodo = [
            'id_periodo_escolar' => $periodo->id_periodo_escolar,
            'codigoperiodo' => $periodo->codigoperiodo,
            'nombre_periodo' => $periodo->nombre_periodo,
            'fecha_inicio' => $periodo->fecha_inicio,
            'fecha_fin' => $periodo->fecha_fin,
            'estado' => $periodo->estado,
            'fechas_clave' => $periodo->fechasClave->map(function ($fecha) {
                return [
                    'id_fecha_clave' => $fecha->id_fecha_clave,
                    'nombre_fecha' => $fecha->nombre_fecha,
                    'descripcion' => $fecha->descripcion,
                    'fecha' => $fecha->fecha,
                    'fecha_limite' => $fecha->fecha_limite,
                    'obligatoria' => $fecha->es_obligatoria,
                    'tipo_fecha' => $fecha->tipoFecha ? [
                        'clave' => $fecha->tipoFecha->clave,
                        'nombre' => $fecha->tipoFecha->nombre,
                    ] : null,
                ];
            }),
        ];

        return response()->json($cleanPeriodo);
    }

    // Crear un nuevo periodo escolar
    public function store(Request $request)
    {
        $request->validate([
            'codigoperiodo' => 'required|string|max:255',
            'nombre_periodo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string|max:50',
        ]);

        $periodo = PeriodoEscolar::create($request->all());

        return response()->json([
            'message' => 'Periodo escolar creado exitosamente',
            'data' => $periodo,
        ], 201);
    }

    // Actualizar un periodo escolar
    public function update(Request $request, $id)
    {
        $periodo = PeriodoEscolar::find($id);

        if (! $periodo) {
            return response()->json(['message' => 'Periodo escolar no encontrado'], 404);
        }

        $request->validate([
            'codigoperiodo' => 'sometimes|string|max:255',
            'nombre_periodo' => 'sometimes|string|max:255',
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin' => 'sometimes|date|after_or_equal:fecha_inicio',
            'estado' => 'sometimes|string|max:50',
        ]);

        $periodo->update($request->all());

        return response()->json([
            'message' => 'Periodo escolar actualizado exitosamente',
            'data' => $periodo,
        ]);
    }

    // Eliminar un periodo escolar
    public function destroy($id)
    {
        $periodo = PeriodoEscolar::find($id);

        if (! $periodo) {
            return response()->json(['message' => 'Periodo escolar no encontrado'], 404);
        }

        $periodo->delete();

        return response()->json(['message' => 'Periodo escolar eliminado exitosamente']);
    }
}
