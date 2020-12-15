<?php

namespace App\Http\Resources\Krs;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Kelas\listCollection as kelasCollection;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'idKrs' => $this->krsdtId,
            'semesterId' => $this->krs->semProdi->sempSemId,
            'kelas' => new kelasCollection($this->kelas),
            'status'    => $this->krsdtApproved,
            'nilai' => $this->krsdtKodeNilai
        ];
    }
}
