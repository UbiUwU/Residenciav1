<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
    // Listar todos los roles
    public function index()
    {
        $roles = Rol::all();
        return response()->json($roles);
    }

    // Mostrar un rol específico
    public function show($id)
    {
        $rol = Rol::find($id);
        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }
        return response()->json($rol);
    }

    // Crear un nuevo rol
    public function store(Request $request)
    {
        $request->validate([
            'idrol' => 'required|integer|min:1|unique:roles,idrol',
            'nombre' => 'required|string|max:50',
        ]);

        $rol = Rol::create($request->all());
        return response()->json($rol, 201);
    }

    // Actualizar un rol existente
    public function update(Request $request, $id)
    {
        $rol = Rol::find($id);
        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:50',
        ]);

        $rol->update($request->all());
        return response()->json($rol);
    }

    // Eliminar un rol
    public function destroy($id)
    {
        $rol = Rol::find($id);
        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        $rol->delete();
        return response()->json(['message' => 'Rol eliminado']);
    }
}
