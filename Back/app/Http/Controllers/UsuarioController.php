<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class UsuarioController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        $usuarios = Usuario::with('rol')->get();
        return response()->json($usuarios);
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        $usuario = Usuario::with('rol')->find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json($usuario);
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string|min:6',
            'idrol' => 'required|integer|min:1|exists:roles,idrol',
        ]);

        try {
            // Llamar a la función de PostgreSQL
            $result = DB::selectOne(
                'SELECT public.insert_usuario(:correo, :password, :idrol) AS idusuario',
                [
                    'correo' => $request->correo,
                    'password' => $request->password,
                    'idrol' => $request->idrol
                ]
            );

            $idusuario = $result->idusuario;

            $usuario = Usuario::with('rol')->find($idusuario);

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'usuario' => $usuario
            ], 201);

        } catch (QueryException $e) {
            // Capturar error de violación de UNIQUE o RAISE EXCEPTION
            $errorMessage = $e->getMessage();

            // Opcional: identificar si es correo duplicado
            if (str_contains($errorMessage, 'Error: El correo ya está registrado')) {
                return response()->json([
                    'message' => 'El correo ya está registrado.'
                ], 400);
            }

            return response()->json([
                'message' => $errorMessage
            ], 400);
        }
    }

    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        // Validación
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string|min:6',
            'idrol' => 'required|integer|min:1|exists:roles,idrol',
        ]);

        try {
            // Llamar a la función PostgreSQL
            $result = DB::selectOne(
                'SELECT public.update_usuario(:idusuario, :correo, :password, :idrol) AS mensaje',
                [
                    'idusuario' => $id,
                    'correo' => $request->correo,
                    'password' => $request->password,
                    'idrol' => $request->idrol
                ]
            );

            $mensaje = $result->mensaje;

            // Revisar si hubo error
            if (str_starts_with($mensaje, 'Error:')) {
                return response()->json(['message' => $mensaje], 400);
            }

            // Traer el usuario actualizado
            $usuario = \App\Models\Usuario::with('rol')->find($id);

            return response()->json([
                'message' => $mensaje,
                'usuario' => $usuario
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
}
