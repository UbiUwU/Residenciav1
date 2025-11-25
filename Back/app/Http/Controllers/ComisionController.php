<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComisionController extends Controller
{
    // Listar todas las comisiones con relaciones
    public function index()
    {
        $comisiones = Comision::with([
            'maestros' => function ($query) {
                $query->soloNombre();
            },
            'periodoEscolar' => function ($query) {
                $query->periodos();
            },
            'tipoEvento',
            'fechas',
        ])
            ->orderBy('id_comision', 'asc')
            ->get();

        return response()->json($comisiones);
    }

    public function indexClean()
    {
        $comisiones = Comision::all();

        return response()->json($comisiones);
    }

    // Método para filtrar comisiones por período escolar
    public function indexByPeriodo($idPeriodoEscolar)
    {
        $validator = Validator::make(
            ['id_periodo_escolar' => $idPeriodoEscolar],
            ['id_periodo_escolar' => 'required|integer|exists:periodo_escolar,id_periodo_escolar']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro inválido',
                'errors' => $validator->errors(),
            ], 422);
        }

        $comisiones = Comision::with([

        ])
            ->where('id_periodo_escolar', $idPeriodoEscolar)
            ->orderBy('id_comision', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comisiones,
            'count' => $comisiones->count(),
            'filtros' => ['periodo_escolar' => $idPeriodoEscolar],
        ]);
    }

    // Método para filtrar comisiones por maestro (tarjeta)
    public function indexByMaestro($tarjetaMaestro)
    {
        $validator = Validator::make(
            ['tarjeta_maestro' => $tarjetaMaestro],
            ['tarjeta_maestro' => 'required|integer|exists:maestros,tarjeta']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro inválido',
                'errors' => $validator->errors(),
            ], 422);
        }

        $comisiones = Comision::with([
            'maestros' => function ($query) {
                $query->soloNombre();
            },
        ])
            ->whereHas('maestros', function ($query) use ($tarjetaMaestro) {
                $query->where('tarjeta', $tarjetaMaestro);
            })
            ->orderBy('id_comision', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comisiones,
            'count' => $comisiones->count(),
            'filtros' => ['tarjeta_maestro' => $tarjetaMaestro],
        ]);
    }

    // Método para filtrar comisiones por período y maestro combinados
    public function indexByPeriodoAndMaestro($idPeriodoEscolar, $tarjetaMaestro)
    {
        $validator = Validator::make(
            [
                'id_periodo_escolar' => $idPeriodoEscolar,
                'tarjeta_maestro' => $tarjetaMaestro,
            ],
            [
                'id_periodo_escolar' => 'required|integer|exists:periodo_escolar,id_periodo_escolar',
                'tarjeta_maestro' => 'required|integer|exists:maestros,tarjeta',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetros inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        $comisiones = Comision::with([
            'maestros' => function ($query) {
                $query->soloNombre();
            },
            'periodoEscolar' => function ($query) {
                $query->periodos();
            },
            'tipoEvento',
            'fechas',
        ])
            ->where('id_periodo_escolar', $idPeriodoEscolar)
            ->whereHas('maestros', function ($query) use ($tarjetaMaestro) {
                $query->where('tarjeta', $tarjetaMaestro);
            })
            ->orderBy('id_comision', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comisiones,
            'count' => $comisiones->count(),
            'filtros' => [
                'periodo_escolar' => $idPeriodoEscolar,
                'tarjeta_maestro' => $tarjetaMaestro,
            ],
        ]);
    }

    // Mostrar una comisión específica
    public function show($id)
    {
        $comision = Comision::with([
            'maestros' => function ($query) {
                $query->soloNombre();
            },
            'periodoEscolar' => function ($query) {
                $query->periodos();
            },
            'tipoEvento',
            'fechas',
        ])
            ->findOrFail($id);

        return response()->json($comision);
    }

    // Crear una nueva comisión
    public function store(Request $request)
    {
        $request->validate([
            'nombre_evento' => 'required|string|max:100',
            'id_tipo_evento' => 'required|integer|exists:tipo_evento,id_tipo_evento',
            'id_periodo_escolar' => 'required|integer|exists:periodo_escolar,id_periodo_escolar',
            'estado' => 'nullable|string',
            'folio' => 'nullable|string|max:50',
            'remitente_nombre' => 'nullable|string|max:100',
            'remitente_puesto' => 'nullable|string|max:100',
            'lugar' => 'nullable|string|max:255',
            'motivo' => 'nullable|string',
            'tipo_comision' => 'nullable|string',
            'permiso_constancia' => 'nullable|boolean',
            'fechas' => 'nullable|array',
            'fechas.*.fecha' => 'required|date',
            'fechas.*.hora_inicio' => 'nullable|date_format:H:i',
            'fechas.*.hora_fin' => 'nullable|date_format:H:i',
            'fechas.*.duracion' => 'nullable|string',
            'fechas.*.observaciones' => 'nullable|string',
            'maestros' => 'nullable|array',
            'maestros.*' => 'required|integer|exists:maestros,tarjeta',
        ]);

        DB::beginTransaction();

        try {
            $comision = Comision::create($request->only([
                'folio',
                'nombre_evento',
                'id_tipo_evento',
                'id_periodo_escolar',
                'estado',
                'remitente_nombre',
                'remitente_puesto',
                'lugar',
                'motivo',
                'tipo_comision',
                'permiso_constancia',
            ]));

            // Insertar fechas si vienen
            if ($request->has('fechas')) {
                foreach ($request->fechas as $fecha) {
                    $comision->fechas()->create($fecha);
                }
            }

            // Relacionar maestros si vienen
            if ($request->has('maestros')) {
                $comision->maestros()->sync($request->maestros);
            }

            DB::commit();

            return response()->json($comision->load(['tipoEvento', 'periodoEscolar', 'fechas', 'maestros']), 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Actualizar comisión
    public function update(Request $request, $id)
    {
        $comision = Comision::findOrFail($id);

        $request->validate([
            'nombre_evento' => 'nullable|string|max:100',
            'id_tipo_evento' => 'nullable|integer|exists:tipo_evento,id_tipo_evento',
            'id_periodo_escolar' => 'nullable|integer|exists:periodo_escolar,id_periodo_escolar',
            'estado' => 'nullable|string',
            'folio' => 'nullable|string|max:50',
            'remitente_nombre' => 'nullable|string|max:100',
            'remitente_puesto' => 'nullable|string|max:100',
            'lugar' => 'nullable|string|max:255',
            'motivo' => 'nullable|string',
            'tipo_comision' => 'nullable|string',
            'permiso_constancia' => 'nullable|boolean',
            'fechas' => 'nullable|array',
            'fechas.*.fecha' => 'required|date',
            'fechas.*.hora_inicio' => 'nullable|date_format:H:i',
            'fechas.*.hora_fin' => 'nullable|date_format:H:i',
            'fechas.*.duracion' => 'nullable|string',
            'fechas.*.observaciones' => 'nullable|string',
            'maestros' => 'nullable|array',
            'maestros.*' => 'required|integer|exists:maestros,tarjeta',
        ]);

        DB::beginTransaction();

        try {
            $comision->update($request->only([
                'folio',
                'nombre_evento',
                'id_tipo_evento',
                'id_periodo_escolar',
                'estado',
                'remitente_nombre',
                'remitente_puesto',
                'lugar',
                'motivo',
                'tipo_comision',
                'permiso_constancia',
            ]));

            // Actualizar fechas
            if ($request->has('fechas')) {
                $comision->fechas()->delete();
                foreach ($request->fechas as $fecha) {
                    $comision->fechas()->create($fecha);
                }
            }

            // Actualizar maestros
            if ($request->has('maestros')) {
                $comision->maestros()->sync($request->maestros);
            }

            DB::commit();

            return response()->json($comision->load(['tipoEvento', 'periodoEscolar', 'fechas', 'maestros']));
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Eliminar comisión
    public function destroy($id)
    {
        $comision = Comision::findOrFail($id);
        $comision->delete();

        return response()->json(['message' => 'Comisión eliminada correctamente']);
    }
}
