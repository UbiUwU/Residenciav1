<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
    public function index()
    {
        return DB::select("SELECT * FROM listar_departamentos()");
    }

    public function show($id)
    {
        $resultado = DB::select("SELECT * FROM obtener_departamento(?)", [$id]);
        return response()->json($resultado[0] ?? [], $resultado ? 200 : 404);
    }

    public function store(Request $request)
    {
        DB::select("SELECT insertar_departamento(?, ?, ?, ?)", [
            $request->id_departamento,
            $request->nombre,
            $request->abreviacion,
            $request->maestro_id,
        ]);

        return response()->json(['message' => 'Departamento creado'], 201);
    }

    public function update(Request $request, $id)
    {
        $result = DB::select("SELECT actualizar_departamento(?, ?, ?, ?)", [
            $id,
            $request->nombre,
            $request->abreviacion,
            $request->maestro_id,
        ]);

        return response()->json(['success' => $result[0]->actualizar_departamento]);
    }

    public function destroy($id)
    {
        $result = DB::select("SELECT eliminar_departamento(?)", [$id]);
        return response()->json(['success' => $result[0]->eliminar_departamento]);
    }
}
