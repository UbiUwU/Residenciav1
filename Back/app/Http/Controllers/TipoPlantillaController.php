<?php

namespace App\Http\Controllers;

use App\Models\TipoPlantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoPlantillaController extends Controller
{
    // Obtener todos los tipos de plantilla
    public function index()
    {
        $tipos = TipoPlantilla::all();

        return response()->json([
            'data' => $tipos,
        ]);
    }

    // Obtener un tipo de plantilla específico
    public function show($id)
    {
        $tipo = TipoPlantilla::findOrFail($id);

        return response()->json([
            'data' => $tipo,
        ]);
    }

    // Crear un tipo de plantilla
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|unique:tipos_plantilla,nombre',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tipo = TipoPlantilla::create($request->all());

        return response()->json([
            'message' => 'Tipo de plantilla creado exitosamente',
            'data' => $tipo,
        ], 201);
    }

    // Actualizar un tipo de plantilla
    public function update(Request $request, $id)
    {
        $tipo = TipoPlantilla::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:50|unique:tipos_plantilla,nombre,'.$id,
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tipo->update($request->all());

        return response()->json([
            'message' => 'Tipo de plantilla actualizado exitosamente',
            'data' => $tipo,
        ]);
    }

    // Eliminar un tipo de plantilla
    public function destroy($id)
    {
        $tipo = TipoPlantilla::findOrFail($id);

        // Verificar si hay plantillas asociadas
        if ($tipo->plantillas()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el tipo de plantilla porque tiene plantillas asociadas',
            ], 422);
        }

        $tipo->delete();

        return response()->json([
            'message' => 'Tipo de plantilla eliminado exitosamente',
        ]);
    }
}
