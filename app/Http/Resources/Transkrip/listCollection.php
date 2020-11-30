<?php

namespace App\Http\Resources\Transkrip;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Semester\listCollection as listSemesterCollection;
use App\Http\Resources\Transkrip\listMatkulCollection;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'semester' => new listSemesterCollection($this->semProdi->semester),
            'matkul' => listMatkulCollection::collection($this->detailKrs()->whereNotNull('krsdtKodeNilai')->where('krsdtApproved',1)->where('krsdtIsDipakaiTranskrip',1)->get())
        ];
    }
}
