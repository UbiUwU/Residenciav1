<?php

namespace App\Http\Controllers;

use App\Models\CatalogoTiposFecha;
use Illuminate\Http\Request;

class CatalogoTiposFechaController extends Controller
{
    /**
     * Listar todos los tipos de fecha
     */
    public function index()
    {
        return response()->json(CatalogoTiposFecha::all(), 200);
    }

    /**
     * Mostrar un tipo específico
     */
    public function show($id)
    {
        $tipo = CatalogoTiposFecha::findOrFail($id);
        return response()->json($tipo, 200);
    }

    /**
     * Crear nuevo tipo
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'clave'       => 'required|string|max:50|unique:catalogo_tipos_fecha,clave',
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'es_activo'   => 'boolean',
        ]);

        $tipo = CatalogoTiposFecha::create($data);

        return response()->json($tipo, 201);
    }

    /**
     * Actualizar tipo existente
     */
    public function update(Request $request, $id)
    {
        $tipo = CatalogoTiposFecha::findOrFail($id);

        $data = $request->validate([
            'nombre'      => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'es_activo'   => 'boolean',
        ]);

        $tipo->update($data);

        return response()->json($tipo, 200);
    }

    /**
     * Eliminar tipo de fecha
     */
    public function destroy($id)
    {
        $tipo = CatalogoTiposFecha::findOrFail($id);
        $tipo->delete();

        return response()->json(['message' => 'Tipo de fecha eliminado'], 200);
    }
}
