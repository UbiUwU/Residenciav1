<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    // Listar todos los departamentos
    public function index()
    {
        $departamentos = Departamento::all();

        return response()->json($departamentos);
    }

    // Mostrar un departamento específico
    public function show($id)
    {
        $departamento = Departamento::find($id);

        if (! $departamento) {
            return response()->json(['message' => 'Departamento no encontrado'], 404);
        }

        return response()->json($departamento);
    }

    // Crear un nuevo departamento
    public function store(Request $request)
    {
        $request->validate([
            'id_departamento' => 'required|integer|unique:departamentos,id_departamento',
            'nombre' => 'required|string|max:100',
            'abreviacion' => 'required|string|max:10',
        ]);

        try {
            $departamento = Departamento::create($request->only(['id_departamento', 'nombre', 'abreviacion']));

            return response()->json([
                'message' => 'Departamento creado exitosamente',
                'departamento' => $departamento,
            ], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Actualizar un departamento existente
    public function update(Request $request, $id)
    {
        $departamento = Departamento::find($id);

        if (! $departamento) {
            return response()->json(['message' => 'Departamento no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'abreviacion' => 'sometimes|required|string|max:10',
        ]);

        try {
            $departamento->update($request->only(['nombre', 'abreviacion']));

            return response()->json([
                'message' => 'Departamento actualizado exitosamente',
                'departamento' => $departamento,
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Eliminar un departamento
    public function destroy($id)
    {
        $departamento = Departamento::find($id);

        if (! $departamento) {
            return response()->json(['message' => 'Departamento no encontrado'], 404);
        }

        try {
            $departamento->delete();

            return response()->json(['message' => 'Departamento eliminado exitosamente']);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
