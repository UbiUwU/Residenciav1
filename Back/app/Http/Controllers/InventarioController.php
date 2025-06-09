<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    // 1. Verificar si el equipo existe
    public function verificarEquipo($num_inventario)
    {
        $existe = DB::select("
            SELECT 1 FROM computadora WHERE numeroinventario = ? LIMIT 1",
            [$num_inventario]
        );

        return response()->json(['existe' => !empty($existe)]);
    }

    // 2. Cambiar estado del equipo a "reservado"
    public function marcarReservado($inventario)
    {
        DB::update("
            UPDATE computadora SET estado = 'reservado' 
            WHERE numeroinventario = ?", [$inventario]);

        return response()->json(['success' => true, 'estado' => 'reservado']);
    }

    // 3. Cambiar estado del equipo a "ocupado"
    public function marcarOcupado($inventario)
    {
        DB::update("
            UPDATE computadora SET estado = 'ocupado' 
            WHERE numeroinventario = ?", [$inventario]);

        return response()->json(['success' => true, 'estado' => 'ocupado']);
    }

    // 4. Liberar equipo (estado: disponible)
    public function liberarEquipo($inventario)
    {
        DB::update("
            UPDATE computadora SET estado = 'disponible' 
            WHERE numeroinventario = ?", [$inventario]);

        return response()->json(['success' => true, 'estado' => 'disponible']);
    }
}
