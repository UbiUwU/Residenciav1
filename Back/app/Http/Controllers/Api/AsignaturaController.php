<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsignaturaController extends Controller
{
    /**
     * Obtener todas las asignaturas con su información completa
     */
    public function index()
    {
        try {
            // Obtener el último período
            $ultimoPeriodo = DB::table('periodo_escolar')
                ->orderByDesc('id_periodo_escolar')
                ->first();

            // Obtener el departamento de Sistemas y Computación
            $departamento = DB::table('departamentos')
                ->where('nombre', 'Sistemas y Computación')
                ->first();

            // Obtener todas las asignaturas con su información relacionada
            $asignaturas = DB::table('asignatura')
                ->get()
                ->map(function ($asignatura) {
                    return $this->enriquecerAsignatura($asignatura);
                });

            return response()->json([
                'periodo_escolar' => $ultimoPeriodo,
                'departamento' => $departamento,
                'asignaturas' => $asignaturas
            ]);

        } catch (\Exception $e) {
            Log::error('Error en AsignaturaController@index: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener las asignaturas'], 500);
        }
    }

    /**
     * Obtener una asignatura específica por su clave
     */
    public function show($clave)
    {
        try {
            $asignatura = DB::table('asignatura')
                ->where('ClaveAsignatura', $clave)
                ->first();

            if (!$asignatura) {
                return response()->json(['error' => 'Asignatura no encontrada'], 404);
            }

            $asignaturaCompleta = $this->enriquecerAsignatura($asignatura);

            return response()->json($asignaturaCompleta);

        } catch (\Exception $e) {
            Log::error('Error en AsignaturaController@show: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener la asignatura'], 500);
        }
    }

    /**
     * Enriquecer una asignatura con toda su información relacionada
     */
    private function enriquecerAsignatura($asignatura)
    {
        // Obtener presentación
        $presentacion = DB::table('presentacion')
            ->where('Clave_Asignatura', $asignatura->ClaveAsignatura)
            ->first();

        // Obtener competencias
        $competencias = DB::table('competencia')
            ->where('Clave_Asignatura', $asignatura->ClaveAsignatura)
            ->get()
            ->map(function ($competencia) {
                $competencia->Tipo_Competencia = json_decode($competencia->Tipo_Competencia);
                return $competencia;
            });

        // Obtener temas con sus relaciones
        $temas = DB::table('tema')
            ->where('Clave_Asignatura', $asignatura->ClaveAsignatura)
            ->orderBy('Numero')
            ->get()
            ->map(function ($tema) {
                // Subtemas
                $tema->subtemas = DB::table('subtema')
                    ->where('Tema_id', $tema->id_Tema)
                    ->get();

                // Competencias del tema
                $tema->competencias = DB::table('competencia_tema')
                    ->where('Tema_id', $tema->id_Tema)
                    ->get()
                    ->map(function ($compTema) {
                        $compTema->Tipo_Competencia = json_decode($compTema->Tipo_Competencia);
                        return $compTema;
                    });

                // Actividades de aprendizaje
                $tema->actividades_aprendizaje = DB::table('actividad_aprendizaje')
                    ->where('Tema_id', $tema->id_Tema)
                    ->get();

                return $tema;
            });

        return [
            'asignatura' => $asignatura,
            'presentacion' => $presentacion,
            'competencias' => $competencias,
            'temas' => $temas
        ];
    }
}