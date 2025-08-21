<?php

namespace App\Http\Controllers;

use App\Models\PeriodoEscolar;
use Illuminate\Http\Request;

class PeriodoEscolarController extends Controller
{
    // Obtener todos los periodos escolares
    // Listar todos los periodos con sus fechas clave
    public function index()
    {
        $periodos = PeriodoEscolar::with('fechasClaveConCatalogo')->get();
        return response()->json($periodos);
    }

    public function indexL()
    {
        $periodos = PeriodoEscolar::periodos()
            ->get();

        return response()->json($periodos);

    }

    public function indexLFC()
    {
        $periodos = PeriodoEscolar::periodos()
            ->with([
                'fechasClave' => function ($query) {
                    $query->fechasClave(); 
                }
            ])
            ->get();

        return response()->json($periodos);
    }

    // Obtener un periodo escolar específico con sus fechas clave
    public function show($id)
    {
        $periodo = PeriodoEscolar::with('fechasClaveConCatalogo')->find($id);

        if (!$periodo) {
            return response()->json(['message' => 'Periodo escolar no encontrado'], 404);
        }

        return response()->json($periodo);
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
            'data' => $periodo
        ], 201);
    }

    // Actualizar un periodo escolar
    public function update(Request $request, $id)
    {
        $periodo = PeriodoEscolar::find($id);

        if (!$periodo) {
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
            'data' => $periodo
        ]);
    }

    // Eliminar un periodo escolar
    public function destroy($id)
    {
        $periodo = PeriodoEscolar::find($id);

        if (!$periodo) {
            return response()->json(['message' => 'Periodo escolar no encontrado'], 404);
        }

        $periodo->delete();

        return response()->json(['message' => 'Periodo escolar eliminado exitosamente']);
    }
}
