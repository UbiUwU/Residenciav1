<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AvanceController extends Controller
{
    public function obtenerAvancesCompletos(Request $request)
{
    $tarjeta = $request->query('tarjeta');

    $resultados = DB::select(
        'SELECT * FROM obtener_avances_completos(?)',
        [$tarjeta]
    );

    // Convertir cada resultado a array y decodificar los campos JSON necesarios
    $respuesta = array_map(function ($item) {
        $item = (array) $item;

        // Decodificar campos JSON si existen
        if (isset($item['detalles'])) {
            $item['detalles'] = json_decode($item['detalles'], true);
        }
        if (isset($item['presentacion'])) {
            $item['presentacion'] = json_decode($item['presentacion'], true);
        }

        return $item;
    }, $resultados);

    return response()->json($respuesta);
}

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clave_asignatura' => 'required|string|max:20',
            'tarjeta_profesor' => 'required|integer',
            'periodo' => 'required|string|max:20',
            'estado' => 'nullable|string|in:Borrador,En Revisión,Aprobado,Rechazado',
            'clave_horario' => 'nullable|integer',
            'temas' => 'required|array|min:1',
            'temas.*.tema' => 'required|string',
            'temas.*.subtema' => 'nullable|string',
            'temas.*.fecha_programada_inicio' => 'required|date',
            'temas.*.fecha_programada_fin' => 'required|date',
            'temas.*.evaluacion_programada_inicio' => 'nullable|date',
            'temas.*.evaluacion_programada_fin' => 'nullable|date',
            'temas.*.porcentaje' => 'required|integer|min:0|max:100',
            'temas.*.observaciones' => 'nullable|string',
            'temas.*.completado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        try {
            $idAvance = DB::selectOne('SELECT crear_avance_programatico_completo(?, ?, ?, ?, ?, ?) AS id', [
                $data['clave_asignatura'],
                $data['tarjeta_profesor'],
                $data['periodo'],
                json_encode($data['temas']),
                $data['estado'] ?? 'Borrador',
                $data['clave_horario'] ?? null
            ]);

            return response()->json([
                'mensaje' => 'Avance programático creado correctamente',
                'id_avance' => $idAvance->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el avance',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
    public function actualizarAvance(Request $request, int $id)
    {
        $request->validate([
            'firma_profesor' => 'nullable|string|max:100',
            'firma_jefe' => 'nullable|string|max:100',
            'estado' => 'nullable|string|in:Borrador,En Revisión,Aprobado,Rechazado',
            'detalles' => 'nullable|array',
            'detalles.*.id_avance_detalle' => 'required|integer',
            'detalles.*.fecha_programada_inicio' => 'nullable|date',
            'detalles.*.fecha_programada_fin' => 'nullable|date',
            'detalles.*.fecha_real_inicio' => 'nullable|date',
            'detalles.*.fecha_real_fin' => 'nullable|date',
            'detalles.*.evaluacion_programada_inicio' => 'nullable|date',
            'detalles.*.evaluacion_programada_fin' => 'nullable|date',
            'detalles.*.evaluacion_realizada_inicio' => 'nullable|date',
            'detalles.*.evaluacion_realizada_fin' => 'nullable|date',
            'detalles.*.porcentaje' => 'nullable|integer|min:0|max:100',
            'detalles.*.observaciones' => 'nullable|string'
        ]);

        $firmaProfesor = $request->input('firma_profesor');
        $firmaJefe = $request->input('firma_jefe');
        $estado = $request->input('estado');
        $detalles = $request->input('detalles', []);

        try {
            $resultado = DB::selectOne('
            SELECT actualizar_avance_y_detalles(
                :id_avance,
                :firma_profesor,
                :firma_jefe,
                :estado,
                :detalles::jsonb
            )
        ', [
                'id_avance' => $id,
                'firma_profesor' => $firmaProfesor,
                'firma_jefe' => $firmaJefe,
                'estado' => $estado,
                'detalles' => json_encode($detalles),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Avance actualizado correctamente',
                'data' => $resultado->actualizar_avance_y_detalles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el avance',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
