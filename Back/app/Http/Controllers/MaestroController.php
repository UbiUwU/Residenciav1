<?php

namespace App\Http\Controllers;

use App\Models\Maestro;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class MaestroController extends Controller
{
    public function index()
    {
        $maestros = Maestro::with(['usuario.rol', 'departamento'])->get();
        if ($maestros->isEmpty()) {
            return response()->json(['message' => 'No hay maestros registrados'], 404);
        }
        return response()->json($maestros, 200);
    }
    public function indexL()
    {
        $maestros = Maestro::InfoBasicaMaestros()->get();

        return response()->json($maestros);
    }

    public function show($tarjeta)
    {
        $maestro = Maestro::with(['usuario.rol', 'departamento'])->find($tarjeta);
        if (!$maestro) {
            return response()->json(['message' => 'Maestro no encontrado'], 404);
        }

        return response()->json($maestro, 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidopaterno' => 'required|string|max:255',
            'apellidomaterno' => 'required|string|max:255',
            'idusuario' => 'nullable|exists:usuarios,idusuario', // opción 2
            'correo' => 'required_without:idusuario|email|unique:usuarios,correo', // opción 1
            'password' => 'required_without:idusuario|string|min:6',
            'idrol' => 'required_without:idusuario|exists:roles,idrol',
            'rfc' => 'required|string|max:13',
            'escolaridad_licenciatura' => 'required|string|max:50',
            'estado_licenciatura' => 'required|string|max:1',
            'escolaridad_especializacion' => 'nullable|string|max:50',
            'estado_especializacion' => 'nullable|string|max:1',
            'escolaridad_maestria' => 'nullable|string|max:50',
            'estado_maestria' => 'nullable|string|max:1',
            'escolaridad_doctorado' => 'nullable|string|max:50',
            'estado_doctorado' => 'nullable|string|max:1',
            'id_departamento' => 'required|exists:departamentos,id_departamento',
        ]);

        try {
            $maestro = DB::transaction(function () use ($request) {

                // Si viene idusuario, usarlo directamente
                if ($request->filled('idusuario')) {
                    $idusuario = $request->idusuario;
                } else {
                    // Si no viene, crear usuario
                    $usuario = Usuario::create([
                        'correo' => $request->correo,
                        'password' => bcrypt($request->password),
                        'idrol' => $request->idrol,
                        'token' => null
                    ]);

                    $idusuario = $usuario->idusuario;
                }

                // Crear maestro asociado
                $maestro = Maestro::create([
                    'nombre' => $request->nombre,
                    'apellidopaterno' => $request->apellidopaterno,
                    'apellidomaterno' => $request->apellidomaterno,
                    'idusuario' => $idusuario,
                    'rfc' => $request->rfc,
                    'escolaridad_licenciatura' => $request->escolaridad_licenciatura,
                    'estado_licenciatura' => $request->estado_licenciatura,
                    'escolaridad_especializacion' => $request->escolaridad_especializacion,
                    'estado_especializacion' => $request->estado_especializacion,
                    'escolaridad_maestria' => $request->escolaridad_maestria,
                    'estado_maestria' => $request->estado_maestria,
                    'escolaridad_doctorado' => $request->escolaridad_doctorado,
                    'estado_doctorado' => $request->estado_doctorado,
                    'id_departamento' => $request->id_departamento,
                ]);

                return $maestro;
            });

            return response()->json([
                'message' => 'Maestro creado exitosamente',
                'maestro' => $maestro->load(['usuario.rol', 'departamento'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el maestro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $tarjeta)
    {
        $maestro = Maestro::find($tarjeta);
        if (!$maestro) {
            return response()->json(['message' => 'Maestro no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'apellidopaterno' => 'sometimes|required|string|max:255',
            'apellidomaterno' => 'sometimes|required|string|max:255',
            'rfc' => 'sometimes|required|string|size:13',
            'escolaridad_licenciatura' => 'sometimes|required|string|max:50',
            'estado_licenciatura' => 'sometimes|required|string|size:1',
            'escolaridad_especializacion' => 'sometimes|required|string|max:50',
            'estado_especializacion' => 'sometimes|required|string|size:1',
            'escolaridad_maestria' => 'sometimes|required|string|max:50',
            'estado_maestria' => 'sometimes|required|string|size:1',
            'escolaridad_doctorado' => 'sometimes|required|string|max:50',
            'estado_doctorado' => 'sometimes|required|string|size:1',
            'id_departamento' => 'sometimes|required|integer|exists:departamentos,id_departamento',

            // 👇 validaciones para usuario
            'correo' => 'sometimes|required|email|unique:usuarios,correo,' . $maestro->idusuario . ',idusuario',
            'password' => 'sometimes|nullable|string|min:6',
            'idrol' => 'sometimes|required|integer|exists:roles,idrol',
        ]);

        try {
            // 🔹 Actualizar maestro
            $maestro->update($request->only([
                'nombre',
                'apellidopaterno',
                'apellidomaterno',
                'rfc',
                'escolaridad_licenciatura',
                'estado_licenciatura',
                'escolaridad_especializacion',
                'estado_especializacion',
                'escolaridad_maestria',
                'estado_maestria',
                'escolaridad_doctorado',
                'estado_doctorado',
                'id_departamento'
            ]));

            // 🔹 Actualizar usuario relacionado
            $usuario = $maestro->usuario;
            if ($usuario) {
                $usuario->update([
                    'correo' => $request->has('correo') ? $request->correo : $usuario->correo,
                    'idrol' => $request->has('idrol') ? $request->idrol : $usuario->idrol,
                    'password' => $request->filled('password')
                        ? bcrypt($request->password)
                        : $usuario->password,
                ]);
            }

            $maestro->load([
                'usuario:idusuario,correo,idrol',
                'departamento'
            ]);

            return response()->json([
                'message' => 'Maestro actualizado exitosamente',
                'maestro' => $maestro
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function destroy($tarjeta)
    {
        $maestro = Maestro::find($tarjeta);
        if (!$maestro) {
            return response()->json(['message' => 'Maestro no encontrado'], 404);
        }

        try {
            $maestro->delete();
            return response()->json(['message' => 'Maestro eliminado exitosamente'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
