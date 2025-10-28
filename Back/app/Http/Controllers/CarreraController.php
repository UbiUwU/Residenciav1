<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CarreraController extends Controller
{
    // Listar todas las carreras
    public function index()
    {
        $carreras = Carrera::all();
        return response()->json($carreras, 200);
    }

    // Mostrar una carrera específica
    public function show($clavecarrera)
    {
        $carrera = Carrera::find($clavecarrera);
        if (!$carrera) {
            return response()->json(['message' => 'Carrera no encontrada'], 404);
        }
        return response()->json($carrera, 200);
    }

    // Crear una nueva carrera
    public function store(Request $request)
    {
        $request->validate([
            'clavecarrera' => 'required|string|max:20|unique:carreras,clavecarrera',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
            'generacion' => 'required|integer|min:1',
        ]);

        try {
            $carrera = Carrera::create($request->only(['clavecarrera', 'nombre', 'descripcion', 'generacion']));
            return response()->json([
                'message' => 'Carrera creada exitosamente',
                'carrera' => $carrera
            ], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Actualizar una carrera existente
    public function update(Request $request, $clavecarrera)
    {
        $carrera = Carrera::find($clavecarrera);
        if (!$carrera) {
            return response()->json(['message' => 'Carrera no encontrada'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|required|string|max:255',
            'generacion' => 'sometimes|required|integer|min:1',
        ]);

        try {
            $carrera->update($request->only(['nombre', 'descripcion', 'generacion']));
            return response()->json([
                'message' => 'Carrera actualizada exitosamente',
                'carrera' => $carrera
            ], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Eliminar una carrera
    public function destroy($clavecarrera)
    {
        $carrera = Carrera::find($clavecarrera);
        if (!$carrera) {
            return response()->json(['message' => 'Carrera no encontrada'], 404);
        }

        try {
            $carrera->delete();
            return response()->json(['message' => 'Carrera eliminada exitosamente'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
