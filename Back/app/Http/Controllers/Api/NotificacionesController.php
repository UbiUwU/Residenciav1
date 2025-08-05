<?php
// app/Http/Controllers/Api/NotificacionController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\TipoNotificacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificacionesController extends Controller
{
    // Obtener todas las notificaciones del usuario autenticado
    public function index()
    {
        $result = DB::select("SELECT * FROM public.notificaciones");
        if (empty($result)) {
            return response()->json([]);
        }

        $jsonString = $result[0]->get_notificaciones_usuario ?? null;

        if ($jsonString === null) {
            return response()->json([]);
        }

        $notificaciones = json_decode($jsonString, true); // Decodificamos el string JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error' => 'No se pudo decodificar las notificaciones',
                'detalle' => json_last_error_msg()
            ], 500);
        }
        return response()->json($notificaciones);
    }

    public function show($Usuario_id)
{
        $notificacionUsuario = DB::select("SELECT 
    n.idNotificaciones,
    n.tituloNotificaciones,
    tn.tipoNotificacion,
    n.Mensaje,
    n.FechaHoraNotificacion,
    n.FechaCreacion,
    tn.descripcionNotificaciones
FROM 
    notificacionesUsuario nu
JOIN 
    notificaciones n ON nu.idNotificaciones = n.idNotificaciones
JOIN 
    tipoNotificaciones tn ON n.idTipoNotificaciones = tn.idTipoNotificaciones
WHERE 
    nu.idusuario = $Usuario_id
ORDER BY 
    n.FechaHoraNotificacion DESC;");


        if(empty($notificacionUsuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron notificaciones para este usuario.'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $notificacionUsuario
        ]);

}


    // Eliminar notificación
    public function destroy($id)
    {
       try{
        $result = DB::delete("DELETE FROM notificacionesUsuario WHERE idNotificaciones = ?", [$id]);
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Notificación eliminada correctamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la notificación'
            ], 404);
        }
       }catch(\Exception $e){
            Log::error('Error al eliminar notificación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la notificación'
            ], 500);
        }
    }

    // Obtener tipos de notificación
    public function tipos()
    {
        $tipos = TipoNotificacion::all();

        return response()->json([
            'success' => true,
            'data' => $tipos
        ]);
    }

    // Crear una nueva notificación (para admin/profesores)
    public function store(Request $request)
    {
        
    }
}