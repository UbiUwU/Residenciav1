<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Presentacion;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentacionController extends Controller
{
    // Crear/Actualizar toda la presentación de una asignatura
    public function store(Request $request, $claveAsignatura)
    {
        $request->validate([
            'caracterizacion' => 'required|array',
            'caracterizacion.*.punto' => 'required|string',
            'intencion' => 'required|array',
            'intencion.*.tema' => 'required|string',
            'intencion.*.descripcion' => 'required|string'
        ]);

        // Verificar que la asignatura existe
        $asignatura = Asignatura::findOrFail($claveAsignatura);

        // Usar transacción para garantizar integridad
        return DB::transaction(function () use ($asignatura, $request) {
            
            // Crear o encontrar la presentación principal
            $presentacion = Presentacion::firstOrCreate([
                'Clave_Asignatura' => $asignatura->ClaveAsignatura
            ]);

            // Sincronizar caracterizaciones
            $presentacion->caracterizaciones()->delete();
            foreach ($request->caracterizacion as $index => $item) {
                $presentacion->caracterizaciones()->create([
                    'Orden' => $index + 1,
                    'Punto' => $item['punto']
                ]);
            }

            // Sincronizar intenciones
            $presentacion->intenciones()->delete();
            foreach ($request->intencion as $index => $item) {
                $presentacion->intenciones()->create([
                    'Orden' => $index + 1,
                    'Tema' => $item['tema'],
                    'Descripcion' => $item['descripcion']
                ]);
            }

            return response()->json([
                'message' => 'Presentación guardada exitosamente',
                'data' => $presentacion->load(['caracterizaciones', 'intenciones'])
            ], 201);
        });
    }

    // Obtener la presentación completa de una asignatura
    public function show($claveAsignatura)
    {
        $presentacion = Presentacion::with([
                'caracterizaciones' => function($query) {
                    $query->orderBy('Orden');
                },
                'intenciones' => function($query) {
                    $query->orderBy('Orden');
                }
            ])
            ->where('Clave_Asignatura', $claveAsignatura)
            ->firstOrFail();

        return response()->json($presentacion);
    }
}