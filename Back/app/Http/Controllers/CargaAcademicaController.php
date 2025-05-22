<?php

namespace App\Http\Controllers;
    
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CargaAcademicaController extends Controller
{
    // --- Carga Academica General ---

    public function indexGeneral()
    {
        $result = DB::select("SELECT * FROM get_all_carga_academica_general()");
        return response()->json($result);
    }

    public function showGeneral($id)
    {
        $result = DB::select("SELECT * FROM get_carga_academica_general_by_id(?)", [$id]);
        if (empty($result)) {
            return response()->json(['message' => 'Carga académica general no encontrada'], 404);
        }
        return response()->json($result[0]);
    }

    public function storeGeneral(Request $request)
    {
        $request->validate([
            'NumeroControl' => 'required|integer',
        ]);

        $response = DB::select("SELECT insert_carga_academica_general(?) AS message", [$request->NumeroControl]);
        return response()->json(['message' => $response[0]->message]);
    }

    public function updateGeneral(Request $request, $id)
    {
        $request->validate([
            'NumeroControl' => 'required|integer',
        ]);

        $response = DB::select("SELECT update_carga_academica_general(?, ?) AS message", [$id, $request->NumeroControl]);
        return response()->json(['message' => $response[0]->message]);
    }

    public function destroyGeneral($id)
    {
        $response = DB::select("SELECT delete_carga_academica_general(?) AS message", [$id]);
        return response()->json(['message' => $response[0]->message]);
    }

    // --- Carga Academica Detalle ---

    public function indexDetalle()
    {
        $result = DB::select("SELECT * FROM get_all_carga_academica_detalles()");
        return response()->json($result);
    }

    public function showDetalle($id)
    {
        $result = DB::select("SELECT * FROM get_carga_academica_detalle_by_id(?)", [$id]);
        if (empty($result)) {
            return response()->json(['message' => 'Detalle de carga académica no encontrado'], 404);
        }
        return response()->json($result[0]);
    }

    public function storeDetalle(Request $request)
    {
        $request->validate([
            'ClaveHorario' => 'required|integer',
            'IdCargaGeneral' => 'required|integer',
        ]);

        $response = DB::select("SELECT insert_carga_academica_detalle(?, ?) AS message", [
            $request->ClaveHorario,
            $request->IdCargaGeneral,
        ]);
        return response()->json(['message' => $response[0]->message]);
    }

    public function updateDetalle(Request $request, $id)
    {
        $request->validate([
            'ClaveHorario' => 'required|integer',
            'IdCargaGeneral' => 'required|integer',
        ]);

        $response = DB::select("SELECT update_carga_academica_detalle(?, ?, ?) AS message", [
            $id,
            $request->ClaveHorario,
            $request->IdCargaGeneral,
        ]);
        return response()->json(['message' => $response[0]->message]);
    }

    public function destroyDetalle($id)
    {
        $response = DB::select("SELECT delete_carga_academica_detalle(?) AS message", [$id]);
        return response()->json(['message' => $response[0]->message]);
    }
}
