<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventoCalendarioController extends Controller
{
    // Obtener todos los eventos
    public function index()
    {
        try {
            $eventos = DB::select('SELECT * FROM obtener_todos_eventos()');
            return response()->json($eventos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Obtener un evento específico
    public function show($id)
    {
        try {
            $evento = DB::select('SELECT * FROM obtener_evento_por_id(?)', [$id]);
            
            if (empty($evento)) {
                return response()->json(['error' => 'Evento no encontrado'], 404);
            }
            
            return response()->json($evento[0]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Crear un nuevo evento
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_evento_id' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'id_periodo_escolar' => 'required|integer',
            'publico_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $id = DB::selectOne('SELECT insertar_evento(?, ?, ?, ?, ?, ?, ?) AS id', [
                $request->nombre,
                $request->descripcion,
                $request->tipo_evento_id,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->id_periodo_escolar,
                $request->publico_id
            ])->id;

            return response()->json(['id' => $id], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Actualizar un evento
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_evento_id' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'id_periodo_escolar' => 'required|integer',
            'publico_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $actualizado = DB::selectOne('SELECT actualizar_evento(?, ?, ?, ?, ?, ?, ?, ?) AS success', [
                $id,
                $request->nombre,
                $request->descripcion,
                $request->tipo_evento_id,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->id_periodo_escolar,
                $request->publico_id
            ])->success;

            if (!$actualizado) {
                return response()->json(['error' => 'Evento no encontrado'], 404);
            }

            return response()->json(['message' => 'Evento actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Eliminar un evento
    public function destroy($id)
    {
        try {
            $eliminado = DB::selectOne('SELECT eliminar_evento(?) AS success', [$id])->success;

            if (!$eliminado) {
                return response()->json(['error' => 'Evento no encontrado'], 404);
            }

            return response()->json(['message' => 'Evento eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}