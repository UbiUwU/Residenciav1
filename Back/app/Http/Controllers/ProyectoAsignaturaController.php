<?php

namespace App\Http\Controllers;

use App\Models\ProyectoAsignatura;
use Illuminate\Http\Request;

class ProyectoAsignaturaController extends Controller
{
    // Crear un proyecto de asignatura
    public function createOne(Request $request)
    {
        $request->validate([
            'ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer',
        ]);

        $proyecto = ProyectoAsignatura::create($request->all());

        return response()->json([
            'message' => 'Proyecto de asignatura creado exitosamente',
            'data' => $proyecto,
        ], 201);
    }

    // Crear múltiples proyectos de asignatura
    public function createMultiple(Request $request)
    {
        $request->validate([
            'proyectos' => 'required|array',
            'proyectos.*.ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'proyectos.*.descripcion' => 'required|string',
            'proyectos.*.orden' => 'nullable|integer',
        ]);

        $proyectos = [];
        foreach ($request->proyectos as $proyectoData) {
            $proyectos[] = ProyectoAsignatura::create($proyectoData);
        }

        return response()->json([
            'message' => 'Proyectos de asignatura creados exitosamente',
            'data' => $proyectos,
        ], 201);
    }

    // Actualizar un proyecto de asignatura
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer',
        ]);

        $proyecto = ProyectoAsignatura::findOrFail($id);
        $proyecto->update($request->all());

        return response()->json([
            'message' => 'Proyecto de asignatura actualizado exitosamente',
            'data' => $proyecto,
        ]);
    }

    // Actualizar múltiples proyectos de asignatura
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'proyectos' => 'required|array',
            'proyectos.*.id_proyecto' => 'required|exists:proyecto_asignatura,id_proyecto',
            'proyectos.*.descripcion' => 'sometimes|required|string',
            'proyectos.*.orden' => 'nullable|integer',
        ]);

        $proyectosActualizados = [];
        foreach ($request->proyectos as $proyectoData) {
            $proyecto = ProyectoAsignatura::findOrFail($proyectoData['id_proyecto']);
            $proyecto->update($proyectoData);
            $proyectosActualizados[] = $proyecto;
        }

        return response()->json([
            'message' => 'Proyectos de asignatura actualizados exitosamente',
            'data' => $proyectosActualizados,
        ]);
    }

    // Eliminar un proyecto de asignatura
    public function deleteOne($id)
    {
        $proyecto = ProyectoAsignatura::findOrFail($id);
        $proyecto->delete();

        return response()->json([
            'message' => 'Proyecto de asignatura eliminado exitosamente',
        ]);
    }

    // Obtener proyectos por asignatura (método adicional útil)
    public function getByAsignatura($claveAsignatura)
    {
        $proyectos = ProyectoAsignatura::where('ClaveAsignatura', $claveAsignatura)
            ->orderBy('orden')
            ->get();

        return response()->json([
            'data' => $proyectos,
        ]);
    }
}
