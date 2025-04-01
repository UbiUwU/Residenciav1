<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaestroController extends Controller
{
    // Obtener todos los maestros con paginación
    public function index(Request $request)
    {
        $maestros = Maestro::paginate(10); // Paginación con 10 resultados por página
        return response()->json($maestros);
    }

    // Buscar maestro por Tarjeta
    public function show($tarjeta)
    {
        $maestro = Maestro::find($tarjeta);

        if (!$maestro) {
            return response()->json(['error' => 'Maestro no encontrado'], 404);
        }

        return response()->json($maestro);
    }

    // Crear un nuevo maestro
    public function store(Request $request)
    {
        // Validaciones
        $validator = Validator::make($request->all(), [
            'tarjeta' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'apellidopaterno' => 'required|string|max:255',
            'apellidomaterno' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'escolaridad_licenciatura' => 'required|string|max:50',
            'estado_licenciatura' => 'required|string|max:1',
            'escolaridad_maestria' => 'nullable|string|max:50',
            'estado_maestria' => 'nullable|string|max:1',
            'id_departamento' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $maestro = Maestro::create($request->all());

        return response()->json($maestro, 201);
    }

    // Actualizar un maestro
    public function update(Request $request, $tarjeta)
    {
        $maestro = Maestro::find($tarjeta);

        if (!$maestro) {
            return response()->json(['error' => 'Maestro no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'Nombre' => 'string|max:255',
            'ApellidoPaterno' => 'string|max:255',
            'ApellidoMaterno' => 'string|max:255',
            'RFC' => 'string|max:13|unique:maestros,RFC,' . $tarjeta . ',Tarjeta',
            'Escolaridad_Licenciatura' => 'string|max:50',
            'Estado_Licenciatura' => 'string|max:1',
            'Escolaridad_Especializacion' => 'nullable|string|max:50',
            'Estado_Especializacion' => 'nullable|string|max:1',
            'Escolaridad_Maestria' => 'nullable|string|max:50',
            'Estado_Maestria' => 'nullable|string|max:1',
            'Escolaridad_Doctorado' => 'nullable|string|max:50',
            'Estado_Doctorado' => 'nullable|string|max:1',
            'Id_Departamento' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $maestro->update($request->all());

        return response()->json($maestro);
    }

    // Eliminar un maestro
    public function destroy($tarjeta)
    {
        $maestro = Maestro::find($tarjeta);

        if (!$maestro) {
            return response()->json(['error' => 'Maestro no encontrado'], 404);
        }

        $maestro->delete();

        return response()->json(['message' => 'Maestro eliminado exitosamente']);
    }

}
