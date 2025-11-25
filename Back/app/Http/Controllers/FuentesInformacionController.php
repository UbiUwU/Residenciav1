<?php

namespace App\Http\Controllers;

use App\Models\FuentesInformacion;
use Illuminate\Http\Request;

class FuentesInformacionController extends Controller
{
    // Crear una fuente de información
    public function createOne(Request $request)
    {
        $request->validate([
            'ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer',
        ]);

        $fuente = FuentesInformacion::create($request->all());

        return response()->json([
            'message' => 'Fuente de información creada exitosamente',
            'data' => $fuente,
        ], 201);
    }

    // Crear múltiples fuentes de información
    public function createMultiple(Request $request)
    {
        $request->validate([
            'fuentes' => 'required|array',
            'fuentes.*.ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'fuentes.*.descripcion' => 'required|string',
            'fuentes.*.orden' => 'nullable|integer',
        ]);

        $fuentes = [];
        foreach ($request->fuentes as $fuenteData) {
            $fuentes[] = FuentesInformacion::create($fuenteData);
        }

        return response()->json([
            'message' => 'Fuentes de información creadas exitosamente',
            'data' => $fuentes,
        ], 201);
    }

    // Actualizar una fuente de información
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer',
        ]);

        $fuente = FuentesInformacion::findOrFail($id);
        $fuente->update($request->all());

        return response()->json([
            'message' => 'Fuente de información actualizada exitosamente',
            'data' => $fuente,
        ]);
    }

    // Actualizar múltiples fuentes de información
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'fuentes' => 'required|array',
            'fuentes.*.id_fuente' => 'required|exists:fuentes_informacion,id_fuente',
            'fuentes.*.descripcion' => 'sometimes|required|string',
            'fuentes.*.orden' => 'nullable|integer',
        ]);

        $fuentesActualizadas = [];
        foreach ($request->fuentes as $fuenteData) {
            $fuente = FuentesInformacion::findOrFail($fuenteData['id_fuente']);
            $fuente->update($fuenteData);
            $fuentesActualizadas[] = $fuente;
        }

        return response()->json([
            'message' => 'Fuentes de información actualizadas exitosamente',
            'data' => $fuentesActualizadas,
        ]);
    }

    // Eliminar una fuente de información
    public function deleteOne($id)
    {
        $fuente = FuentesInformacion::findOrFail($id);
        $fuente->delete();

        return response()->json([
            'message' => 'Fuente de información eliminada exitosamente',
        ]);
    }
}
