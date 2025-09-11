<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlumnoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'numerocontrol'    => $this->numerocontrol,
            'nombre'           => $this->nombre,
            'apellidopaterno'  => $this->apellidopaterno,
            'apellidomaterno'  => $this->apellidomaterno,
            'clavecarrera'     => $this->clavecarrera,

            // Relación con carga académica general
            'cargas_academicas' => CargaAcademicaGeneralResource::collection($this->whenLoaded('cargasGenerales')),
        ];
    }
}
