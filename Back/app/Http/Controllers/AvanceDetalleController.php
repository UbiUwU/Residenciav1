<?php

namespace App\Http\Controllers;

use App\Models\AvanceDetalle;
use Illuminate\Http\Request;

class AvanceDetalleController extends Controller
{
    public function index()
    {
        $detalles = AvanceDetalle::with(['avance', 'tema', 'tema.subtemas'])->get();

        return response()->json($detalles);
    }

    public function show($id)
    {
        $detalle = AvanceDetalle::with(['avance', 'tema', 'subtema'])->find($id);

        if (! $detalle) {
            return response()->json(['message' => 'Detalle no encontrado'], 404);
        }

        return response()->json($detalle);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_avance' => 'required|integer|exists:avance,id_avance',
            'id_tema' => 'nullable|integer|exists:tema,id_Tema',
            'porcentaje_aprobacion' => 'nullable|numeric|min:0|max:100',
            'requiere_firma_docente' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        $detalle = AvanceDetalle::create($request->all());

        return response()->json([
            'message' => 'Detalle agregado correctamente',
            'detalle' => $detalle,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $detalle = AvanceDetalle::find($id);

        if (! $detalle) {
            return response()->json(['message' => 'Detalle no encontrado'], 404);
        }

        $request->validate([
            'id_tema' => 'nullable|integer|exists:tema,id_Tema',
            'porcentaje_aprobacion' => 'nullable|numeric|min:0|max:100',
            'requiere_firma_docente' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        $detalle->update($request->all());

        return response()->json([
            'message' => 'Detalle actualizado correctamente',
            'detalle' => $detalle,
        ]);
    }

    public function destroy($id)
    {
        $detalle = AvanceDetalle::find($id);

        if (! $detalle) {
            return response()->json(['message' => 'Detalle no encontrado'], 404);
        }

        $detalle->delete();

        return response()->json(['message' => 'Detalle eliminado correctamente']);
    }
}
