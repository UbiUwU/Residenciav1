<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CargaAcademicaDetalleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'idcargadetalle' => $this->idcargadetalle,
            'clavehorario'   => $this->clavehorario,

            // Relación con horario asignatura maestro
            'horario_asignatura' => new HorarioAsignaturaMaestroResource($this->whenLoaded('horarioAsignatura')),
        ];
    }
}
