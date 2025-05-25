<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EdificioController extends Controller
{
    public function index()
    {
        $edificios = DB::select("SELECT * FROM get_all_edificios()");
        return response()->json($edificios);
    }

    public function show($clave_edificio)
    {
        $edificio = DB::select("SELECT * FROM get_edificio_by_clave(?)", [$clave_edificio]);
        if (empty($edificio)) {
            return response()->json(['message' => 'Edificio no encontrado'], 404);
        }
        return response()->json($edificio[0]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ClaveEdificio' => 'required|string|max:20',
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $response = DB::select("SELECT insert_edificio(?, ?, ?) AS message", [
            $request->ClaveEdificio,
            $request->Nombre,
            $request->Descripcion ?? '',
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function update(Request $request, $clave_edificio)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $response = DB::select("SELECT update_edificio(?, ?, ?) AS message", [
            $clave_edificio,
            $request->Nombre,
            $request->Descripcion ?? '',
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function destroy($clave_edificio)
    {
        $response = DB::select("SELECT delete_edificio(?) AS message", [$clave_edificio]);
        return response()->json(['message' => $response[0]->message]);
    }
}
