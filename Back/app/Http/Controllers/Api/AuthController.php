<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string'
        ]);

        $correo = $request->input('correo');
        $password = $request->input('password');

        // Ejecutar la función PostgreSQL
        $result = DB::select("SELECT * FROM login_usuario(?, ?)", [$correo, $password]);

        if (empty($result) || is_null($result[0]->idusuario)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => $result[0]
        ]);
    }
}