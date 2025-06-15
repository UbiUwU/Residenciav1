<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//Es avance programatico
class InstrumentacionController extends Controller
{
    public function crearInstrumentacion(Request $request)
    {
        $data = $request->validate([
            'clave_asignatura' => 'required|string',
            'tarjeta' => 'required|integer',
            'periodo' => 'required|string',
            'firma_profesor' => 'nullable|string',
            'firma_jefe' => 'nullable|string',
        ]);

        $result = DB::select('SELECT crear_instrumentacion(?, ?, ?, ?, ?) AS result', [
            $data['clave_asignatura'],
            $data['tarjeta'],
            $data['periodo'],
            $data['firma_profesor'],
            $data['firma_jefe'],
        ]);

        return response()->json(json_decode($result[0]->result));
    }

    public function agregarDetalle(Request $request)
    {
        $data = $request->validate([
            'id_instrumentacion' => 'required|integer',
            'id_tema' => 'required|integer',
            'fecha_programada_inicio' => 'nullable|date',
            'fecha_programada_fin' => 'nullable|date',
            'evaluacion_programada_inicio' => 'nullable|date',
            'evaluacion_programada_fin' => 'nullable|date',
            'porcentaje' => 'nullable|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $result = DB::select('SELECT agregar_detalle_instrumentacion(?, ?, ?, ?, ?, ?, ?, ?) AS result', [
            $data['id_instrumentacion'],
            $data['id_tema'],
            $data['fecha_programada_inicio'],
            $data['fecha_programada_fin'],
            $data['evaluacion_programada_inicio'],
            $data['evaluacion_programada_fin'],
            $data['porcentaje'],
            $data['observaciones'],
        ]);

        return response()->json(json_decode($result[0]->result));
    }
    public function obtenerPorTarjeta($tarjeta)
    {
        $result = DB::select('SELECT obtener_instrumentacion_por_tarjeta(?) AS result', [
            $tarjeta
        ]);

        return response()->json(json_decode($result[0]->result));
    }

    public function actualizarDetalle(Request $request, $id)
    {
        $data = $request->validate([
            'fecha_programada_inicio' => 'nullable|date',
            'fecha_programada_fin' => 'nullable|date',
            'evaluacion_programada_inicio' => 'nullable|date',
            'evaluacion_programada_fin' => 'nullable|date',
            'porcentaje' => 'nullable|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $result = DB::select('SELECT actualizar_detalle_instrumentacion(?, ?, ?, ?, ?, ?, ?) AS result', [
            $id,
            $data['fecha_programada_inicio'] ?? null,
            $data['fecha_programada_fin'] ?? null,
            $data['evaluacion_programada_inicio'] ?? null,
            $data['evaluacion_programada_fin'] ?? null,
            $data['porcentaje'] ?? null,
            $data['observaciones'] ?? null,
        ]);

        return response()->json(json_decode($result[0]->result));
    }

    public function actualizarInstrumentacion(Request $request, $id)
    {
        $data = $request->validate([
            'firma_profesor' => 'nullable|string',
            'firma_jefe' => 'nullable|string',
            'estado' => 'nullable|string|in:Borrador,Aprobado,Finalizado',
        ]);

        $result = DB::select('SELECT actualizar_instrumentacion(?, ?, ?, ?) AS result', [
            $id,
            $data['firma_profesor'] ?? null,
            $data['firma_jefe'] ?? null,
            $data['estado'] ?? null,
        ]);

        return response()->json(json_decode($result[0]->result));
    }


}
