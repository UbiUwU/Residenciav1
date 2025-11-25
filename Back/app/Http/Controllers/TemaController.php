<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Subtema;
use App\Models\Tema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TemaController extends Controller
{
    // Obtener todos los temas de una asignatura
    public function indexTemas($claveAsignatura)
    {
        try {
            $asignatura = Asignatura::findOrFail($claveAsignatura);

            $temas = Tema::with(['subtemasRecursivos', 'competenciasGenericas', 'competenciasEspecificas', 'actividadesAprendizaje'])
                ->where('Clave_Asignatura', $claveAsignatura)
                ->orderBy('Numero')
                ->get();

            return response()->json([
                'success' => true,
                'asignatura' => $asignatura,
                'data' => $temas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los temas de la asignatura',
            ], 404);
        }
    }

    // Obtener un tema específico con toda su estructura
    public function showTema($idTema)
    {
        try {
            $tema = Tema::with([
                'asignatura',
                'subtemasRecursivos',
                'competenciasGenericas',
                'competenciasEspecificas',
                'actividadesAprendizaje',
            ])->findOrFail($idTema);

            return response()->json([
                'success' => true,
                'data' => $tema,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tema no encontrado',
            ], 404);
        }
    }

    // Crear un nuevo tema
    public function storeTema(Request $request, $claveAsignatura)
    {
        $validator = Validator::make($request->all(), [
            'Numero' => 'required|integer|min:1',
            'Nombre_Tema' => 'required|string|max:255',
            'subtemas' => 'sometimes|array',
            'subtemas.*.Nombre_Subtema' => 'required_with:subtemas|string|max:255',
            'subtemas.*.Orden' => 'sometimes|integer|min:1',
            'subtemas.*.Nivel' => 'sometimes|integer|min:1',
            'subtemas.*.hijos' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Verificar que la asignatura existe
            $asignatura = Asignatura::findOrFail($claveAsignatura);

            return DB::transaction(function () use ($asignatura, $request) {
                // Crear el tema
                $tema = Tema::create([
                    'Clave_Asignatura' => $asignatura->ClaveAsignatura,
                    'Numero' => $request->Numero,
                    'Nombre_Tema' => $request->Nombre_Tema,
                ]);

                // Crear subtemas si vienen en el request
                if ($request->has('subtemas')) {
                    $this->crearSubtemasRecursivos($tema->id_Tema, $request->subtemas, null);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Tema creado exitosamente',
                    'data' => $tema->load('subtemasRecursivos'),
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el tema',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Actualizar un tema
    public function updateTema(Request $request, $idTema)
    {
        $validator = Validator::make($request->all(), [
            'Numero' => 'sometimes|integer|min:1',
            'Nombre_Tema' => 'sometimes|string|max:255',
            'subtemas' => 'sometimes|array',
            'subtemas.*.id_Subtema' => 'sometimes|integer|exists:subtema,id_Subtema',
            'subtemas.*.Nombre_Subtema' => 'required_with:subtemas|string|max:255',
            'subtemas.*.Orden' => 'sometimes|integer|min:1',
            'subtemas.*.Nivel' => 'sometimes|integer|min:1',
            'subtemas.*._delete' => 'sometimes|boolean',
            'subtemas.*.hijos' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request, $idTema) {
                $tema = Tema::findOrFail($idTema);

                // Actualizar datos básicos del tema
                $tema->update($request->only(['Numero', 'Nombre_Tema']));

                // Procesar subtemas si vienen en el request
                if ($request->has('subtemas')) {
                    $this->procesarSubtemasRecursivos($tema->id_Tema, $request->subtemas, null);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Tema actualizado exitosamente',
                    'data' => $tema->fresh(['subtemasRecursivos']),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el tema',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar un tema y todos sus subtemas
    public function destroyTema($idTema)
    {
        try {
            return DB::transaction(function () use ($idTema) {
                $tema = Tema::withCount(['subtemas' => function ($query) {
                    $query->withTrashed();
                }])->findOrFail($idTema);

                // Eliminar todos los subtemas (se eliminan en cascada por la foreign key)
                $tema->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Tema eliminado exitosamente',
                    'deleted' => [
                        'tema_id' => $tema->id_Tema,
                        'subtemas_eliminados' => $tema->subtemas_count,
                    ],
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el tema',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ==================== MÉTODOS PARA SUBTEMAS ====================

    // Obtener un subtema específico con su estructura completa
    public function showSubtema($idSubtema)
    {
        try {
            $subtema = Subtema::with([
                'tema.asignatura',
                'padre',
                'hijosRecursivos',
            ])->findOrFail($idSubtema);

            return response()->json([
                'success' => true,
                'data' => $subtema,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subtema no encontrado',
            ], 404);
        }
    }

    // Crear un nuevo subtema
    public function storeSubtema(Request $request, $idTema)
    {
        $validator = Validator::make($request->all(), [
            'Subtema_Padre_id' => 'nullable|integer|exists:subtema,id_Subtema',
            'Nombre_Subtema' => 'required|string|max:255',
            'Orden' => 'required|integer|min:1',
            'Nivel' => 'sometimes|integer|min:1',
            'hijos' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tema = Tema::findOrFail($idTema);

            return DB::transaction(function () use ($tema, $request) {
                $subtema = Subtema::create([
                    'Tema_id' => $tema->id_Tema,
                    'Subtema_Padre_id' => $request->Subtema_Padre_id,
                    'Nombre_Subtema' => $request->Nombre_Subtema,
                    'Orden' => $request->Orden,
                    'Nivel' => $request->Nivel ?? 1,
                ]);

                // Crear hijos si vienen en el request
                if ($request->has('hijos')) {
                    $this->crearSubtemasRecursivos($tema->id_Tema, $request->hijos, $subtema->id_Subtema);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Subtema creado exitosamente',
                    'data' => $subtema->load('hijosRecursivos'),
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el subtema',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Actualizar un subtema
    public function updateSubtema(Request $request, $idSubtema)
    {
        $validator = Validator::make($request->all(), [
            'Nombre_Subtema' => 'sometimes|string|max:255',
            'Orden' => 'sometimes|integer|min:1',
            'Nivel' => 'sometimes|integer|min:1',
            'hijos' => 'sometimes|array',
            'hijos.*.id_Subtema' => 'sometimes|integer|exists:subtema,id_Subtema',
            'hijos.*.Nombre_Subtema' => 'required_with:hijos|string|max:255',
            'hijos.*.Orden' => 'sometimes|integer|min:1',
            'hijos.*.Nivel' => 'sometimes|integer|min:1',
            'hijos.*._delete' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request, $idSubtema) {
                $subtema = Subtema::findOrFail($idSubtema);

                // Actualizar datos básicos del subtema
                $subtema->update($request->only(['Nombre_Subtema', 'Orden', 'Nivel']));

                // Procesar hijos si vienen en el request
                if ($request->has('hijos')) {
                    $this->procesarSubtemasRecursivos($subtema->Tema_id, $request->hijos, $subtema->id_Subtema);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Subtema actualizado exitosamente',
                    'data' => $subtema->fresh(['hijosRecursivos']),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el subtema',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar un subtema y todos sus hijos (en cascada)
    public function destroySubtema($idSubtema)
    {
        try {
            $subtema = Subtema::withCount(['hijos' => function ($query) {
                $query->withTrashed();
            }])->findOrFail($idSubtema);

            $subtema->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subtema eliminado exitosamente',
                'deleted' => [
                    'subtema_id' => $subtema->id_Subtema,
                    'hijos_eliminados' => $subtema->hijos_count,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el subtema',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Crear subtemas recursivamente
    private function crearSubtemasRecursivos($temaId, $subtemas, $padreId = null)
    {
        foreach ($subtemas as $index => $subtemaData) {
            $subtema = Subtema::create([
                'Tema_id' => $temaId,
                'Subtema_Padre_id' => $padreId,
                'Nombre_Subtema' => $subtemaData['Nombre_Subtema'],
                'Orden' => $subtemaData['Orden'] ?? $index + 1,
                'Nivel' => $subtemaData['Nivel'] ?? 1,
            ]);

            // Crear hijos recursivamente
            if (isset($subtemaData['hijos']) && is_array($subtemaData['hijos'])) {
                $this->crearSubtemasRecursivos($temaId, $subtemaData['hijos'], $subtema->id_Subtema);
            }
        }
    }

    // Procesar subtemas recursivamente (para updates)
    private function procesarSubtemasRecursivos($temaId, $subtemas, $padreId = null)
    {
        foreach ($subtemas as $subtemaData) {
            // Eliminar subtema
            if (isset($subtemaData['_delete']) && $subtemaData['_delete'] === true && isset($subtemaData['id_Subtema'])) {
                $subtema = Subtema::where('id_Subtema', $subtemaData['id_Subtema'])
                    ->where('Tema_id', $temaId)
                    ->first();
                if ($subtema) {
                    $subtema->delete();
                }

                continue;
            }

            // Actualizar subtema existente
            if (isset($subtemaData['id_Subtema'])) {
                $subtema = Subtema::where('id_Subtema', $subtemaData['id_Subtema'])
                    ->where('Tema_id', $temaId)
                    ->firstOrFail();

                $subtema->update([
                    'Nombre_Subtema' => $subtemaData['Nombre_Subtema'],
                    'Orden' => $subtemaData['Orden'] ?? $subtema->Orden,
                    'Nivel' => $subtemaData['Nivel'] ?? $subtema->Nivel,
                ]);
            } else {
                // Crear nuevo subtema
                $subtema = Subtema::create([
                    'Tema_id' => $temaId,
                    'Subtema_Padre_id' => $padreId,
                    'Nombre_Subtema' => $subtemaData['Nombre_Subtema'],
                    'Orden' => $subtemaData['Orden'] ?? 1,
                    'Nivel' => $subtemaData['Nivel'] ?? 1,
                ]);
            }

            // Procesar hijos recursivamente
            if (isset($subtemaData['hijos']) && is_array($subtemaData['hijos'])) {
                $this->procesarSubtemasRecursivos($temaId, $subtemaData['hijos'], $subtema->id_Subtema);
            }
        }
    }

    // Obtener estructura completa de temas y subtemas de una asignatura
    public function estructuraCompleta($claveAsignatura)
    {
        try {
            $asignatura = Asignatura::with(['temas' => function ($query) {
                $query->with(['subtemasRecursivos'])
                    ->orderBy('Numero');
            }])->findOrFail($claveAsignatura);

            return response()->json([
                'success' => true,
                'data' => $asignatura,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la estructura de temas',
            ], 404);
        }
    }
}
