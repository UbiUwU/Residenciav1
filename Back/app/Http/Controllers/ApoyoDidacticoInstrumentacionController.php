<?php

namespace App\Http\Controllers;

use App\Models\ApoyoDidacticoInstrumentacion;
use Illuminate\Http\Request;

class ApoyoDidacticoInstrumentacionController extends Controller
{
    // Crear un apoyo didáctico
    public function createOne(Request $request)
    {
        $request->validate([
            'id_instrumentacion' => 'required|exists:instrumentacion,id_instrumentacion',
            'descripcion' => 'required|string',
            'orden' => 'nullable|integer'
        ]);

        $apoyoDidactico = ApoyoDidacticoInstrumentacion::create($request->all());

        return response()->json([
            'message' => 'Apoyo didáctico creado exitosamente',
            'data' => $apoyoDidactico
        ], 201);
    }

    // Crear múltiples apoyos didácticos
    public function createMultiple(Request $request)
    {
        $request->validate([
            'apoyos_didacticos' => 'required|array',
            'apoyos_didacticos.*.id_instrumentacion' => 'required|exists:instrumentacion,id_instrumentacion',
            'apoyos_didacticos.*.descripcion' => 'required|string',
            'apoyos_didacticos.*.orden' => 'nullable|integer'
        ]);

        $apoyosDidacticos = [];
        foreach ($request->apoyos_didacticos as $apoyoData) {
            $apoyosDidacticos[] = ApoyoDidacticoInstrumentacion::create($apoyoData);
        }

        return response()->json([
            'message' => 'Apoyos didácticos creados exitosamente',
            'data' => $apoyosDidacticos
        ], 201);
    }

    // Actualizar un apoyo didáctico
    public function updateOne(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'orden' => 'nullable|integer'
        ]);

        $apoyoDidactico = ApoyoDidacticoInstrumentacion::findOrFail($id);
        $apoyoDidactico->update($request->all());

        return response()->json([
            'message' => 'Apoyo didáctico actualizado exitosamente',
            'data' => $apoyoDidactico
        ]);
    }

    // Actualizar múltiples apoyos didácticos
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'apoyos_didacticos' => 'required|array',
            'apoyos_didacticos.*.id_apoyo_didactico' => 'required|exists:apoyos_didacticos_instrumentacion,id_apoyo_didactico',
            'apoyos_didacticos.*.descripcion' => 'sometimes|required|string',
            'apoyos_didacticos.*.orden' => 'nullable|integer'
        ]);

        $apoyosActualizados = [];
        foreach ($request->apoyos_didacticos as $apoyoData) {
            $apoyo = ApoyoDidacticoInstrumentacion::findOrFail($apoyoData['id_apoyo_didactico']);
            $apoyo->update($apoyoData);
            $apoyosActualizados[] = $apoyo;
        }

        return response()->json([
            'message' => 'Apoyos didácticos actualizados exitosamente',
            'data' => $apoyosActualizados
        ]);
    }
}