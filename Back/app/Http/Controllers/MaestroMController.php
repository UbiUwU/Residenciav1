<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaestroMController extends Controller
{
    // 1. Obtener horario del maestro
    public function getHorario($tarjeta)
    {
        $horario = DB::select("SELECT * FROM public.get_horario_maestro(?)", [$tarjeta]);
        return response()->json($horario);
    }

    // 2. Verificar si ya tiene aula reservada hoy
    public function tieneReservacion(Request $request)
    {
        $request->validate([
            'tarjeta' => 'required|integer',
            'claveaula' => 'required|string',
            'fecha' => 'required|date'
        ]);

        $existe = DB::select(
            "SELECT 1 FROM reservacionmaestros WHERE tarjeta = ? AND claveaula = ? AND fechareservacion = ? LIMIT 1",
            [$request->tarjeta, $request->claveaula, $request->fecha]
        );

        return response()->json(['reservado' => !empty($existe)]);
    }

    // 3. Obtener su reservación actual
    public function getReservacionActual(Request $request)
    {
        $request->validate([
            'tarjeta' => 'required|integer',
            'fecha' => 'required|date'
        ]);

        $data = DB::select("
            SELECT rm.claveaula, a.claveedificio, a.nombre, 
                   rm.fechareservacion, rm.horainicio, TO_CHAR(rm.horafin, 'HH24:MI') as hora_fin
            FROM reservacionmaestros rm
            JOIN aulas a ON rm.claveaula = a.claveaula
            WHERE rm.tarjeta = ? AND rm.fechareservacion >= ?
            LIMIT 1", [$request->tarjeta, $request->fecha]);

        return response()->json($data);
    }

    // 4. Verificar disponibilidad del aula
    public function aulaDisponible(Request $request)
    {
        $request->validate([
            'aula' => 'required|string',
            'fecha' => 'required|date',
            'horaInicio' => 'required',
            'horaFin' => 'required'
        ]);

        $conflictos = DB::select("
            SELECT * FROM reservacionmaestros 
            WHERE claveaula = ? AND fechareservacion = ?
              AND (horainicio <= ? AND horafin >= ?)",
            [$request->aula, $request->fecha, $request->horaFin, $request->horaInicio]);

        return response()->json(['disponible' => empty($conflictos)]);
    }

    // 5. Insertar nueva reservación
    public function reservarAula(Request $request)
    {
        $request->validate([
            'tarjeta' => 'required|integer',
            'aula' => 'required|string',
            'fecha' => 'required|date',
            'horaInicio' => 'required',
            'horaFin' => 'required'
        ]);

        DB::insert("
            INSERT INTO reservacionmaestros (tarjeta, claveaula, fechareservacion, horainicio, horafin)
            VALUES (?, ?, ?, ?, ?)",
            [$request->tarjeta, $request->aula, $request->fecha, $request->horaInicio, $request->horaFin]);

        return response()->json(['success' => true, 'message' => 'Aula reservada correctamente']);
    }

    // 6. Eliminar reservación
    public function eliminarReservacion(Request $request)
    {
        $request->validate([
            'tarjeta' => 'required|integer',
            'aula' => 'required|string'
        ]);

        DB::delete("
            DELETE FROM reservacionmaestros 
            WHERE tarjeta = ? AND claveaula = ?",
            [$request->tarjeta, $request->aula]);

        return response()->json(['success' => true, 'message' => 'Reservación eliminada']);
    }

    // 7. Verificar si el aula existe
    public function verificarAula($clave_aula)
    {
        $aula = DB::select("SELECT nombre FROM aulas WHERE claveaula = ? LIMIT 1", [$clave_aula]);
        return response()->json(['existe' => !empty($aula), 'nombre' => $aula[0]->nombre ?? null]);
    }

    // 8. Registrar en bitácora
    public function registrarBitacora(Request $request)
    {
        $request->validate([
            'tarjeta' => 'required|integer',
            'claveaula' => 'required|string',
            'fecha' => 'required|date_format:Y-m-d H:i:s'
        ]);

        DB::insert("
            INSERT INTO bitacora_maestros (tarjeta, claveaula, fechahora)
            VALUES (?, ?, ?)",
            [$request->tarjeta, $request->claveaula, $request->fecha]);

        return response()->json(['success' => true, 'message' => 'Registro en bitácora exitoso']);
    }
}
