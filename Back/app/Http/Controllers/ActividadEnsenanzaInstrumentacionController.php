<?php

namespace App\Http\Controllers;

use App\Models\ActividadEnsenanzaInstrumentacion;
use Illuminate\Http\Request;

class ActividadEnsenanzaInstrumentacionController extends Controller
{
    // Crear una actividad de enseñanza
    public function createOne(Request $request)
    {
        $request->validate([
            'id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer'
        ]);

        $actividad = ActividadEnsenanzaInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Actividad de enseñanza creada exitosamente',
            'data' => $actividad
        ], 201);
    }

    // Crear múltiples actividades de enseñanza
    public function createMultiple(Request $request)
    {
        $request->validate([
            'actividades' => 'required|array',
            'actividades.*.id_competencia' => 'required|exists:competencias_instrumentacion,id_competencia',
            'actividades.*.descripcion' => 'required|string',
            'actividades.*.orden' => 'nullable|integer'
        ]);

        $actividades = [];
        foreach ($request->actividades as $actividadData) {
            $actividades[] = ActividadEnsenanzaInstrumentacion::create($actividadData);
        }

        return response()->json([
            'message' => 'Actividades de enseñanza creadas exitosamente',
            'data' => $actividades
        ], 201);
    }

    // Actualizar una actividad de enseñanza
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer'
        ]);

        $actividad = ActividadEnsenanzaInstrumentacion::findOrFail($id);
        $actividad->update($request->all());

        return response()->json([
            'message' => 'Actividad de enseñanza actualizada exitosamente',
            'data' => $actividad
        ]);
    }

    // Actualizar múltiples actividades de enseñanza
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'actividades' => 'required|array',
            'actividades.*.id_actividades_ensenanza_instrumentacion' => 'required|exists:actividades_ensenanza_instrumentacion,id_actividades_ensenanza_instrumentacion',
            'actividades.*.descripcion' => 'sometimes|required|string',
            'actividades.*.orden' => 'nullable|integer'
        ]);

        $actividadesActualizadas = [];
        foreach ($request->actividades as $actividadData) {
            $actividad = ActividadEnsenanzaInstrumentacion::findOrFail($actividadData['id_actividades_ensenanza_instrumentacion']);
            $actividad->update($actividadData);
            $actividadesActualizadas[] = $actividad;
        }

        return response()->json([
            'message' => 'Actividades de enseñanza actualizadas exitosamente',
            'data' => $actividadesActualizadas
        ]);
    }
}