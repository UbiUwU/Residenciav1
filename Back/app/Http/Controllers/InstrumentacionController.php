<?php

namespace App\Http\Controllers;

use App\Models\Instrumentacion;
use Illuminate\Http\Request;

class InstrumentacionController extends Controller
{
    // Index completo con toda la información
    public function indexCompleto()
    {
        $instrumentaciones = Instrumentacion::with([
            'asignatura',
            'maestro',
            'carrera',
            'periodoEscolar',
            'departamento',
            'competencias.tema',
            'competencias.competenciasGenericas',
            'competencias.actividadesEnsenanza',
            'competencias.indicadoresAlcance',
            'competencias.nivelesDesempeno.indicadoresAlcance',
            'competencias.evaluaciones.indicadoresAlcance',
            'apoyosDidacticos',
            'calendarizaciones',
        ])->get();

        return response()->json([
            'data' => $instrumentaciones,
        ]);
    }

    // Index básico con datos principales
    public function indexBasico()
    {
        $instrumentaciones = Instrumentacion::select(
            'instrumentacion.id_instrumentacion',
            'instrumentacion.estado',
            'instrumentacion.fecha_creacion',
            'maestros.nombre as nombre_maestro',
            'maestros.apellidopaterno as apellido_paterno_maestro',
            'maestros.apellidomaterno as apellido_materno_maestro',
            'periodo_escolar.nombre_periodo as periodo_escolar',
            'carreras.nombre',
            'asignatura.NombreAsignatura',
            'asignatura.ClaveAsignatura',
            'departamentos.nombre'
        )
            ->join('maestros', 'instrumentacion.tarjeta_profesor', '=', 'maestros.tarjeta')
            ->join('periodo_escolar', 'instrumentacion.id_periodo_escolar', '=', 'periodo_escolar.id_periodo_escolar')
            ->join('carreras', 'instrumentacion.clavecarrera', '=', 'carreras.clavecarrera')
            ->join('asignatura', 'instrumentacion.ClaveAsignatura', '=', 'asignatura.ClaveAsignatura')
            ->join('departamentos', 'instrumentacion.id_departamento', '=', 'departamentos.id_departamento')
            ->get();

        return response()->json([
            'data' => $instrumentaciones,
        ]);
    }

    // Búsqueda por periodo escolar
    public function buscarPorPeriodo($idPeriodo)
    {
        $instrumentaciones = Instrumentacion::select(
            'instrumentacion.id_instrumentacion',
            'instrumentacion.estado',
            'instrumentacion.fecha_creacion',
            'maestros.nombre as nombre_maestro',
            'maestros.apellidopaterno as apellido_paterno_maestro',
            'maestros.apellidomaterno as apellido_materno_maestro',
            'periodo_escolar.nombre_periodo as periodo_escolar',
            'carreras.nombre',
            'asignatura.NombreAsignatura',
            'asignatura.ClaveAsignatura',
            'departamentos.nombre'
        )
            ->join('maestros', 'instrumentacion.tarjeta_profesor', '=', 'maestros.tarjeta')
            ->join('periodo_escolar', 'instrumentacion.id_periodo_escolar', '=', 'periodo_escolar.id_periodo_escolar')
            ->join('carreras', 'instrumentacion.clavecarrera', '=', 'carreras.clavecarrera')
            ->join('asignatura', 'instrumentacion.ClaveAsignatura', '=', 'asignatura.ClaveAsignatura')
            ->join('departamentos', 'instrumentacion.id_departamento', '=', 'departamentos.id_departamento')
            ->get();

        return response()->json([
            'data' => $instrumentaciones,
        ]);
    }

    // Búsqueda por maestro
    public function buscarPorMaestro($tarjetaMaestro)
    {
        $instrumentaciones = Instrumentacion::select(
            'instrumentacion.id_instrumentacion',
            'instrumentacion.estado',
            'instrumentacion.fecha_creacion',
            'maestros.nombre as nombre_maestro',
            'maestros.apellidopaterno as apellido_paterno_maestro',
            'maestros.apellidomaterno as apellido_materno_maestro',
            'periodo_escolar.nombre_periodo as periodo_escolar',
            'carreras.nombre',
            'asignatura.NombreAsignatura',
            'asignatura.ClaveAsignatura',
            'departamentos.nombre'
        )
            ->join('maestros', 'instrumentacion.tarjeta_profesor', '=', 'maestros.tarjeta')
            ->join('periodo_escolar', 'instrumentacion.id_periodo_escolar', '=', 'periodo_escolar.id_periodo_escolar')
            ->join('carreras', 'instrumentacion.clavecarrera', '=', 'carreras.clavecarrera')
            ->join('asignatura', 'instrumentacion.ClaveAsignatura', '=', 'asignatura.ClaveAsignatura')
            ->join('departamentos', 'instrumentacion.id_departamento', '=', 'departamentos.id_departamento')
            ->get();

        return response()->json([
            'data' => $instrumentaciones,
        ]);
    }

