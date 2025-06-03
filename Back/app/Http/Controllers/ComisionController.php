<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComisionController extends Controller
{
    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'eventName' => 'required|string|max:100',
            'eventType.value' => 'required|string|max:50',
            'eventDate' => 'required|date',
            'status' => 'required|string|max:30',
            'selectedMaestro' => 'required|array|min:1',
            'selectedMaestro.*.value' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Extraer los valores
        $nombreEvento = $request->eventName;
        $tipoEvento = $request->eventType['value'];
        $fechaEvento = $request->eventDate;
        $estatus = $request->status;
        $maestros = collect($request->selectedMaestro)->pluck('value')->toArray();

        // Llamar a la función de PostgreSQL
        $result = DB::select('SELECT * FROM crear_comision_con_maestros(?, ?, ?, ?, ?)', [
            $nombreEvento,
            $tipoEvento,
            $fechaEvento,
            $estatus,
            '{' . implode(',', $maestros) . '}' // Formato ARRAY
        ]);

        return response()->json($result[0]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre_evento' => 'required|string|max:255',
            'tipo_evento' => 'required|string|max:100',
            'fecha_evento' => 'required|date',
            'estatus' => 'required|string|max:20',
            'tarjetas_maestros' => 'required|array|min:1',
            'tarjetas_maestros.*' => 'integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $result = DB::select('SELECT actualizar_comision(?, ?, ?, ?, ?, ?) AS result', [
            (int) $id,
            $request->nombre_evento,
            $request->tipo_evento,
            $request->fecha_evento,
            $request->estatus,
            '{' . implode(',', $request->tarjetas_maestros) . '}'
        ]);

        return response()->json(json_decode($result[0]->result));
    }

    public function destroy($id)
    {
        $result = DB::select('SELECT eliminar_comision(?) AS result', [(int) $id]);
        return response()->json(json_decode($result[0]->result));
    }
    public function index()
    {
        $result = DB::select('SELECT obtener_comisiones_con_maestros() AS result');

        if (empty($result) || $result[0]->result === null) {
            return response()->json([]);
        }

        return response()->json(json_decode($result[0]->result));
    }

    public function getByMaestro($tarjeta)
{
    $result = DB::select('SELECT get_comisiones_by_maestro(CAST(? AS bigint)) AS result', [$tarjeta]);

    if (empty($result) || $result[0]->result === null) {
        return response()->json([]);
    }

    return response()->json(json_decode($result[0]->result));
}

}
