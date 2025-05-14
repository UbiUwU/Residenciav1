<?php

// app/Http/Controllers/HorarioMaestroController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HorarioMaestro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HorarioMaestroController extends Controller
{
    public function index($maestro_id)
{
    return HorarioMaestro::where('maestro_id', $maestro_id)
        ->orderByRaw("
            CASE dia_semana
                WHEN 'Lunes' THEN 1
                WHEN 'Martes' THEN 2
                WHEN 'Miércoles' THEN 3
                WHEN 'Jueves' THEN 4
                WHEN 'Viernes' THEN 5
                WHEN 'Sábado' THEN 6
                WHEN 'Domingo' THEN 7
                ELSE 8
            END
        ")
        ->orderBy('hora_inicio')
        ->get();
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'maestro_id' => 'required|integer',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Validar traslapes
        $traslape = HorarioMaestro::where('maestro_id', $request->maestro_id)
            ->where('dia_semana', $request->dia_semana)
            ->where(function($query) use ($request) {
                $query->where('hora_inicio', '<', $request->hora_fin)
                      ->where('hora_fin', '>', $request->hora_inicio);
            })
            ->exists();

        if ($traslape) {
            return response()->json(['error' => 'El horario se traslapa con uno existente'], 409);
        }

        $horario = HorarioMaestro::create($request->all());
        return response()->json(['message' => 'Horario creado', 'horario' => $horario], 201);
    }

    public function update(Request $request, $id)
{
    // Validar entrada
    $validator = Validator::make($request->all(), [
        'dia_semana' => 'sometimes|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
        'hora_inicio' => 'sometimes|date_format:H:i',
        'hora_fin' => 'sometimes|date_format:H:i',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    try {
        // Llamar a la función PostgreSQL
        $result = DB::selectOne(
            "SELECT actualizar_horario_maestro(?, ?, ?, ?) AS resultado",
            [
                $id,
                $request->input('dia_semana'),
                $request->input('hora_inicio'),
                $request->input('hora_fin')
            ]
        );

        // Analizar el resultado
        $resultado = $result->resultado;

        if (str_starts_with($resultado, 'ERROR:')) {
            return response()->json(['error' => str_replace('ERROR: ', '', $resultado)], 400);
        } elseif (str_starts_with($resultado, 'AVISO:')) {
            return response()->json(['message' => str_replace('AVISO: ', '', $resultado)], 200);
        } elseif (str_starts_with($resultado, 'ÉXITO:')) {
            // Obtener el horario actualizado para devolverlo
            $horarioActualizado = HorarioMaestro::find($id);
            return response()->json([
                'message' => str_replace('ÉXITO: ', '', $resultado),
                'horario' => $horarioActualizado
            ], 200);
        }

        return response()->json(['message' => 'Horario actualizado'], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al actualizar el horario',
            'details' => $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        $horario = HorarioMaestro::find($id);
        if (!$horario) {
            return response()->json(['error' => 'Horario no encontrado'], 404);
        }

        $horario->delete();
        return response()->json(['message' => 'Horario eliminado']);
    }
}
