<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB

class PlantillaController extends Controller
{
    // Método para crear una plantilla
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:docx|max:10240',
        ]);

        // Subir el archivo
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('plantillas', 'public');
        }

        // Insertar en la base de datos usando DB facade
        $plantilla = DB::table('plantillas')->insertGetId([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'archivo' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Plantilla creada correctamente', 'id' => $plantilla], 201);
    }

    // Método para obtener todas las plantillas
    public function index()
    {
        $plantillas = DB::table('plantillas')->get();
        return response()->json($plantillas);
    }

    // Método para editar una plantilla
    public function update(Request $request, $id)
    {
        // Validación de datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:docx|max:10240',
        ]);

        $updateData = [
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'updated_at' => now(),
        ];

        // Si se subió un nuevo archivo
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('plantillas', 'public');
            $updateData['archivo'] = $path;
        }

        // Actualizar la plantilla en la base de datos
        DB::table('plantillas')->where('id', $id)->update($updateData);

        return response()->json(['message' => 'Plantilla actualizada correctamente']);
    }

    // Método para eliminar una plantilla
    public function destroy($id)
    {
        // Obtener la plantilla para eliminar el archivo
        $plantilla = DB::table('plantillas')->where('id', $id)->first();

        if ($plantilla) {
            // Eliminar el archivo de la carpeta pública
            if (file_exists(public_path('storage/' . $plantilla->archivo))) {
                unlink(public_path('storage/' . $plantilla->archivo));
            }

            // Eliminar el registro de la base de datos
            DB::table('plantillas')->where('id', $id)->delete();

            return response()->json(['message' => 'Plantilla eliminada correctamente']);
        }

        return response()->json(['message' => 'Plantilla no encontrada'], 404);
    }
}