    // Búsqueda por departamento
    public function buscarPorDepartamento($idDepartamento)
    {
        $instrumentaciones = Instrumentacion::select(
            'instrumentacion.id_instrumentacion',
            'instrumentacion.estado',
            'instrumentacion.fecha_creacion',
            'maestros.nombre as nombre_maestro',
            'maestros.apellidopaterno as apellido_paterno_maestro',
            'maestros.apellidomaterno as apellido_materno_maestro',
            'periodo_escolar.nombre_periodo as periodo_escolar',
            'carreras.nombre',
            'asignatura.NombreAsignatura',
            'asignatura.ClaveAsignatura',
            'departamentos.nombre'
        )
            ->join('maestros', 'instrumentacion.tarjeta_profesor', '=', 'maestros.tarjeta')
            ->join('periodo_escolar', 'instrumentacion.id_periodo_escolar', '=', 'periodo_escolar.id_periodo_escolar')
            ->join('carreras', 'instrumentacion.clavecarrera', '=', 'carreras.clavecarrera')
            ->join('asignatura', 'instrumentacion.ClaveAsignatura', '=', 'asignatura.ClaveAsignatura')
            ->join('departamentos', 'instrumentacion.id_departamento', '=', 'departamentos.id_departamento')
            ->get();

        return response()->json([
            'data' => $instrumentaciones,
        ]);
    }

    // Búsqueda por carrera
    public function buscarPorCarrera($claveCarrera)
    {
        $instrumentaciones = Instrumentacion::select(
            'instrumentacion.id_instrumentacion',
            'instrumentacion.estado',
            'instrumentacion.fecha_creacion',
            'maestros.nombre as nombre_maestro',
            'maestros.apellidopaterno as apellido_paterno_maestro',
            'maestros.apellidomaterno as apellido_materno_maestro',
            'periodo_escolar.nombre_periodo as periodo_escolar',
            'carreras.nombre',
            'asignatura.NombreAsignatura',
            'asignatura.ClaveAsignatura',
            'departamentos.nombre'
        )
            ->join('maestros', 'instrumentacion.tarjeta_profesor', '=', 'maestros.tarjeta')
            ->join('periodo_escolar', 'instrumentacion.id_periodo_escolar', '=', 'periodo_escolar.id_periodo_escolar')
            ->join('carreras', 'instrumentacion.clavecarrera', '=', 'carreras.clavecarrera')
            ->join('asignatura', 'instrumentacion.ClaveAsignatura', '=', 'asignatura.ClaveAsignatura')
            ->join('departamentos', 'instrumentacion.id_departamento', '=', 'departamentos.id_departamento')
            ->get();

        return response()->json([
            'data' => $instrumentaciones,
        ]);
    }

    // Búsqueda combinada con múltiples parámetros
    public function buscarCombinada(Request $request)
    {
        $query = Instrumentacion::select(
            'instrumentacion.id_instrumentacion',
            'instrumentacion.estado',
            'instrumentacion.fecha_creacion',
            'maestros.nombre as nombre_maestro',
            'maestros.apellidopaterno as apellido_paterno_maestro',
            'maestros.apellidomaterno as apellido_materno_maestro',
            'periodo_escolar.nombre_periodo as periodo_escolar',
            'carreras.nombre as nombre_carrera',
            'asignatura.NombreAsignatura',
            'asignatura.ClaveAsignatura',
            'departamentos.nombre as nombre_departamento'
        )
            ->join('maestros', 'instrumentacion.tarjeta_profesor', '=', 'maestros.tarjeta')
            ->join('periodo_escolar', 'instrumentacion.id_periodo_escolar', '=', 'periodo_escolar.id_periodo_escolar')
            ->join('carreras', 'instrumentacion.clavecarrera', '=', 'carreras.clavecarrera')
            ->join('asignatura', 'instrumentacion.ClaveAsignatura', '=', 'asignatura.ClaveAsignatura')
            ->join('departamentos', 'instrumentacion.id_departamento', '=', 'departamentos.id_departamento');

        if ($request->has('id_periodo_escolar')) {
            $query->where('instrumentacion.id_periodo_escolar', $request->periodoc);
        }

        if ($request->has('tarjeta_profesor')) {
            $query->where('instrumentacion.tarjeta_profesor', $request->tarjetac);
        }

        if ($request->has('id_departamento')) {
            $query->where('instrumentacion.id_departamento', $request->departamentoc);
        }

        if ($request->has('clavecarrera')) {
            $query->where('instrumentacion.clavecarrera', $request->clavecarrerac);
        }

        if ($request->has('ClaveAsignatura')) {
            $query->where('instrumentacion.ClaveAsignatura', $request->ClaveAsignaturac);
        }

        if ($request->has('estado')) {
            $query->where('instrumentacion.estado', $request->estadoc);
        }

        $instrumentaciones = $query->get();

        return response()->json([
            'data' => $instrumentaciones,
        ]);
    }

