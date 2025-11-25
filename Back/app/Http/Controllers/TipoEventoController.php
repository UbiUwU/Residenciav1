<?php

namespace App\Http\Controllers;

use App\Models\TipoEvento;
use Illuminate\Http\Request;

class TipoEventoController extends Controller
{
    // Listar todos los tipos de evento
    public function index()
    {
        $tipos = TipoEvento::all();

        return response()->json($tipos, 200);
    }

    // Crear un nuevo tipo de evento
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $tipoEvento = TipoEvento::create($request->all());

        return response()->json([
            'message' => 'Tipo de evento creado exitosamente',
            'data' => $tipoEvento,
        ], 201);
    }

    // Mostrar un tipo de evento por id
    public function show($id)
    {
        $tipoEvento = TipoEvento::find($id);

        if (! $tipoEvento) {
            return response()->json(['message' => 'Tipo de evento no encontrado'], 404);
        }

        return response()->json($tipoEvento, 200);
    }

    // Actualizar un tipo de evento
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $tipoEvento = TipoEvento::find($id);

        if (! $tipoEvento) {
            return response()->json(['message' => 'Tipo de evento no encontrado'], 404);
        }

        $tipoEvento->update($request->all());

        return response()->json([
            'message' => 'Tipo de evento actualizado exitosamente',
            'data' => $tipoEvento,
        ], 200);
    }

    // Eliminar un tipo de evento
    public function destroy($id)
    {
        $tipoEvento = TipoEvento::find($id);

        if (! $tipoEvento) {
            return response()->json(['message' => 'Tipo de evento no encontrado'], 404);
        }

        $tipoEvento->delete();

        return response()->json(['message' => 'Tipo de evento eliminado exitosamente'], 200);
    }
}
