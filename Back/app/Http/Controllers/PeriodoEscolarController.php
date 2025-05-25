<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodoEscolarController extends Controller
{
    // Crear nuevo período
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigoperiodo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $result = DB::select(
                "SELECT insert_periodo_escolar(?, ?, ?) as result",
                [
                    $request->codigoperiodo,
                    $request->fecha_inicio,
                    $request->fecha_fin
                ]
            );

            return response()->json(['message' => $result[0]->result], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Obtener todos los períodos
    public function index()
    {
        try {
            $periodos = DB::select("SELECT * FROM get_all_periodos_escolares()");
            return response()->json($periodos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Obtener un período específico
    public function show($id)
    {
        try {
            $periodo = DB::select("SELECT * FROM get_periodo_escolar_by_id(?)", [$id]);
            
            if (empty($periodo)) {
                return response()->json(['message' => 'Período no encontrado'], 404);
            }

            return response()->json($periodo[0]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Actualizar un período
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'codigoperiodo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $result = DB::select(
                "SELECT update_periodo_escolar(?, ?, ?, ?) as result",
                [
                    $id,
                    $request->codigoperiodo,
                    $request->fecha_inicio,
                    $request->fecha_fin
                ]
            );

            return response()->json(['message' => $result[0]->result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Eliminar un período
    public function destroy($id)
    {
        try {
            $result = DB::select("SELECT delete_periodo_escolar(?) as result", [$id]);
            return response()->json(['message' => $result[0]->result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}