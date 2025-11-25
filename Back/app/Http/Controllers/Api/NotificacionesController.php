<?php

// app/Http/Controllers/Api/NotificacionController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\TipoNotificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificacionesController extends Controller
{
    // Obtener todas las notificaciones del usuario autenticado
    public function index()
    {
        $result = DB::select('SELECT * FROM public.notificaciones');
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
                'detalle' => json_last_error_msg(),
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

        if (empty($notificacionUsuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron notificaciones para este usuario.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $notificacionUsuario,
        ]);

    }

    // Eliminar notificación
    public function destroy($id)
    {
        try {
            $result = DB::delete('DELETE FROM notificacionesUsuario WHERE idNotificaciones = ?', [$id]);
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notificación eliminada correctamente',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la notificación',
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error al eliminar notificación: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la notificación',
            ], 500);
        }
    }

    // Obtener tipos de notificación
    public function tipos()
    {
        $tipos = TipoNotificacion::all();

        return response()->json([
            'success' => true,
            'data' => $tipos,
        ]);
    }

    // Crear una nueva notificación (para admin/profesores)
    public function store(Request $request) {}

    // insertar notificacion de usuario

    public function insertNotificacionUsuario(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:200',
            'mensaje' => 'required|string',
            'tipo_notificacion_id' => 'required|integer|exists:tiponotificaciones,idtiponotificaciones',
            'fecha_hora' => 'required|date',
            'usuarios' => 'required|array',
            'usuarios.*' => 'integer|exists:usuarios,idusuario',
            'carreras' => 'nullable|array',
            'carreras.*' => 'string|exists:carreras,clave',
        ]);

        DB::beginTransaction();

        try {
            // Insertar notificación
            $notificacionId = DB::selectOne('
            INSERT INTO notificaciones (titulonotificaciones, mensaje, idtiponotificaciones, fechahoranotificacion, fechacreacion)
            VALUES (?, ?, ?, ?, ?)
            RETURNING idnotificaciones',
                [
                    $validatedData['titulo'],
                    $validatedData['mensaje'],
                    $validatedData['tipo_notificacion_id'],
                    $validatedData['fecha_hora'],
                    now(),
                ]
            )->idnotificaciones;

            // Insertar usuarios relacionados
            if (! empty($validatedData['usuarios'])) {
                $usuariosValues = array_map(function ($usuarioId) use ($notificacionId) {
                    return "({$notificacionId}, {$usuarioId})";
                }, $validatedData['usuarios']);

                DB::insert('
                INSERT INTO notificacionesusuario (idnotificaciones, idusuario)
                VALUES '.implode(',', $usuariosValues)
                );
            }

            // Insertar carreras relacionadas
            if (! empty($validatedData['carreras'])) {
                $carrerasValues = array_map(function ($carreraClave) use ($notificacionId) {
                    return "({$notificacionId}, '{$carreraClave}')";
                }, $validatedData['carreras']);

                DB::insert('
                INSERT INTO notificacionescarrera (idnotificaciones, clavecarrera)
                VALUES '.implode(',', $carrerasValues)
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notificación creada exitosamente',
                'notificacion_id' => $notificacionId,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la notificación',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
