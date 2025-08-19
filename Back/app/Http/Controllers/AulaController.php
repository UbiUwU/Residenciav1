<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AulaController extends Controller
{
    // Listar todas las aulas
    public function index()
    {
        $aulas = Aula::with('edificio')->get();
        return response()->json($aulas);
    }

    // Mostrar un aula específica
    public function show($clave)
    {
        $aula = Aula::with('edificio')->find($clave);

        if (!$aula) {
            return response()->json(['message' => 'Aula no encontrada'], 404);
        }

        return response()->json($aula);
    }

    // Crear un nuevo aula
    public function store(Request $request)
    {
        $request->validate([
            'claveaula' => 'required|string|max:20|unique:aulas,claveaula',
            'claveedificio' => 'required|string|exists:edificios,claveedificio',
            'nombre' => 'required|string|max:100',
            'cantidadcomputadoras' => 'required|integer|min:0',
            'horadisponible' => 'required|date_format:H:i:s',
        ]);

        try {
            $aula = Aula::create($request->only([
                'claveaula',
                'claveedificio',
                'nombre',
                'cantidadcomputadoras',
                'horadisponible'
            ]));

            return response()->json([
                'message' => 'Aula creada exitosamente',
                'aula' => $aula
            ], 201);

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Actualizar un aula existente
    public function update(Request $request, $clave)
    {
        $aula = Aula::find($clave);

        if (!$aula) {
            return response()->json(['message' => 'Aula no encontrada'], 404);
        }

        $request->validate([
            'claveedificio' => 'sometimes|required|string|exists:edificios,claveedificio',
            'nombre' => 'sometimes|required|string|max:100',
            'cantidadcomputadoras' => 'sometimes|required|integer|min:0',
            'horadisponible' => 'sometimes|required|date_format:H:i:s',
        ]);

        try {
            $aula->update($request->only([
                'claveedificio',
                'nombre',
                'cantidadcomputadoras',
                'horadisponible'
            ]));

            return response()->json([
                'message' => 'Aula actualizada exitosamente',
                'aula' => $aula
            ]);

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Eliminar un aula
    public function destroy($clave)
    {
        $aula = Aula::find($clave);

        if (!$aula) {
            return response()->json(['message' => 'Aula no encontrada'], 404);
        }

        try {
            $aula->delete();
            return response()->json(['message' => 'Aula eliminada exitosamente']);

        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
