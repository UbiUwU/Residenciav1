<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComputadoraController extends Controller
{
    public function index()
    {
        $computadoras = DB::select('SELECT * FROM get_all_computadoras()');

        return response()->json($computadoras);
    }

    public function show($numero_inventario)
    {
        $computadora = DB::select('SELECT * FROM get_computadora_by_inventario(?)', [$numero_inventario]);
        if (empty($computadora)) {
            return response()->json(['message' => 'Computadora no encontrada'], 404);
        }

        return response()->json($computadora[0]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'NumeroInventario' => 'required|integer',
            'ClaveAula' => 'required|string|max:20',
            'Marca' => 'required|string|max:50',
            'Estado' => 'required|string|max:50',
        ]);

        $response = DB::select('SELECT insert_computadora(?, ?, ?, ?) AS message', [
            $request->NumeroInventario,
            $request->ClaveAula,
            $request->Marca,
            $request->Estado,
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function update(Request $request, $numero_inventario)
    {
        $request->validate([
            'ClaveAula' => 'required|string|max:20',
            'Marca' => 'required|string|max:50',
            'Estado' => 'required|string|max:50',
        ]);

        $response = DB::select('SELECT update_computadora(?, ?, ?, ?) AS message', [
            $numero_inventario,
            $request->ClaveAula,
            $request->Marca,
            $request->Estado,
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function destroy($numero_inventario)
    {
        $response = DB::select('SELECT delete_computadora(?) AS message', [$numero_inventario]);

        return response()->json(['message' => $response[0]->message]);
    }
}
