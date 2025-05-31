<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsignaturaController extends Controller
{
    public function index()
    {
        // Obtener todas las asignaturas
        $asignaturas = DB::select('SELECT * FROM get_all_asignaturas()');
        return response()->json($asignaturas);
    }

    public function show($clave)
    {
        // Obtener una asignatura por clave
        $asignatura = DB::select('SELECT * FROM get_asignatura_by_clave(CAST(? AS varchar))', [$clave]);

        if (empty($asignatura)) {
            return response()->json(['message' => 'Asignatura no encontrada'], 404);
        }

        return response()->json($asignatura[0]);
    }

    public function getByClaveComplete($clave)
    {
        $asignatura = DB::select('SELECT * FROM get_asignatura_by_clave_complete(CAST(? AS varchar))', [$clave]);

        if (empty($asignatura)) {
            return response()->json(['message' => 'Asignatura no encontrada'], 404);
        }

        $json = $asignatura[0]->get_asignatura_by_clave_complete;
        return response()->json(json_decode($json));
    }

    public function getByCarrera($claveCarrera)
    {
        // Obtener asignaturas por carrera
        $asignaturas = DB::select('SELECT * FROM get_asignaturas_by_carrera(CAST(? AS varchar))', [$claveCarrera]);
        return response()->json($asignaturas);
    }

    public function getByCarreraAndSemestre($claveCarrera, $semestre)
    {
        // Obtener asignaturas por carrera y semestre
        $asignaturas = DB::select(
            'SELECT * FROM get_asignaturas_by_carrera_and_semestre(CAST(? AS varchar), CAST(? AS smallint))',
            [$claveCarrera, $semestre]
        );
        return response()->json($asignaturas);
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'clave_asignatura' => 'required|string|max:50',
            'clave_carrera' => 'required|string|max:20',
            'nombre' => 'required|string|max:100',
            'creditos' => 'required|integer',
            'horas_teoricas' => 'required|integer',
            'horas_practicas' => 'required|integer',
            'semestre' => 'required|integer|min:1|max:12',
            'posicion' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Insertar nueva asignatura
        $result = DB::select(
            'SELECT insert_asignatura_carrera(CAST(? AS varchar), CAST(? AS varchar), CAST(? AS varchar), 
            CAST(? AS integer), CAST(? AS integer), CAST(? AS integer), CAST(? AS smallint), CAST(? AS smallint)) AS result',
            [
                $request->clave_asignatura,
                $request->clave_carrera,
                $request->nombre,
                $request->creditos,
                $request->horas_teoricas,
                $request->horas_practicas,
                $request->semestre,
                $request->posicion
            ]
        );

        return response()->json(['message' => $result[0]->result], 201);
    }

    public function update(Request $request, $clave)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'clave_carrera' => 'required|string|max:20',
            'nombre' => 'required|string|max:100',
            'creditos' => 'required|integer',
            'horas_teoricas' => 'required|integer',
            'horas_practicas' => 'required|integer',
            'semestre' => 'required|integer|min:1|max:12',
            'posicion' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Actualizar asignatura
        $result = DB::select(
            'SELECT update_asignatura_carrera(CAST(? AS varchar), CAST(? AS varchar), CAST(? AS varchar), 
            CAST(? AS integer), CAST(? AS integer), CAST(? AS integer), CAST(? AS smallint), CAST(? AS smallint)) AS result',
            [
                $clave,
                $request->clave_carrera,
                $request->nombre,
                $request->creditos,
                $request->horas_teoricas,
                $request->horas_practicas,
                $request->semestre,
                $request->posicion
            ]
        );

        return response()->json(['message' => $result[0]->result]);
    }

    public function destroy($clave)
    {
        // Eliminar asignatura
        $result = DB::select('SELECT delete_asignatura(CAST(? AS varchar)) AS result', [$clave]);

        if (strpos($result[0]->result, 'Error') !== false) {
            return response()->json(['message' => $result[0]->result], 404);
        }

        return response()->json(['message' => $result[0]->result]);
    }

    public function getByTarjetaComplete($clave)
    {
        // Validar que la tarjeta sea un número positivo
        if (!is_numeric($clave) || $clave <= 0) {
            return response()->json(['message' => 'Tarjeta inválida'], 400);
        }

        // Obtener todas las asignaturas completas asociadas a la tarjeta (maestro)
        $asignaturas = DB::select('SELECT * FROM get_asignaturas_by_tarjeta_complete(CAST(? AS bigint))', [$clave]);

        if (empty($asignaturas)) {
            return response()->json(['message' => 'No se encontraron asignaturas para esta tarjeta'], 404);
        }

        $json = $asignaturas[0]->get_asignaturas_by_tarjeta_complete;
        return response()->json(json_decode($json));
    }
    public function getDetalleGruposByTarjeta($clave)
    {
        // Validar que la tarjeta sea un número positivo
        if (!is_numeric($clave) || $clave <= 0) {
            return response()->json(['message' => 'Tarjeta inválida'], 400);
        }

        // Ejecutar la función PostgreSQL
        $detalles = DB::select('SELECT * FROM get_Detalle_Grupos__Para_Calificaciones_By_Tarjeta(CAST(? AS bigint))', [$clave]);

        if (empty($detalles)) {
            return response()->json(['message' => 'No se encontraron datos para esta tarjeta'], 404);
        }

        $json = $detalles[0]->get_detalle_grupos__para_calificaciones_by_tarjeta;
        return response()->json(json_decode($json));
    }

}