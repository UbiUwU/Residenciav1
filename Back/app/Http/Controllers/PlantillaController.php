<?php

namespace App\Http\Controllers;

use App\Models\Plantilla;
use App\Models\TipoPlantilla;
use App\Models\EstadoPlantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PlantillaController extends Controller
{
    // Obtener todas las plantillas con relaciones
    public function index(Request $request)
    {
        $query = Plantilla::with(['tipo', 'estado', 'periodoEscolar']);

        // Filtros
        if ($request->has('tipo_id')) {
            $query->where('tipo_id', $request->tipo_id);
        }

        if ($request->has('estado_id')) {
            $query->where('estado_id', $request->estado_id);
        }

        if ($request->has('periodo_escolar_id')) {
            $query->where('periodo_escolar_id', $request->periodo_escolar_id);
        }

        if ($request->has('activas')) {
            $query->activas();
        }

        $plantillas = $query->orderBy('creado_en', 'desc')->get();

        return response()->json([
            'data' => $plantillas
        ]);
    }

    // Obtener una plantilla específica con relaciones
    public function show($id)
    {
        $plantilla = Plantilla::with(['tipo', 'estado', 'periodoEscolar'])->findOrFail($id);
        return response()->json([
            'data' => $plantilla
        ]);
    }

    // Crear una plantilla
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:doc,docx,pdf|max:2048',
            'tipo_id' => 'required|exists:tipos_plantilla,id',
            'estado_id' => 'sometimes|exists:estados_plantilla,id',
            'periodo_escolar_id' => 'nullable|exists:periodo_escolar,id_periodo_escolar'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('archivo');

        // Subir archivo
        // Subir archivo
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('plantillas', $nombreArchivo, 'public');
            $data['archivo'] = $nombreArchivo;  // Store in $data array instead
        }



        if (!isset($data['estado_id'])) {
            $data['estado_id'] = 1; // Activo por defecto
        }

        $plantilla = Plantilla::create($data);

        return response()->json([
            'message' => 'Plantilla creada exitosamente',
            'data' => $plantilla->load(['tipo', 'estado', 'periodoEscolar'])
        ], 201);
    }

    // Actualizar una plantilla
    public function update(Request $request, $id)
    {
        $plantilla = Plantilla::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:doc,docx,pdf|max:2048',
            'tipo_id' => 'sometimes|required|exists:tipos_plantilla,id',
            'estado_id' => 'sometimes|exists:estados_plantilla,id',
            'periodo_escolar_id' => 'nullable|exists:periodo_escolar,id_periodo_escolar'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('archivo');

        if ($request->hasFile('archivo')) {
            // borrar archivo anterior si existe
            if ($plantilla->archivo && Storage::disk('public')->exists('plantillas/' . $plantilla->archivo)) {
                Storage::disk('public')->delete('plantillas/' . $plantilla->archivo);
            }

            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->storeAs('plantillas', $nombreArchivo, 'public');
            $plantilla->archivo = $nombreArchivo;
        }

        $plantilla->update($data);

        return response()->json([
            'message' => 'Plantilla actualizada exitosamente',
            'data' => $plantilla->load(['tipo', 'estado', 'periodoEscolar'])
        ]);
    }


    // Eliminar una plantilla
    public function destroy($id)
    {
        $plantilla = Plantilla::findOrFail($id);

        // Aquí podrías agregar lógica para eliminar el archivo físico si es necesario
        // Storage::delete('plantillas/' . $plantilla->archivo);

        $plantilla->delete();

        return response()->json([
            'message' => 'Plantilla eliminada exitosamente'
        ]);
    }

    // Cambiar estado de una plantilla
    public function cambiarEstado(Request $request, $id)
    {
        $plantilla = Plantilla::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'estado_id' => 'required|exists:estados_plantilla,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $plantilla->update(['estado_id' => $request->estado_id]);

        return response()->json([
            'message' => 'Estado de plantilla actualizado exitosamente',
            'data' => $plantilla->load(['tipo', 'estado', 'periodoEscolar'])
        ]);
    }

    // Obtener plantillas por tipo
    public function porTipo($tipoId)
    {
        $plantillas = Plantilla::with(['tipo', 'estado', 'periodoEscolar'])
            ->where('tipo_id', $tipoId)
            ->activas()
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'data' => $plantillas
        ]);
    }

    // Obtener plantillas por periodo escolar
    public function porPeriodo($periodoId)
    {
        $plantillas = Plantilla::with(['tipo', 'estado', 'periodoEscolar'])
            ->where('periodo_escolar_id', $periodoId)
            ->activas()
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'data' => $plantillas
        ]);
    }

    // Buscar plantillas por nombre
    public function buscar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'termino' => 'required|string|min:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $plantillas = Plantilla::with(['tipo', 'estado', 'periodoEscolar'])
            ->where('nombre', 'ILIKE', '%' . $request->termino . '%')
            ->orWhere('descripcion', 'ILIKE', '%' . $request->termino . '%')
            ->orderBy('creado_en', 'desc')
            ->get();

        return response()->json([
            'data' => $plantillas
        ]);
    }
}