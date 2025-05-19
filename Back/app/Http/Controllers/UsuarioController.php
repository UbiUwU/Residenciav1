<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function getAll()
    {
        $usuarios = DB::select('SELECT * FROM get_all_usuarios()');
        return response()->json($usuarios);
    }

    public function getById($id)
    {
        $usuario = DB::select('SELECT * FROM get_usuario_by_id(?)', [$id]);
        return response()->json($usuario);
    }

    public function insert(Request $request)
    {
        $correo = $request->input('correo');
        $password = $request->input('password');
        $id_rol = $request->input('id_rol');

        $result = DB::select('SELECT insert_usuario(?, ?, ?) AS mensaje', [$correo, $password, $id_rol]);

        return response()->json(['mensaje' => $result[0]->mensaje]);
    }

    public function update(Request $request, $id)
    {
        $correo = $request->input('correo');
        $password = $request->input('password');
        $id_rol = $request->input('id_rol');

        $result = DB::select('SELECT update_usuario(?, ?, ?, ?) AS mensaje', [$id, $correo, $password, $id_rol]);

        return response()->json(['mensaje' => $result[0]->mensaje]);
    }

    public function delete($id)
    {
        $result = DB::select('SELECT delete_usuario(?) AS mensaje', [$id]);
        return response()->json(['mensaje' => $result[0]->mensaje]);
    }
}
