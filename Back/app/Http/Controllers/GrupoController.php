<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = DB::select("SELECT * FROM get_all_grupos()");
        return response()->json($grupos);
    }

    public function show($clave_grupo)
    {
        $grupo = DB::select("SELECT * FROM get_grupo_by_clave(?)", [$clave_grupo]);
        if (empty($grupo)) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }
        return response()->json($grupo[0]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ClaveGrupo' => 'required|string|max:20',
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $response = DB::select("SELECT insert_grupo(?, ?, ?) AS message", [
            $request->ClaveGrupo,
            $request->Nombre,
            $request->Descripcion ?? '',
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function update(Request $request, $clave_grupo)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $response = DB::select("SELECT update_grupo(?, ?, ?) AS message", [
            $clave_grupo,
            $request->Nombre,
            $request->Descripcion ?? '',
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function destroy($clave_grupo)
    {
        $response = DB::select("SELECT delete_grupo(?) AS message", [$clave_grupo]);
        return response()->json(['message' => $response[0]->message]);
    }
}
