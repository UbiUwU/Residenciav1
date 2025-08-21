<?php

namespace App\Http\Controllers;

use App\Models\HorarioAsignaturaMaestro;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class HorarioAsignaturaMaestroController extends Controller
{
    // Listar todos los horarios
    public function index()
    {
        $horarios = HorarioAsignaturaMaestro::with([
            'maestro' => function ($query) {
                $query->soloNombre();
            },
            'aula',
            'grupo',
            'asignatura',
            'periodoEscolar'
        ])->get();

        return response()->json($horarios, 200);
    }

    // Mostrar un horario específico
    public function show($clavehorario)
    {
        $horario = HorarioAsignaturaMaestro::with([
            'maestro',
            'aula',
            'grupo',
            'asignatura',
            'periodoEscolar'
        ])->find($clavehorario);

        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        return response()->json($horario, 200);
    }

    // Crear un nuevo horario
    public function store(Request $request)
    {
        $request->validate([
            'tarjeta' => 'required|integer|exists:maestros,tarjeta',
            'claveaula' => 'required|string|exists:aulas,claveaula',
            'clavegrupo' => 'required|string|exists:grupos,clavegrupo',
            'claveasignatura' => 'required|string|exists:asignatura,ClaveAsignatura',
            'idperiodoescolar' => 'required|integer|exists:periodo_escolar,id_periodo_escolar',
            'lunes_hi' => 'nullable|date_format:H:i:s',
            'lunes_hf' => 'nullable|date_format:H:i:s',
            'martes_hi' => 'nullable|string|max:100',
            'martes_hf' => 'nullable|string|max:100',
            'miercoles_hi' => 'nullable|string|max:100',
            'miercoles_hf' => 'nullable|string|max:100',
            'jueves_hi' => 'nullable|string|max:100',
            'jueves_hf' => 'nullable|string|max:100',
            'viernes_hi' => 'nullable|string|max:100',
            'viernes_hf' => 'nullable|string|max:100',
            'sabado_hi' => 'nullable|string|max:100',
            'sabado_hf' => 'nullable|string|max:100'
        ]);

        try {
            $horario = DB::transaction(function () use ($request) {
                return HorarioAsignaturaMaestro::create($request->all());
            });

            $horario->load(['maestro.usuario.rol', 'aula', 'grupo', 'asignatura', 'periodoEscolar']);

            return response()->json([
                'message' => 'Horario creado exitosamente',
                'horario' => $horario
            ], 201);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Error al crear el horario',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // Actualizar un horario existente
    public function update(Request $request, $clavehorario)
    {
        $horario = HorarioAsignaturaMaestro::find($clavehorario);

        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        $request->validate([
            'tarjeta' => 'sometimes|required|integer|exists:maestros,tarjeta',
            'claveaula' => 'sometimes|required|string|exists:aulas,claveaula',
            'clavegrupo' => 'sometimes|required|string|exists:grupos,clavegrupo',
            'claveasignatura' => 'sometimes|required|string|exists:asignatura,ClaveAsignatura',
            'idperiodoescolar' => 'sometimes|required|integer|exists:periodo_escolar,id_periodo_escolar',
            'lunes_hi' => 'nullable|date_format:H:i:s',
            'lunes_hf' => 'nullable|date_format:H:i:s',
            'martes_hi' => 'nullable|string|max:100',
            'martes_hf' => 'nullable|string|max:100',
            'miercoles_hi' => 'nullable|string|max:100',
            'miercoles_hf' => 'nullable|string|max:100',
            'jueves_hi' => 'nullable|string|max:100',
            'jueves_hf' => 'nullable|string|max:100',
            'viernes_hi' => 'nullable|string|max:100',
            'viernes_hf' => 'nullable|string|max:100',
            'sabado_hi' => 'nullable|string|max:100',
            'sabado_hf' => 'nullable|string|max:100'
        ]);

        try {
            $horario->update($request->all());

            $horario->load(['maestro.usuario.rol', 'aula', 'grupo', 'asignatura', 'periodoEscolar']);

            return response()->json([
                'message' => 'Horario actualizado exitosamente',
                'horario' => $horario
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Error al actualizar el horario',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // Eliminar un horario
    public function destroy($clavehorario)
    {
        $horario = HorarioAsignaturaMaestro::find($clavehorario);

        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        $horario->delete();

        return response()->json(['message' => 'Horario eliminado exitosamente'], 200);
    }
}
