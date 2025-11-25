<?php

namespace App\Http\Controllers;

use App\Models\Practiasasignatura;
use Illuminate\Http\Request;

class PractiasasignaturaController extends Controller
{
    // Crear una práctica
    public function createOne(Request $request)
    {
        $request->validate([
            'ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer',
        ]);

        $practica = Practiasasignatura::create($request->all());

        return response()->json([
            'message' => 'Práctica creada exitosamente',
            'data' => $practica,
        ], 201);
    }

    // Crear múltiples prácticas
    public function createMultiple(Request $request)
    {
        $request->validate([
            'practicas' => 'required|array',
            'practicas.*.ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'practicas.*.descripcion' => 'required|string',
            'practicas.*.orden' => 'nullable|integer',
        ]);

        $practicas = [];
        foreach ($request->practicas as $practicaData) {
            $practicas[] = Practiasasignatura::create($practicaData);
        }

        return response()->json([
            'message' => 'Prácticas creadas exitosamente',
            'data' => $practicas,
        ], 201);
    }

    // Actualizar una práctica
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer',
        ]);

        $practica = Practiasasignatura::findOrFail($id);
        $practica->update($request->all());

        return response()->json([
            'message' => 'Práctica actualizada exitosamente',
            'data' => $practica,
        ]);
    }

    // Actualizar múltiples prácticas
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'practicas' => 'required|array',
            'practicas.*.id_practicas' => 'required|exists:practias_asignatura,id_practicas',
            'practicas.*.descripcion' => 'sometimes|required|string',
            'practicas.*.orden' => 'nullable|integer',
        ]);

        $practicasActualizadas = [];
        foreach ($request->practicas as $practicaData) {
            $practica = Practiasasignatura::findOrFail($practicaData['id_practicas']);
            $practica->update($practicaData);
            $practicasActualizadas[] = $practica;
        }

        return response()->json([
            'message' => 'Prácticas actualizadas exitosamente',
            'data' => $practicasActualizadas,
        ]);
    }

    // Eliminar una práctica
    public function deleteOne($id)
    {
        $practica = Practiasasignatura::findOrFail($id);
        $practica->delete();

        return response()->json([
            'message' => 'Práctica eliminada exitosamente',
        ]);
    }
}
