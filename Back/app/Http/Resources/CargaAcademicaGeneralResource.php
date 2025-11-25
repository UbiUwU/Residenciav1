<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CargaAcademicaGeneralResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'idcargageneral' => $this->idcargageneral,
            'numerocontrol' => $this->numerocontrol,

            // Relación con detalles
            'detalles' => CargaAcademicaDetalleResource::collection($this->whenLoaded('detalles')),
        ];
    }
}
