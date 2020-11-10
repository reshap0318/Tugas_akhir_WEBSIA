<?php

namespace App\Http\Resources\SemesterProdi;

use Illuminate\Http\Resources\Json\JsonResource;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'semester_prodi_id' => $this->sempId,
            'semester_tahun' => $this->semester->semTahun,
            'semester_periode' => $this->semester->semNmsemrId  
        ];
    }
}
