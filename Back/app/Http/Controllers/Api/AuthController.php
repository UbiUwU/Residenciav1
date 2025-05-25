<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        try {
            $result = DB::select("SELECT * FROM login_usuario(?, ?)", [
                $request->input('correo'),
                $request->input('password')
            ]);

            if (empty($result) || is_null($result[0]->idusuario)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            $maestro = DB::select("SELECT * FROM get_maestro_by_idusuario(?)", [$result[0]->idusuario]);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $result[0],
                    'maestro' => !empty($maestro) ? $maestro[0] : null
                ],
                'token' => $result[0]->token
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function me(Request $request)
    {
        try {
            $token = $request->bearerToken();
            
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token no proporcionado'
                ], 401);
            }

            $user = DB::select("SELECT * FROM usuarios WHERE token = ?", [$token]);
            
            if (empty($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido o sesión expirada'
                ], 401);
            }

            $maestro = DB::select("SELECT * FROM get_maestro_by_idusuario(?)", [$user[0]->idusuario]);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user[0],
                    'maestro' => !empty($maestro) ? $maestro[0] : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}