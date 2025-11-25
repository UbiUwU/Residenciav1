<?php

namespace App\Http\Controllers;

use App\Models\FechasClavePeriodo;
use Illuminate\Http\Request;

class FechasClavePeriodoController extends Controller
{
    /**
     * Listar todas las fechas clave (opcional: filtradas por periodo).
     */
    public function index(Request $request)
    {
        $query = FechasClavePeriodo::with(['periodoEscolar', 'tipoCatalogo']);

        if ($request->has('periodo_escolar_id')) {
            $query->where('periodo_escolar_id', $request->periodo_escolar_id);
        }

        return response()->json($query->get(), 200);
    }

    /**
     * Mostrar una fecha clave específica
     */
    public function show($id)
    {
        $fecha = FechasClavePeriodo::with(['periodoEscolar', 'tipoCatalogo'])->findOrFail($id);

        return response()->json($fecha, 200);
    }

    /**
     * Crear nueva fecha clave
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'periodo_escolar_id' => 'required|integer|exists:periodo_escolar,id_periodo_escolar',
            'tipo_fecha_clave' => 'required|string|max:50',
            'nombre_fecha' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'fecha_limite' => 'nullable|date',
            'es_obligatoria' => 'boolean',
        ]);

        $fecha = FechasClavePeriodo::create($data);

        return response()->json($fecha, 201);
    }

    /**
     * Actualizar fecha clave
     */
    public function update(Request $request, $id)
    {
        $fecha = FechasClavePeriodo::findOrFail($id);

        $data = $request->validate([
            'tipo_fecha_clave' => 'sometimes|required|string|max:50',
            'nombre_fecha' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha' => 'sometimes|required|date',
            'fecha_limite' => 'nullable|date',
            'es_obligatoria' => 'boolean',
        ]);

        $fecha->update($data);

        return response()->json($fecha, 200);
    }

    /**
     * Eliminar fecha clave
     */
    public function destroy($id)
    {
        $fecha = FechasClavePeriodo::findOrFail($id);
        $fecha->delete();

        return response()->json(['message' => 'Fecha clave eliminada'], 200);
    }
}
