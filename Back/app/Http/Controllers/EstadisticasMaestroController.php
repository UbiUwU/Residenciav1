<?php

namespace App\Http\Controllers;

use App\Models\HorarioAsignaturaMaestro;
use App\Models\PeriodoEscolar;
use App\Models\Maestro;
use Illuminate\Http\Request;

class EstadisticasMaestroController extends Controller
{
    /**
     * Calcula estadísticas de un maestro en un período específico
     *
     * @param string $tarjeta
     * @param int $id_periodo_escolar
     * @return \Illuminate\Http\JsonResponse
     */
    public function estadisticasPorPeriodo($tarjeta, $id_periodo_escolar)
    {
        try {
            // Validar que el maestro existe y obtener sus datos
            $maestro = Maestro::with('departamento')->where('tarjeta', $tarjeta)->first();
            
            if (!$maestro) {
                return response()->json([
                    'success' => false,
                    'message' => 'El maestro con la tarjeta especificada no existe'
                ], 404);
            }

            // Validar que el período existe
            $periodo = PeriodoEscolar::find($id_periodo_escolar);
            if (!$periodo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El período escolar especificado no existe'
                ], 404);
            }

            // Obtener todos los horarios del maestro en el período específico
            $horarios = HorarioAsignaturaMaestro::with([
                'grupo',
                'asignatura.carreras', // Cargar las carreras de la asignatura
                'cargaDetalles.cargaGeneral.alumno'
            ])
            ->where('tarjeta', $tarjeta)
            ->where('idperiodoescolar', $id_periodo_escolar)
            ->get();

            // Calcular estadísticas
            $gruposAtendidos = $this->calcularGruposAtendidos($horarios);
            $numeroEstudiantes = $this->calcularNumeroEstudiantes($horarios);
            $asignaturas = $this->obtenerAsignaturasConDetalles($horarios);

            // Construir nombre completo del maestro
            $nombreCompleto = trim($maestro->nombre . ' ' . $maestro->apellidopaterno . ' ' . $maestro->apellidomaterno);

            return response()->json([
                'success' => true,
                'data' => [
                    'maestro' => [
                        'tarjeta' => $maestro->tarjeta,
                        'nombre_completo' => $nombreCompleto,
                        'departamento' => $maestro->departamento ? $maestro->departamento->nombre : 'No asignado',
                        'clave_departamento' => $maestro->departamento ? $maestro->departamento->id_departamento : null
                    ],
                    'periodo' => [
                        'id' => $periodo->id_periodo_escolar,
                        'nombre' => $periodo->nombre_periodo,
                        'codigo' => $periodo->codigoperiodo,
                        'fecha_inicio' => $periodo->fecha_inicio,
                        'fecha_fin' => $periodo->fecha_fin,
                        'estado' => $periodo->estado
                    ],
                    'estadisticas' => [
                        'grupos_atendidos' => $gruposAtendidos,
                        'numero_estudiantes' => $numeroEstudiantes,
                        'total_asignaturas' => count($asignaturas),
                        'asignaturas' => $asignaturas
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular las estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcula el número de grupos únicos atendidos por el maestro
     *
     * @param \Illuminate\Database\Eloquent\Collection $horarios
     * @return int
     */
    private function calcularGruposAtendidos($horarios)
    {
        $gruposUnicos = $horarios->unique('clavegrupo');
        return $gruposUnicos->count();
    }

    /**
     * Calcula el número total de estudiantes en todos los grupos del maestro
     *
     * @param \Illuminate\Database\Eloquent\Collection $horarios
     * @return int
     */
    private function calcularNumeroEstudiantes($horarios)
    {
        $totalEstudiantes = 0;
        
        foreach ($horarios as $horario) {
            // Contar estudiantes únicos por grupo
            $estudiantesEnGrupo = 0;
            
            foreach ($horario->cargaDetalles as $detalle) {
                // Asegurarnos de que cada estudiante se cuente solo una vez por grupo
                if ($detalle->cargaGeneral && $detalle->cargaGeneral->alumno) {
                    $estudiantesEnGrupo++;
                }
            }
            
            $totalEstudiantes += $estudiantesEnGrupo;
        }
        
        return $totalEstudiantes;
    }

    /**
     * Obtiene la lista de asignaturas únicas con sus carreras y cantidad de alumnos
     *
     * @param \Illuminate\Database\Eloquent\Collection $horarios
     * @return array
     */
    private function obtenerAsignaturasConDetalles($horarios)
    {
        $asignaturasUnicas = $horarios->unique('claveasignatura');
        
        $asignaturas = [];
        foreach ($asignaturasUnicas as $horario) {
            if ($horario->asignatura) {
                // Obtener carreras de la asignatura
                $carreras = $this->obtenerCarrerasDeAsignatura($horario->asignatura);
                
                // Calcular cantidad de alumnos para esta asignatura específica
                $alumnosPorAsignatura = $this->calcularAlumnosPorAsignatura($horarios, $horario->claveasignatura);
                
                $asignaturas[] = [
                    'clave' => $horario->asignatura->ClaveAsignatura,
                    'nombre' => $horario->asignatura->NombreAsignatura,
                    'creditos' => $horario->asignatura->Creditos,
                    'satca_teoricas' => $horario->asignatura->Satca_Teoricas,
                    'satca_practicas' => $horario->asignatura->Satca_Practicas,
                    'satca_total' => $horario->asignatura->Satca_Total,
                    'cantidad_alumnos' => $alumnosPorAsignatura,
                    'carreras' => $carreras
                ];
            }
        }
        
        return $asignaturas;
    }

    /**
     * Obtiene las carreras asociadas a una asignatura
     *
     * @param \App\Models\Asignatura $asignatura
     * @return array
     */
    private function obtenerCarrerasDeAsignatura($asignatura)
    {
        $carreras = [];
        
        if ($asignatura->carreras) {
            foreach ($asignatura->carreras as $carrera) {
                $carreras[] = [
                    'clave' => $carrera->clavecarrera,
                    'nombre' => $carrera->nombre,
                ];
            }
        }
        
        return $carreras;
    }

    /**
     * Calcula la cantidad de alumnos por asignatura específica
     *
     * @param \Illuminate\Database\Eloquent\Collection $horarios
     * @param string $claveAsignatura
     * @return int
     */
    private function calcularAlumnosPorAsignatura($horarios, $claveAsignatura)
    {
        $alumnosUnicos = [];
        
        // Filtrar solo los horarios de esta asignatura específica
        $horariosAsignatura = $horarios->where('claveasignatura', $claveAsignatura);
        
        foreach ($horariosAsignatura as $horario) {
            foreach ($horario->cargaDetalles as $detalle) {
                if ($detalle->cargaGeneral && $detalle->cargaGeneral->alumno) {
                    // Usar el número de control como identificador único
                    $numeroControl = $detalle->cargaGeneral->alumno->numerocontrol;
                    $alumnosUnicos[$numeroControl] = true;
                }
            }
        }
        
        return count($alumnosUnicos);
    }
}