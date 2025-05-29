<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentacionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'clave_asignatura' => 'required|string',
            'caracterizacion' => 'required|string|max:255',
            'intencion_didactica' => 'required|string|max:255',
        ]);

        try {
            $id = DB::selectOne('SELECT crear_presentacion(?, ?, ?) AS id', [
                $request->clave_asignatura,
                $request->caracterizacion,
                $request->intencion_didactica
            ]);

            return response()->json([
                'success' => true,
                'id_presentacion' => $id->id
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'clave_asignatura' => 'required|string',
            'caracterizacion' => 'required|string|max:255',
            'intencion_didactica' => 'required|string|max:255',
        ]);

        try {
            $result = DB::selectOne('SELECT actualizar_presentacion(?, ?, ?, ?) AS actualizado', [
                $id,
                $request->clave_asignatura,
                $request->caracterizacion,
                $request->intencion_didactica
            ]);

            if ($result->actualizado) {
                return response()->json(['success' => true], 200);
            } else {
                return response()->json(['success' => false, 'error' => 'No se encontró la presentación.'], 404);
            }

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $result = DB::selectOne('SELECT eliminar_presentacion(?) AS eliminado', [$id]);

            if ($result->eliminado) {
                return response()->json(['success' => true], 200);
            } else {
                return response()->json(['success' => false, 'error' => 'No se encontró la presentación.'], 404);
            }

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