    // Crear una nueva instrumentación
    public function create(Request $request)
    {
        $request->validate([
            'ClaveAsignatura' => 'required|exists:asignatura,ClaveAsignatura',
            'tarjeta_profesor' => 'required|exists:maestros,tarjeta',
            'clavecarrera' => 'required|exists:carreras,clavecarrera',
            'id_periodo_escolar' => 'required|exists:periodo_escolar,id_periodo_escolar',
            'id_departamento' => 'required|exists:departamentos,id_departamento',
            'nombre_jefe_academico' => 'nullable|string|max:60',
            'estado' => 'nullable|in:borrador,revision,aprobado,rechazado',
        ]);

        $instrumentacion = Instrumentacion::create([
            'ClaveAsignatura' => $request->ClaveAsignatura,
            'tarjeta_profesor' => $request->tarjeta_profesor,
            'clavecarrera' => $request->clavecarrera,
            'id_periodo_escolar' => $request->id_periodo_escolar,
            'id_departamento' => $request->id_departamento,
            'nombre_jefe_academico' => $request->nombre_jefe_academico,
            'estado' => $request->estado ?? 'borrador',
        ]);

        return response()->json([
            'message' => 'Instrumentación creada exitosamente',
            'data' => $instrumentacion->load(['asignatura', 'maestro', 'carrera', 'periodoEscolar', 'departamento']),
        ], 201);
    }

    // Actualizar una instrumentación existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'ClaveAsignatura' => 'sometimes|required|exists:asignatura,ClaveAsignatura',
            'tarjeta_profesor' => 'sometimes|required|exists:maestros,tarjeta',
            'clavecarrera' => 'sometimes|required|exists:carreras,clavecarrera',
            'id_periodo_escolar' => 'sometimes|required|exists:periodo_escolar,id_periodo_escolar',
            'id_departamento' => 'sometimes|required|exists:departamentos,id_departamento',
            'nombre_jefe_academico' => 'nullable|string|max:60',
            'estado' => 'nullable|in:borrador,revision,aprobado,rechazado',
        ]);

        $instrumentacion = Instrumentacion::findOrFail($id);

        $instrumentacion->update($request->all());

        return response()->json([
            'message' => 'Instrumentación actualizada exitosamente',
            'data' => $instrumentacion->load(['asignatura', 'maestro', 'carrera', 'periodoEscolar', 'departamento']),
        ]);
    }

    // Obtener una instrumentación específica
    public function show($id)
    {
        $instrumentacion = Instrumentacion::with([
            'asignatura',
            'asignatura.competencias',
            'asignatura.presentacion.caracterizaciones',
            'asignatura.presentacion.intenciones',
            'maestro',
            'carrera',
            'periodoEscolar',
            'departamento',
            'competencias' => function ($query) {
                $query->with([
                    'tema',
                    'tema.subtemas',
                    'tema.actividadesAprendizaje',
                    'competenciasGenericas',
                    'actividadesEnsenanza',
                    'indicadoresAlcance',
                    'nivelesDesempeno.indicadoresAlcance',
                    'evaluaciones.indicadoresAlcance',
                ]);
            },
            'apoyosDidacticos',
            'calendarizaciones',
        ])->findOrFail($id);

        return response()->json([
            'data' => $instrumentacion,
        ]);
    }
}
