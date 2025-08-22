<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HorarioAsignaturaMaestroResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'clavehorario' => $this->clavehorario,
            'tarjeta' => $this->tarjeta,
            'claveaula' => $this->claveaula,
            'clavegrupo' => $this->clavegrupo,
            'claveasignatura' => $this->claveasignatura,
            'idperiodoescolar' => $this->idperiodoescolar,

            // 🔹 Horarios por día
            'lunes' => [
                'hi' => $this->lunes_hi,
                'hf' => $this->lunes_hf,
            ],
            'martes' => [
                'hi' => $this->martes_hi,
                'hf' => $this->martes_hf,
            ],
            'miercoles' => [
                'hi' => $this->miercoles_hi,
                'hf' => $this->miercoles_hf,
            ],
            'jueves' => [
                'hi' => $this->jueves_hi,
                'hf' => $this->jueves_hf,
            ],
            'viernes' => [
                'hi' => $this->viernes_hi,
                'hf' => $this->viernes_hf,
            ],
            'sabado' => [
                'hi' => $this->sabado_hi,
                'hf' => $this->sabado_hf,
            ],

            // 🔹 Relaciones
            'maestro' => $this->whenLoaded('maestro', function () {
                return [
                    'tarjeta' => $this->maestro->tarjeta,
                    'nombre' => $this->maestro->nombre ?? null,
                ];
            }),

            'aula' => $this->whenLoaded('aula'),
            'grupo' => $this->whenLoaded('grupo'),
            'asignatura' => $this->whenLoaded('asignatura'),
            'periodoEscolar' => $this->whenLoaded('periodoEscolar'),

            // 🔹 Relación inversa
            'cargaDetalles' => CargaAcademicaDetalleResource::collection($this->whenLoaded('cargaDetalles')),
        ];
    }
}
