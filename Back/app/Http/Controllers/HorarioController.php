<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = DB::select('SELECT * FROM get_all_horarios()');

        return response()->json($horarios);
    }

    public function show($clave_horario)
    {
        $horario = DB::select('SELECT * FROM get_horario_by_clave(?)', [$clave_horario]);
        if (empty($horario)) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        return response()->json($horario[0]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ClaveHorario' => 'required|integer',
            'Tarjeta' => 'required|integer',
            'ClaveAula' => 'required|string|max:20',
            'ClaveGrupo' => 'required|string|max:20',
            'ClaveAsignatura' => 'required|string|max:20',
            'Lunes_HI' => 'required|date_format:H:i:s',
            'Lunes_HF' => 'required|date_format:H:i:s',
            'IdPeriodoEscolar' => 'required|integer',
            'Martes_HI' => 'nullable|date_format:H:i:s',
            'Martes_HF' => 'nullable|date_format:H:i:s',
            'Miercoles_HI' => 'nullable|date_format:H:i:s',
            'Miercoles_HF' => 'nullable|date_format:H:i:s',
            'Jueves_HI' => 'nullable|date_format:H:i:s',
            'Jueves_HF' => 'nullable|date_format:H:i:s',
            'Viernes_HI' => 'nullable|date_format:H:i:s',
            'Viernes_HF' => 'nullable|date_format:H:i:s',
            'Sabado_HI' => 'nullable|date_format:H:i:s',
            'Sabado_HF' => 'nullable|date_format:H:i:s',
        ]);

        $response = DB::select('SELECT insert_horario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS message', [
            $request->ClaveHorario,
            $request->Tarjeta,
            $request->ClaveAula,
            $request->ClaveGrupo,
            $request->ClaveAsignatura,
            $request->Lunes_HI,
            $request->Lunes_HF,
            $request->IdPeriodoEscolar,
            $request->Martes_HI,
            $request->Martes_HF,
            $request->Miercoles_HI,
            $request->Miercoles_HF,
            $request->Jueves_HI,
            $request->Jueves_HF,
            $request->Viernes_HI,
            $request->Viernes_HF,
            $request->Sabado_HI,
            $request->Sabado_HF,
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function update(Request $request, $clave_horario)
    {
        $request->validate([
            'Tarjeta' => 'required|integer',
            'ClaveAula' => 'required|string|max:20',
            'ClaveGrupo' => 'required|string|max:20',
            'ClaveAsignatura' => 'required|string|max:20',
            'Lunes_HI' => 'required|date_format:H:i:s',
            'Lunes_HF' => 'required|date_format:H:i:s',
            'IdPeriodoEscolar' => 'required|integer',
            'Martes_HI' => 'nullable|date_format:H:i:s',
            'Martes_HF' => 'nullable|date_format:H:i:s',
            'Miercoles_HI' => 'nullable|date_format:H:i:s',
            'Miercoles_HF' => 'nullable|date_format:H:i:s',
            'Jueves_HI' => 'nullable|date_format:H:i:s',
            'Jueves_HF' => 'nullable|date_format:H:i:s',
            'Viernes_HI' => 'nullable|date_format:H:i:s',
            'Viernes_HF' => 'nullable|date_format:H:i:s',
            'Sabado_HI' => 'nullable|date_format:H:i:s',
            'Sabado_HF' => 'nullable|date_format:H:i:s',
        ]);

        $response = DB::select('SELECT update_horario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS message', [
            $clave_horario,
            $request->Tarjeta,
            $request->ClaveAula,
            $request->ClaveGrupo,
            $request->ClaveAsignatura,
            $request->Lunes_HI,
            $request->Lunes_HF,
            $request->IdPeriodoEscolar,
            $request->Martes_HI,
            $request->Martes_HF,
            $request->Miercoles_HI,
            $request->Miercoles_HF,
            $request->Jueves_HI,
            $request->Jueves_HF,
            $request->Viernes_HI,
            $request->Viernes_HF,
            $request->Sabado_HI,
            $request->Sabado_HF,
        ]);

        return response()->json(['message' => $response[0]->message]);
    }

    public function destroy($clave_horario)
    {
        $response = DB::select('SELECT delete_horario(?) AS message', [$clave_horario]);

        return response()->json(['message' => $response[0]->message]);
    }
}
