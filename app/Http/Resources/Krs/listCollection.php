<?php

namespace App\Http\Resources\Krs;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Dosen\listCollection as dosenCollection;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'idKrs' => $this->krsdtId,
            'kode_matkul' => $this->kelas->matkul->mkkurKode,
            'nama_matkul' => $this->kelas->matkul->mkkurNamaResmi,
            'sks_matkul'  => $this->kelas->matkul->mkkurJumlahSksKurikulum,
            'dosen' => dosenCollection::collection($this->kelas->dosens),
            'status'    => ''
        ];
    }
}
