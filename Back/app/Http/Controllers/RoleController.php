<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = DB::select('SELECT * FROM get_all_roles()');

        return response()->json($roles);
    }

    public function show($id)
    {
        $role = DB::select('SELECT * FROM get_role_by_id(?)', [$id]);
        if (empty($role)) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        return response()->json($role[0]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'IdRol' => 'required|integer|min:1',
            'Nombre' => 'required|string|max:50',
        ]);

        $result = DB::select('SELECT insert_role(?, ?) AS message', [
            $request->IdRol,
            $request->Nombre,
        ]);

        return response()->json(['message' => $result[0]->message]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:50',
        ]);

        $result = DB::select('SELECT update_role(?, ?) AS message', [
            $id,
            $request->Nombre,
        ]);

        return response()->json(['message' => $result[0]->message]);
    }

    public function destroy($id)
    {
        $result = DB::select('SELECT delete_role(?) AS message', [$id]);

        return response()->json(['message' => $result[0]->message]);
    }
}
