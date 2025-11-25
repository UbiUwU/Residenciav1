<?php

namespace App\Http\Controllers;

use App\Models\EstadoPlantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstadoPlantillaController extends Controller
{
    // Obtener todos los estados de plantilla
    public function index()
    {
        $estados = EstadoPlantilla::all();

        return response()->json([
            'data' => $estados,
        ]);
    }

    // Obtener un estado de plantilla específico
    public function show($id)
    {
        $estado = EstadoPlantilla::findOrFail($id);

        return response()->json([
            'data' => $estado,
        ]);
    }

    // Crear un estado de plantilla
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:20|unique:estados_plantilla,nombre',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $estado = EstadoPlantilla::create($request->all());

        return response()->json([
            'message' => 'Estado de plantilla creado exitosamente',
            'data' => $estado,
        ], 201);
    }

    // Actualizar un estado de plantilla
    public function update(Request $request, $id)
    {
        $estado = EstadoPlantilla::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:20|unique:estados_plantilla,nombre,'.$id,
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $estado->update($request->all());

        return response()->json([
            'message' => 'Estado de plantilla actualizado exitosamente',
            'data' => $estado,
        ]);
    }

    // Eliminar un estado de plantilla
    public function destroy($id)
    {
        $estado = EstadoPlantilla::findOrFail($id);

        // Verificar si hay plantillas asociadas
        if ($estado->plantillas()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el estado de plantilla porque tiene plantillas asociadas',
            ], 422);
        }

        $estado->delete();

        return response()->json([
            'message' => 'Estado de plantilla eliminado exitosamente',
        ]);
    }
}
