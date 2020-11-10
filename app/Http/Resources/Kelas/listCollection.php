<?php

namespace App\Http\Resources\Kelas;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Dosen\listCollection as dosenCollection;

class listCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'kelas_id' => $this->klsId,
            'kode_matkul' => $this->matkul->mkkurKode,
            'nama_matkul' => $this->matkul->mkkurNamaResmi,
            'sks_matkul'  => $this->matkul->mkkurJumlahSksKurikulum,
            'dosen' => dosenCollection::collection($this->dosens),
        ];
    }
}
