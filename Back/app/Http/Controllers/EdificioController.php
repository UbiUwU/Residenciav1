<?php

namespace App\Http\Controllers;

use App\Models\Edificio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EdificioController extends Controller
{
    // Listar todos los edificios
    public function index()
    {
        $edificios = Edificio::all();

        return response()->json($edificios);
    }

    // Mostrar un edificio específico
    public function show($clave)
    {
        $edificio = Edificio::find($clave);

        if (! $edificio) {
            return response()->json(['message' => 'Edificio no encontrado'], 404);
        }

        return response()->json($edificio);
    }

    // Crear un nuevo edificio
    public function store(Request $request)
    {
        $request->validate([
            'claveedificio' => 'required|string|max:20|unique:edificios,claveedificio',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $edificio = Edificio::create($request->only(['claveedificio', 'nombre', 'descripcion']));

            return response()->json([
                'message' => 'Edificio creado exitosamente',
                'edificio' => $edificio,
            ], 201);

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Actualizar un edificio existente
    public function update(Request $request, $clave)
    {
        $edificio = Edificio::find($clave);

        if (! $edificio) {
            return response()->json(['message' => 'Edificio no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|required|string|max:255',
        ]);

        try {
            $edificio->update($request->only(['nombre', 'descripcion']));

            return response()->json([
                'message' => 'Edificio actualizado exitosamente',
                'edificio' => $edificio,
            ]);

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Eliminar un edificio
    public function destroy($clave)
    {
        $edificio = Edificio::find($clave);

        if (! $edificio) {
            return response()->json(['message' => 'Edificio no encontrado'], 404);
        }

        try {
            $edificio->delete();

            return response()->json(['message' => 'Edificio eliminado exitosamente']);

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
