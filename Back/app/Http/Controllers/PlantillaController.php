<?php
namespace App\Http\Controllers;

use App\Models\Plantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantillaController extends Controller
{
    public function index()
    {
        return Plantilla::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:avance,instrumentacion,reporte_final',
            'archivo' => 'required|file|mimes:docx|max:5120',
        ]);

        $file = $request->file('archivo');
        $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
        $file->storeAs('plantillas', $filename, 'public');

        $plantilla = Plantilla::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'archivo' => $filename,
            'tipo' => $request->tipo,
            'estado' => 'activo',
        ]);

        return response()->json([
            'mensaje' => 'Plantilla subida exitosamente',
            'plantilla' => $plantilla
        ], 201);
    }

    public function show($id)
    {
        return Plantilla::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'sometimes|required|in:avance,instrumentacion,reporte_final',
            'estado' => 'sometimes|required|in:activo,en_uso,inactivo',
        ]);

        $plantilla = Plantilla::findOrFail($id);
        $plantilla->fill($request->only(['nombre', 'descripcion', 'tipo', 'estado']));
        $plantilla->save();

        return response()->json([
            'mensaje' => 'Plantilla actualizada correctamente.',
            'plantilla' => $plantilla
        ]);
    }

    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:activo,en_uso,inactivo',
        ]);

        $plantilla = Plantilla::findOrFail($id);
        $plantilla->estado = $request->estado;
        $plantilla->save();

        return response()->json(['mensaje' => 'Estado actualizado.']);
    }

    public function updateArchivo(Request $request, $id)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:docx|max:5120',
        ]);

        $plantilla = Plantilla::findOrFail($id);

        $file = $request->file('archivo');
        $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
        $file->storeAs('plantillas', $filename, 'public');

        // Opcional: borrar archivo anterior
        if ($plantilla->archivo && Storage::disk('public')->exists('plantillas/' . $plantilla->archivo)) {
            Storage::disk('public')->delete('plantillas/' . $plantilla->archivo);
        }

        $plantilla->archivo = $filename;
        $plantilla->save();

        return response()->json([
            'mensaje' => 'Archivo actualizado correctamente.',
            'archivo' => $filename
        ]);
    }

    public function descargarArchivo($id)
    {
        $plantilla = Plantilla::findOrFail($id);
        $ruta = storage_path('app/public/plantillas/' . $plantilla->archivo);

        if (!file_exists($ruta)) {
            return response()->json(['mensaje' => 'Archivo no encontrado.'], 404);
        }

        return response()->download($ruta, $plantilla->archivo);
    }
}