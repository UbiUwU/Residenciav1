<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    // Obtener todos los alumnos
    public function index()
    {
        $alumnos = DB::select("SELECT * FROM get_all_alumnos()");

        return response()->json([
            'success' => true,
            'data' => $alumnos
        ]);
    }

    // Obtener un alumno por número de control
    public function show($numeroControl)
    {
        $alumno = DB::select("SELECT * FROM get_alumno_by_id(?)", [$numeroControl]);

        if (empty($alumno)) {
            return response()->json([
                'success' => false,
                'message' => 'Alumno no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $alumno[0]
        ]);
    }

    // Crear un nuevo alumno
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_control' => 'required|integer|unique:alumnos,NumeroControl',
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'id_usuario' => 'required|integer|exists:usuarios,id',
            'clave_carrera' => 'required|string|exists:carreras,ClaveCarrera'
        ]);

        try {
            $result = DB::select("SELECT insert_alumno(?, ?, ?, ?, ?, ?) AS result", [
                $validated['numero_control'],
                $validated['nombre'],
                $validated['apellido_paterno'],
                $validated['apellido_materno'],
                $validated['id_usuario'],
                $validated['clave_carrera']
            ]);

            $message = $result[0]->result;

            if (str_contains($message, 'Error')) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'numero_control' => $validated['numero_control']
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear alumno: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    // Actualizar un alumno
    public function update(Request $request, $numeroControl)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'apellido_paterno' => 'sometimes|string|max:255',
            'apellido_materno' => 'sometimes|string|max:255',
            'clave_carrera' => 'sometimes|string|exists:carreras,clavecarrera'
        ]);

        try {
            $result = DB::select("SELECT update_alumno(?, ?, ?, ?, ?) AS result", [
                $numeroControl,
                $validated['nombre'] ?? null,
                $validated['apellido_paterno'] ?? null,
                $validated['apellido_materno'] ?? null,
                $validated['clave_carrera'] ?? null
            ]);

            $message = $result[0]->result;

            if (str_contains($message, 'Error')) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $validated
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar alumno: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    // Eliminar un alumno
    public function destroy($numeroControl)
    {
        try {
            $result = DB::select("SELECT delete_alumno(?) AS result", [$numeroControl]);
            $message = $result[0]->result;

            if (str_contains($message, 'Error')) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar alumno: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    // 1. Ver horario del alumno
    public function getHorario($numeroControl)
    {
        $result = DB::select("SELECT * FROM public.get_horario_alumno(?)", [$numeroControl]);

        if (empty($result)) {
            return response()->json([]);
        }

        // Supongamos que la función devuelve un solo registro con un campo JSON en texto
        $jsonString = $result[0]->get_horario_alumno ?? null;

        if ($jsonString === null) {
            return response()->json([]);
        }

        // Decodificar el JSON para obtener un array PHP
        $horarioArray = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error' => 'Error al decodificar JSON del horario',
                'message' => json_last_error_msg(),
            ], 500);
        }

        // Retornar el JSON ya decodificado para mejor visualización
        return response()->json($horarioArray);
    }


    // 2. Registrar usuario y alumno
    public function registrarAlumno(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string',
            'numerocontrol' => 'required',
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'clavecarrera' => 'required'
        ]);

        $userExists = DB::select("SELECT idusuario FROM usuarios WHERE correo = ? LIMIT 1", [$request->correo]);

        if (!empty($userExists)) {
            return response()->json(['success' => false, 'message' => 'Correo ya registrado'], 409);
        }

        $nuevoUsuario = DB::select("SELECT * FROM insert_usuario(?, ?, 1)", [
            $request->correo,
            $request->password
        ]);

        $idUsuarioResult = DB::select("SELECT idusuario FROM usuarios WHERE correo = ?", [$request->correo]);
        $idUsuario = $idUsuarioResult[0]->idusuario;

        DB::select("SELECT * FROM insert_alumno(?, ?, ?, ?, ?, ?)", [
            $request->numerocontrol,
            $request->nombre,
            $request->apellido_paterno,
            $request->apellido_materno,
            $idUsuario,
            $request->clavecarrera
        ]);

        return response()->json(['success' => true, 'message' => 'Alumno registrado correctamente']);
    }



    // 3. Cambiar contraseña
    public function cambiarContrasena(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string'
        ]);

        $usuario = DB::select("SELECT idusuario, idrol FROM usuarios WHERE correo = ?", [$request->correo]);

        if (empty($usuario)) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }

        $idusuario = $usuario[0]->idusuario;
        $idrol = $usuario[0]->idrol;

        DB::select("SELECT * FROM update_usuario(?, ?, ?, ?)", [
            $idusuario,
            $request->correo,
            $request->password,
            $idrol
        ]);

        return response()->json(['success' => true, 'message' => 'Contraseña actualizada']);
    }

    // 4. Ver computadoras disponibles
    public function computadorasDisponibles()
    {
        $computadoras = DB::select("SELECT numeroinventario, claveaula, marca, estado FROM computadora ORDER BY claveaula, estado, numeroinventario");
        return response()->json($computadoras);
    }

    // 5. Ver si ya tiene una reservación activa
    public function tieneReservacionActiva(Request $request)
    {
        $request->validate([
            'control' => 'required',
            'fecha' => 'required|date'
        ]);

        $reservas = DB::select("SELECT * FROM reservacionalumnos WHERE numerocontrol = ? AND fechareservacion >= ?", [
            $request->control,
            $request->fecha
        ]);

        return response()->json(['reservas' => $reservas]);
    }

    // 6. Ver si ya reservó un equipo hoy
    public function yaReservoHoy(Request $request)
    {
        $request->validate([
            'control' => 'required',
            'inventario' => 'required',
            'fecha' => 'required|date'
        ]);

        $existe = DB::select("
            SELECT 1 FROM reservacionalumnos 
            WHERE numerocontrol = ? AND numeroinventario = ? AND fechareservacion = ?
            LIMIT 1", [$request->control, $request->inventario, $request->fecha]);

        return response()->json(['reservado' => !empty($existe)]);
    }

    // 7. Ver sus reservaciones activas
    public function reservacionesActivas(Request $request)
    {
        $request->validate([
            'control' => 'required',
            'fecha' => 'required|date'
        ]);

        $reservas = DB::select("
            SELECT ra.numeroinventario, ra.horainicio, ra.horafin, 
                   c.marca, c.claveaula, ra.fechareservacion
            FROM reservacionalumnos ra
            JOIN computadora c ON ra.numeroinventario = c.numeroinventario
            WHERE ra.numerocontrol = ? AND ra.fechareservacion = ?",
            [$request->control, $request->fecha]
        );

        return response()->json($reservas);
    }

    // 8. Insertar reservación
    public function reservarComputadora(Request $request)
    {
        $request->validate([
            'numerocontrol' => 'required',
            'numeroinventario' => 'required',
            'fechareservacion' => 'required|date',
            'horainicio' => 'required',
            'horafin' => 'required'
        ]);

        DB::select("SELECT insert_reservacion_alumno(?, ?, ?, ?, ?)", [
            $request->numerocontrol,
            $request->numeroinventario,
            $request->fechareservacion,
            $request->horainicio,
            $request->horafin
        ]);

        return response()->json(['success' => true, 'message' => 'Computadora reservada']);
    }

    // 9. Extender tiempo de uso
    public function extenderReserva(Request $request)
    {
        $request->validate([
            'inventario' => 'required',
            'horas' => 'required|integer',
            'minutos' => 'required|integer'
        ]);

        DB::update("
            UPDATE reservacionalumnos 
            SET horafin = horafin + INTERVAL '? hours' + INTERVAL '? minutes'
            WHERE numeroinventario = ?",
            [$request->horas, $request->minutos, $request->inventario]
        );

        return response()->json(['success' => true, 'message' => 'Reserva extendida']);
    }

    // 10. Cancelar reserva
    public function cancelarReserva(Request $request)
    {
        $request->validate([
            'control' => 'required',
            'inventario' => 'required'
        ]);

        DB::delete("
            DELETE FROM reservacionalumnos 
            WHERE numerocontrol = ? AND numeroinventario = ?",
            [$request->control, $request->inventario]
        );

        return response()->json(['success' => true, 'message' => 'Reservación cancelada']);
    }

    // 11. Registrar en bitácora
    public function registrarBitacora(Request $request)
    {
        $request->validate([
            'numerocontrol' => 'required',
            'numeroinventario' => 'required'
        ]);

        DB::select("SELECT * FROM insert_bitacora_alumno(?, ?)", [
            $request->numerocontrol,
            $request->numeroinventario
        ]);

        return response()->json(['success' => true, 'message' => 'Bitácora registrada']);
    }
    public function cerrarBitacora(Request $request)
    {
        $request->validate([
            'id_bitacora_alumno' => 'required|integer'
        ]);

        $result = DB::select("SELECT * FROM close_bitacora_alumno(?)", [
            $request->id_bitacora_alumno
        ]);

        if (empty($result)) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar la bitácora.'
            ], 400);
        }

        $row = $result[0];

        return response()->json([
            'success' => $row->tiempo_uso !== null,
            'message' => $row->mensaje,
            'tiempo_uso' => $row->tiempo_uso ?? null
        ]);
    }

    public function verBitacoraAlumno(Request $request)
    {
        $request->validate([
            'numerocontrol' => 'required|integer'
        ]);

        $bitacora = DB::select("
        SELECT 
            id_bitacora_alumno,
            numerocontrol,
            numeroinventario,
            hora_entrada,
            hora_salida,
            tiempo_uso
        FROM bitacora_alumnos
        WHERE numerocontrol = ?
        ORDER BY hora_entrada DESC
    ", [$request->numerocontrol]);

        if (empty($bitacora)) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros en la bitácora para este alumno.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bitácora encontrada.',
            'data' => $bitacora
        ]);
    }


}
