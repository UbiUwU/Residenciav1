<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = DB::select("SELECT * FROM get_all_carreras()");
        return response()->json($carreras);
    }

    public function show($clave)
    {
        $carrera = DB::select("SELECT * FROM get_carrera_by_clave(?)", [$clave]);
        if (empty($carrera)) {
            return response()->json(['message' => 'Carrera no encontrada'], 404);
        }
        return response()->json($carrera[0]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ClaveCarrera' => 'required|string|max:20',
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $response = DB::select("SELECT insert_carrera(?, ?, ?) AS message", [
            $request->ClaveCarrera,
            $request->Nombre,
            $request->Descripcion ?? '',
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function update(Request $request, $clave)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $response = DB::select("SELECT update_carrera(?, ?, ?) AS message", [
            $clave,
            $request->Nombre,
            $request->Descripcion ?? '',
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function destroy($clave)
    {
        $response = DB::select("SELECT delete_carrera(?) AS message", [$clave]);
        return response()->json(['message' => $response[0]->message]);
    }
}
