<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    // Listar todos los grupos
    public function index()
    {
        $grupos = Grupo::all();

        return response()->json($grupos, 200);
    }

    // Mostrar un grupo específico
    public function show($clavegrupo)
    {
        $grupo = Grupo::find($clavegrupo);
        if (! $grupo) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }

        return response()->json($grupo, 200);
    }

    // Crear un nuevo grupo
    public function store(Request $request)
    {
        $request->validate([
            'clavegrupo' => 'required|string|max:20|unique:grupos,clavegrupo',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $grupo = Grupo::create($request->only(['clavegrupo', 'nombre', 'descripcion']));

            return response()->json([
                'message' => 'Grupo creado exitosamente',
                'grupo' => $grupo,
            ], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Actualizar un grupo existente
    public function update(Request $request, $clavegrupo)
    {
        $grupo = Grupo::find($clavegrupo);
        if (! $grupo) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|required|string|max:255',
        ]);

        try {
            $grupo->update($request->only(['nombre', 'descripcion']));

            return response()->json([
                'message' => 'Grupo actualizado exitosamente',
                'grupo' => $grupo,
            ], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Eliminar un grupo
    public function destroy($clavegrupo)
    {
        $grupo = Grupo::find($clavegrupo);
        if (! $grupo) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }

        try {
            $grupo->delete();

            return response()->json(['message' => 'Grupo eliminado exitosamente'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
