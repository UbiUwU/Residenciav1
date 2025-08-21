<?php

namespace App\Http\Controllers;

use App\Models\AvanceDetalleFecha;
use Illuminate\Http\Request;

class AvanceDetalleFechaController extends Controller
{
    /**
     * Listar todas las fechas (opcionalmente filtradas por id_avance_detalle).
     */
    public function index(Request $request)
    {
        $query = AvanceDetalleFecha::query();

        if ($request->has('id_avance_detalle')) {
            $query->where('id_avance_detalle', $request->id_avance_detalle);
        }

        return response()->json($query->get());
    }

    /**
     * Registrar nuevas fechas para un detalle.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_avance_detalle'   => 'required|exists:avance_detalles,id_avance_detalle',
            'fecha_inicio'        => 'required|date',
            'fecha_fin'           => 'required|date|after_or_equal:fecha_inicio',
            'fecha_inicio_real'   => 'nullable|date',
            'fecha_fin_real'      => 'nullable|date|after_or_equal:fecha_inicio_real',
            'fecha_evaluacion'    => 'nullable|date',
            'fecha_evaluacion_real'=> 'nullable|date|after_or_equal:fecha_inicio_real',
        ]);

        $fecha = AvanceDetalleFecha::create($validated);

        return response()->json([
            'message' => 'Fechas registradas correctamente',
            'data' => $fecha
        ], 201);
    }

    /**
     * Mostrar un registro en específico.
     */
    public function show($id)
    {
        $fecha = AvanceDetalleFecha::findOrFail($id);
        return response()->json($fecha);
    }

    /**
     * Actualizar fechas.
     */
    public function update(Request $request, $id)
    {
        $fecha = AvanceDetalleFecha::findOrFail($id);

        $validated = $request->validate([
            'fecha_inicio'        => 'sometimes|required|date',
            'fecha_fin'           => 'sometimes|required|date|after_or_equal:fecha_inicio',
            'fecha_inicio_real'   => 'nullable|date',
            'fecha_fin_real'      => 'nullable|date|after_or_equal:fecha_inicio_real',
            'fecha_evaluacion'    => 'nullable|date',
            'fecha_evaluacion_real'=> 'nullable|date|after_or_equal:fecha_inicio_real',
        ]);

        $fecha->update($validated);

        return response()->json([
            'message' => 'Fechas actualizadas correctamente',
            'data' => $fecha
        ]);
    }

    /**
     * Eliminar un registro.
     */
    public function destroy($id)
    {
        $fecha = AvanceDetalleFecha::findOrFail($id);
        $fecha->delete();

        return response()->json(['message' => 'Registro eliminado correctamente']);
    }
}
