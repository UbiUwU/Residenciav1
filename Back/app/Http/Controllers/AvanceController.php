<?php

namespace App\Http\Controllers;

use App\Models\Avance;
use Illuminate\Http\Request;

class AvanceController extends Controller
{
    // Obtener todos los avances
    public function index()
    {
        $avances = Avance::with(['asignatura', 'profesor', 'periodoEscolar', 'horario', 'detalles', 'fechas' ,'detallesConFechas'])->get();
        return response()->json($avances);
    }

    // Obtener un avance por ID
    public function show($id)
    {
        $avance = Avance::with(['asignatura', 'profesor', 'periodoEscolar', 'horario', 'detalles'])
            ->find($id);

        if (!$avance) {
            return response()->json(['message' => 'Avance no encontrado'], 404);
        }

        return response()->json($avance);
    }

    // Crear un nuevo avance
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave_asignatura' => 'required|string|exists:asignatura,ClaveAsignatura',
            'tarjeta_profesor' => 'required|integer|exists:maestros,tarjeta',
            'id_periodo_escolar' => 'required|integer|exists:periodo_escolar,id_periodo_escolar',
            'clave_horario' => 'nullable|integer|exists:horarioasignatura_maestro,clavehorario',
            'firma_profesor' => 'nullable|string|max:100',
            'firma_jefe_carrera' => 'nullable|string|max:100',
            'requiere_firma_jefe' => 'boolean',
            'estado' => 'in:borrador,enviado,firmado,rechazado'
        ]);

        $avance = Avance::create($validated);

        return response()->json([
            'message' => 'Avance creado exitosamente',
            'avance' => $avance
        ], 201);
    }

    // Actualizar un avance
    public function update(Request $request, $id)
    {
        $avance = Avance::find($id);

        if (!$avance) {
            return response()->json(['message' => 'Avance no encontrado'], 404);
        }

        $validated = $request->validate([
            'clave_asignatura' => 'sometimes|string|exists:asignatura,ClaveAsignatura',
            'tarjeta_profesor' => 'sometimes|integer|exists:maestros,tarjeta',
            'id_periodo_escolar' => 'sometimes|integer|exists:periodo_escolar,id_periodo_escolar',
            'clave_horario' => 'nullable|integer|exists:horarioasignatura_maestro,clavehorario',
            'firma_profesor' => 'nullable|string|max:100',
            'firma_jefe_carrera' => 'nullable|string|max:100',
            'requiere_firma_jefe' => 'boolean',
            'estado' => 'in:borrador,enviado,firmado,rechazado'
        ]);

        $avance->update($validated);

        return response()->json([
            'message' => 'Avance actualizado exitosamente',
            'avance' => $avance
        ]);
    }

    // Eliminar un avance
    public function destroy($id)
    {
        $avance = Avance::find($id);

        if (!$avance) {
            return response()->json(['message' => 'Avance no encontrado'], 404);
        }

        $avance->delete();

        return response()->json(['message' => 'Avance eliminado exitosamente']);
    }
}
