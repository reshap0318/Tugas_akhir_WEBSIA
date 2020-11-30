<?php

namespace App\Http\Resources\Semester;

use Illuminate\Http\Resources\Json\JsonResource;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->semId,
            'tahun' => $this->semTahun,
            'periode' => "Semester ".$this->ref->nmsemrNama
        ];
    }
}
