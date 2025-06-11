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

            $user = $result[0];
            $idusuario = $user->idusuario;
            $tipo = $user->idrol ?? null; // Asegúrate que la función devuelva esto

            $extraData = null;

            if ($tipo == '2') {
                $maestro = DB::select("SELECT * FROM get_maestro_by_idusuario(?)", [$idusuario]);
                $extraData = !empty($maestro) ? $maestro[0] : null;
            } elseif ($tipo == '1') {
                $alumno = DB::select("
                SELECT a.*, u.correo 
                FROM alumnos a
                JOIN usuarios u ON a.idusuario = u.idusuario 
                WHERE u.idusuario = ?", [$idusuario]);
                $extraData = !empty($alumno) ? $alumno[0] : null;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'detalle' => $extraData
                ],
                'token' => $user->token
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

            $usuario = $user[0];
            $tipo = $usuario->tipousuario ?? null;
            $detalle = null;

            if ($tipo === 'Maestro') {
                $maestro = DB::select("SELECT * FROM get_maestro_by_idusuario(?)", [$usuario->idusuario]);
                $detalle = !empty($maestro) ? $maestro[0] : null;
            } elseif ($tipo === 'Alumno') {
                $alumno = DB::select("
                SELECT a.*, u.correo 
                FROM alumnos a
                JOIN usuarios u ON a.idusuario = u.idusuario 
                WHERE u.idusuario = ?", [$usuario->idusuario]);
                $detalle = !empty($alumno) ? $alumno[0] : null;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $usuario,
                    'detalle' => $detalle
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
    /**
     * Cambio de contraseña por correo
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        try {
            // 1. Obtener idusuario e idrol del usuario a partir de su correo
            $userData = DB::select(
                "SELECT idusuario, idrol FROM usuarios WHERE correo = ?",
                [$request->input('correo')]
            );

            if (empty($userData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $idusuario = $userData[0]->idusuario;
            $idrol = $userData[0]->idrol;

            // 2. Ejecutar la función de actualización de usuario
            $updateResult = DB::select(
                "SELECT * FROM public.update_usuario(?, ?, ?, ?)",
                [
                    $idusuario,
                    $request->input('correo'),
                    $request->input('password'),
                    $idrol
                ]
            );

            // Puedes checar aquí si update_usuario devuelve algún indicador de éxito
            // Por ejemplo, si devuelve la fila actualizada o un booleano
            if (empty($updateResult)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo actualizar la contraseña'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor al cambiar contraseña',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
