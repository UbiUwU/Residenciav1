<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Http\Resources\AlumnoResource;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlumnoReworkController extends Controller
{
    /**
     * Listar todos los alumnos
     */
    public function index()
    {
        $alumnos = Alumno::get();
        return AlumnoResource::collection($alumnos);
    }

    /**
     * Mostrar un alumno en específico
     */
    public function show($numerocontrol)
{
    try {
        $alumno = Alumno::with([
            'cargasGenerales.detalles.horarioAsignatura.maestro'
        ])->findOrFail($numerocontrol);

        return new AlumnoResource($alumno);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Alumno no encontrado'
        ], 404);
    }
}

    /**
     * Crear un nuevo alumno
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidopaterno' => 'required|string|max:255',
            'apellidomaterno' => 'required|string|max:255',
            'correo' => 'required|email',
            'password' => 'sometimes|string|min:6', // Solo requerido si no existe usuario
            'clavecarrera' => 'required|string',
            'idrol' => 'sometimes|integer|exists:roles,idrol' // Solo requerido si crea usuario
        ]);

        DB::beginTransaction();

        try {
            // Verificar si el usuario ya existe
            $usuario = Usuario::where('correo', $validated['correo'])->first();

            if (!$usuario) {
                // Validar campos requeridos para crear nuevo usuario
                $request->validate([
                    'password' => 'required|string|min:6',
                    'idrol' => 'required|integer|exists:roles,idrol'
                ]);

                // Crear nuevo usuario
                $usuario = Usuario::create([
                    'correo' => $validated['correo'],
                    'password' => Hash::make($validated['password']),
                    'idrol' => $validated['idrol'],
                    'token' => null,
                ]);
            }

            // Verificar si ya existe un alumno con este usuario
            $alumnoExistente = Alumno::where('idusuario', $usuario->idusuario)->first();

            if ($alumnoExistente) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Ya existe un alumno asociado a este usuario',
                    'alumno' => $alumnoExistente
                ], 409);
            }

            // Crear el alumno
            $alumno = Alumno::create([
                'nombre' => $validated['nombre'],
                'apellidopaterno' => $validated['apellidopaterno'],
                'apellidomaterno' => $validated['apellidomaterno'],
                'idusuario' => $usuario->idusuario,
                'clavecarrera' => $validated['clavecarrera'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Alumno creado exitosamente',
                'alumno' => $alumno,
                'usuario' => $usuario,
                'usuario_existente' => !$usuario->wasRecentlyCreated // Indica si el usuario era existente
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el alumno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un alumno existente
     */
    public function update(Request $request, $numerocontrol)
    {
        // Validación de datos
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'apellidopaterno' => 'sometimes|string|max:255',
            'apellidomaterno' => 'sometimes|string|max:255',
            'correo' => 'sometimes|email',
            'password' => 'nullable|string|min:6',
            'clavecarrera' => 'sometimes|string',
            'idrol' => 'sometimes|integer|exists:roles,idrol'
        ]);

        DB::beginTransaction();

        try {
            // Buscar el alumno
            $alumno = Alumno::findOrFail($numerocontrol);
            $usuario = $alumno->usuario;

            // Bandera para saber si se actualizó el usuario
            $usuarioActualizado = false;

            // Actualizar datos del alumno
            $alumnoActualizado = false;
            $camposAlumno = ['nombre', 'apellidopaterno', 'apellidomaterno', 'clavecarrera'];

            foreach ($camposAlumno as $campo) {
                if (isset($validated[$campo]) && $alumno->$campo != $validated[$campo]) {
                    $alumno->$campo = $validated[$campo];
                    $alumnoActualizado = true;
                }
            }

            // Manejar actualización de usuario
            if (isset($validated['correo']) || isset($validated['password']) || isset($validated['idrol'])) {

                // Verificar si se quiere cambiar el correo
                if (isset($validated['correo']) && $validated['correo'] !== $usuario->correo) {
                    // Verificar si el nuevo correo ya existe en otro usuario
                    $usuarioExistente = Usuario::where('correo', $validated['correo'])
                        ->where('idusuario', '!=', $usuario->idusuario)
                        ->first();

                    if ($usuarioExistente) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'El correo ya está en uso por otro usuario'
                        ], 409);
                    }

                    $usuario->correo = $validated['correo'];
                    $usuarioActualizado = true;
                }

                // Actualizar password si se proporciona
                if (isset($validated['password']) && !empty($validated['password'])) {
                    $usuario->password = Hash::make($validated['password']);
                    $usuarioActualizado = true;
                }

                // Actualizar rol si se proporciona
                if (isset($validated['idrol']) && $usuario->idrol != $validated['idrol']) {
                    $usuario->idrol = $validated['idrol'];
                    $usuarioActualizado = true;
                }

                if ($usuarioActualizado) {
                    $usuario->save();
                }
            }

            if ($alumnoActualizado) {
                $alumno->save();
            }

            DB::commit();

            // Respuesta simplificada
            $response = [
                'message' => 'Alumno actualizado exitosamente',
                'alumno_actualizado' => $alumnoActualizado,
                'usuario_actualizado' => $usuarioActualizado
            ];

            // Opcional: agregar solo los campos actualizados si se quiere
            if ($alumnoActualizado) {
                $response['alumno'] = [
                    'numerocontrol' => $alumno->numerocontrol,
                    'nombre' => $alumno->nombre,
                    'apellidopaterno' => $alumno->apellidopaterno,
                    'apellidomaterno' => $alumno->apellidomaterno,
                    'clavecarrera' => $alumno->clavecarrera
                ];
            }

            if ($usuarioActualizado) {
                $response['usuario'] = [
                    'idusuario' => $usuario->idusuario,
                    'correo' => $usuario->correo
                ];
            }

            return response()->json($response, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar el alumno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un alumno
     */
    public function destroy($numerocontrol)
    {
        $alumno = Alumno::findOrFail($numerocontrol);
        $alumno->delete();

        return response()->json(['message' => 'Alumno eliminado correctamente']);
    }
}
