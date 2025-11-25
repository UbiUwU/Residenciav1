<?php

namespace App\Http\Controllers;

use App\Models\AvanceFecha;
use Illuminate\Http\Request;

class AvanceFechaController extends Controller
{
    // Obtener todas las fechas de avances
    public function index()
    {
        $fechas = AvanceFecha::with(['avance', 'fechaClave'])->get();

        return response()->json($fechas);
    }

    // Obtener una fecha de avance por ID
    public function show($id)
    {
        $fecha = AvanceFecha::with(['avance', 'fechaClave'])->find($id);

        if (! $fecha) {
            return response()->json(['message' => 'Fecha de avance no encontrada'], 404);
        }

        return response()->json($fecha);
    }

    // Crear una nueva fecha de avance
    public function store(Request $request)
    {
        $request->validate([
            'id_avance' => 'required|integer|exists:avance,id_avance',
            'id_fecha_clave' => 'required|integer|exists:fechas_clave_periodo,id_fecha_clave',
            'observaciones' => 'nullable|string',
            'fecha_real' => 'nullable|date',
        ]);

        $fecha = AvanceFecha::create($request->all());

        return response()->json($fecha, 201);
    }

    // Actualizar una fecha de avance
    public function update(Request $request, $id)
    {
        $fecha = AvanceFecha::find($id);

        if (! $fecha) {
            return response()->json(['message' => 'Fecha de avance no encontrada'], 404);
        }

        $request->validate([
            'id_avance' => 'sometimes|integer|exists:avance,id_avance',
            'id_fecha_clave' => 'sometimes|integer|exists:fechas_clave_periodo,id_fecha_clave',
            'observaciones' => 'nullable|string',
            'fecha_real' => 'nullable|date',
        ]);

        $fecha->update($request->all());

        return response()->json($fecha);
    }

    // Eliminar una fecha de avance
    public function destroy($id)
    {
        $fecha = AvanceFecha::find($id);

        if (! $fecha) {
            return response()->json(['message' => 'Fecha de avance no encontrada'], 404);
        }

        $fecha->delete();

        return response()->json(['message' => 'Fecha de avance eliminada correctamente']);
    }
}
